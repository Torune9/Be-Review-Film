<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct (){
        $this->middleware(['auth_api','verification']);
    }

    public function createOrUpdateProfile(Request $request)
    {

        $user = auth()->user();

        $validator = Validator::make($request->all(),[
            'age' => 'required|integer',
            'biodata' => 'required|string',
            'address' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                "message" => $validator->failed()
            ],400);
        }

        $data = $validator->validated();

        $data['user_id'] = $user->id;

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $data,
        );

        return response()->json([
            "message" => "profile berhasil di buat atau di update",
            "data" => $profile
        ],200);
    }

}
