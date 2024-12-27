<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Genres;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
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
        $genres = Genres::latest()->get();

        return response()->json([
            "message" => "Tampil data genre berhasil",
            "data" => $genres
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $dataBody =  Genres::create($validator->validated());

        return response()->json([
            "message" => "Tambah Genre berhasil",
            "body" => $dataBody
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genres::findOrfail($id);

        if(!$genre) {
            return response()->json([
                "message" => "genre tidak ditemukan"
            ],404);
        }

        return response()->json([
            "message" => "Detail Data Genre",
            "data" => $genre
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $genre = Genres::find($id);


        if (!$genre) {
            return response()->json([
                'message' => 'Genre tidak ditemukan'
            ], 404);
        }


        $genre->name = $request->name ? $request->name : $genre->name;
        $genre->save();


        return response()->json([
            "message" => "Update Genre berhasil",
            "data" => $genre
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genres::find($id);

        if (!$genre) {
            return response()->json([
                "message" => "Genre tidak ditemukan"
            ], 404);
        }

        $genre->delete();

        return response()->json([
            "message" => "Hapus Genre berhasil"
        ], 200);
    }
}
