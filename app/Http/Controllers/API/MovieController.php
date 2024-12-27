<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Genres;
use App\Models\Movie;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class MovieController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth_api')->except(['index','show']);

        $this->middleware('is_admin')->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::latest()->get();

        return response()->json([
            "message" => "tampil data berhasil",
            "data" => $movies
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required',
            'summary' => 'string',
            'poster' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'year' => 'string|required',
            'genre' => 'string|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Gagal tambah movie",
                "error" => $validator->failed()
            ], 400);
        }

        $validate = $validator->validated();

        if ($request->hasFile('poster')) {

            $file = $request->file('poster')->getRealPath();


            $uploadFile = Cloudinary::upload($file, [
                'folder' => 'laravel-vue'
            ])->getSecurePath();

            $validate["poster"] = $uploadFile;
        }

        $genre_id = Genres::where('name', $request->genre)->first();

        if (!$genre_id) {
            return response()->json([
                "message" => "Genre Tidak Ditemukan"
            ],404);
        }
        $validate["genre_id"] = $genre_id->id;


        $movie = Movie::create($validate);

        return response()->json([
            "message" => "Tambah movie berhasil",
            "body" => $movie
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movie = Movie::find($id);

        if(!$movie){
            return response()->json([
                "message" => "Movie tidak ditemukan"
            ],404);
        }

        return response()->json([
            "message" => "Detail data movie",
            "data" => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required',
            'summary' => 'string',
            'poster' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'year' => 'string|required',
            'genre' => 'string|required'
        ]);

        $validate = $validator->validated();

        $movie = Movie::find($id);

        if(!$movie){
            return response()->json([
                "message" => "Movie tidak ditemukan"
            ],404);
        }

        $genre_id = $movie->genre_id;

        if($request->genre){
            $genre_id = Genres::where('name', $request->genre)->first();
            $validate["genre_id"] = $genre_id->id;
        }

        if (!$genre_id) {
            return response()->json([
                "message" => "Genre Tidak Ditemukan"
            ],404);
        }


        if ($request->hasFile('poster')) {

            $file = $request->file('poster')->getRealPath();


            $uploadFile = Cloudinary::upload($file, [
                'folder' => 'laravel-vue'
            ])->getSecurePath();

            $validate["poster"] = $uploadFile;
        }

        $movie->title = $request->title ? $request->title : $movie->title;

        $movie->poster = $request->poster ?
        $validate['poster'] : $movie->poster;

        $movie->genre_id = $request->genre ? $validate['genre_id'] : $movie->genre_id;

        $movie->summary = $request->summary ? $request->summary : $movie->summary;

        $movie->year = $request->year ? $request->year : $movie->year;

        $movie->save();

        return response()->json([
            "message" => "Data movie berhasil di update",
            "body" => $validate
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                "message" => "Movie tidak ditemukan"
            ], 404);
        }

        $movie->delete();

        return response()->json([
            "message" => "Hapus Movie berhasil"
        ], 200);
    }
}
