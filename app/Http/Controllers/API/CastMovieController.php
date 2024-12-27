<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CastMovie;
use App\Models\Casts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CastMovieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_api');

        $this->middleware('is_admin')->except(['index','show']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movie_cast = CastMovie::latest()->get();

        return response()->json([
            "message" => "Berhasil tampil cast movie",
            "data" => $movie_cast
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "name" => "required|string",
            "movie_id" => "required",
            "cast_id" => "required"
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => $validator->failed()
            ],400);
        }
        $data = $validator->validated();

        CastMovie::create($data);

        return response()->json([
            "message" => "Berhasil tambah cast movie"
        ],201);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail_cast_movie = CastMovie::with(['cast','movie'])->find($id);


        if(!$detail_cast_movie){
            return response()->json([
                "message" => "Detail cast movie tidak ditemukan"
            ],400);
        }

        return response()->json([
            "message" => "Berhasil tampil cast movie",
            "data" => $detail_cast_movie
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $validator->validated();

        $cast_movie = CastMovie::find($id);

        if (!$cast_movie) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $cast_movie->update([
            'name' => $data['name'] ?? $cast_movie->name,
            'cast_id' => $data['cast_id'] ?? $cast_movie->cast_id,
            'movie_id' => $data['movie_id'] ?? $cast_movie->movie_id,
        ]);

        return response()->json(['message' => 'Berhasil update cast movie', 'data' => $cast_movie], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cast_movie = CastMovie::find($id);

        $cast_movie->delete();

        return response()->json([
            "message" => "Berhasil delete cast movie"
        ]);
    }
}
