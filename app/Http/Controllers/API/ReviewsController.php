<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{

    public function __construct (){
        $this->middleware(['auth_api','verification']);
    }
    public function createOrUpdateReview(Request $request)
    {

        $user = auth()->user();

        $validator = Validator::make($request->all(),[
            'critic' => 'required',
            'rating' => 'required|digits_between:1,5',
            'movie_id' => 'required',
        ]);

        $data = $validator->validated();

        if($validator->fails()){
            return response()->json([
                "message" => $validator->errors()
            ]);
        }

        $data['user_id'] = $user->id;

        $review = Reviews::updateOrcreate(
            ['user_id' => $user->id],
            $data
        );

        return response()->json([
            "message" => "Review berhasil dibuat/diubah",
            "data" => $review
        ]);
    }
}
