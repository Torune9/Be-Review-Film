<?php

namespace App\Http\Controllers\API;

use App\Models\Cast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CastController extends Controller
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
        $casts = Cast::latest()->get();

        return response()->json([
            "message" => "tampil data berhasil",
            "data" => $casts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'age'  => 'required|integer|digits_between:1,3',
            'bio' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $dataBody =  Cast::create($validator->validated());

        return response()->json([
            "message" => "Tambah Cast berhasil",
            "body" => $dataBody
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $casts = Cast::findOrfail($id);

        if(!$casts) {
            return response()->json([
                "message" => "genre tidak ditemukan"
            ],404);
        }

        return response()->json([
            "message" => "detail data cast",
            "data" => $casts
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
            'age'  => 'integer|digits_between:1,3',
            'bio'  => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $cast = Cast::find($id);


        if (!$cast) {
            return response()->json([
                'message' => 'Cast not found'
            ], 404);
        }


        $cast->name = $request->name ? $request->name : $cast->name;
        $cast->age = $request->age ? $request->age : $cast->age;
        $cast->bio = $request->bio ? $request->bio : $cast->bio;

        $cast->save();


        return response()->json([
            "message" => "Update Cast berhasil",
            "data" => $cast
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $casts = Cast::find($id);

        if (!$casts) {
            return response()->json([
                "message" => "Data tidak ditemukan"
            ], 404);
        }

        $casts->delete();

        return response()->json([
            "message" => "Hapus Cast berhasil"
        ], 200);
    }
}
