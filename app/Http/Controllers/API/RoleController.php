<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth_api');
        $this->middleware('is_admin')->except('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::latest()->get();

        return response()->json([
            "message" => "Data berhasil tampil",
            "data" => $roles
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $role = Roles::create([
            'name' => $request->name
        ]);

        return response()->json([
            "message" => "Tambah role berhasil",
            "data" => $role
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Roles::find($id)->first();

        if(!$role){
            return response()->json([
                "message" => "data role tidak ditemukan"
            ],404);
        }

        return response()->json([
            "message" => "data detail role",
            "role" => $role
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $role = Roles::find($id)->first();


        if(!$role){
            return response()->json([
                "message" => "data role tidak ditemukan"
            ],404);
        }

        $role->update([
            "name" => $request->name ?? $role->name
        ]);

        return response()->json([
            "message" => "data role berhasil di update",
            "data" => $role
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::find($id);

        if(!$role){
            return response()->json([
                "message" => "role tidak ditemukan"
            ]);
        }

        $role->delete();

        return response()->json([
            "message" => "role berhasil dihapus"
        ]);
    }
}
