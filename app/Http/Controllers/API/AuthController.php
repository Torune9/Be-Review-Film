<?php

namespace App\Http\Controllers\API;

use App\Models\Otp;
use App\Models\Roles;
use App\Models\Users;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Mail\otpGenerate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function generateOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Users::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $otpCode = rand(100000, 999999);

        $expiresAt = Carbon::now()->addMinutes(15);

        $otp = Otp::updateOrCreate(
            ['user_id' => $user->id],
            ['otp' => $otpCode,'validate_until' => $expiresAt,]
        );

        Mail::to($user->email)->send(new otpGenerate($user));

        return response()->json(['message' => 'OTP sent successfully.']);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8',
            'role' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->failed(), 400);
        }

        $validated = $validator->validated();


        if($validated['password'] !== $validated['password_confirmation']){
            return response()->json([
                "message" => "password not match"
            ],400);
        }

        $user_email = Users::where('email',$request->email)->first();

        if($user_email){
            return response()->json([
                "message" => "email sudah terpakai"
            ],400);
        }

        $role_id = Roles::where('name', $request->role)->first();

        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role_id->id,
            'email_verified_at' => Carbon::now(),
        ]);

        $otpCode = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(15);

        $otp = Otp::create([
            'otp' => $otpCode,
            'user_id' => $user->id,
            'validate_until' => $expiresAt,
        ]);


        Mail::to($user->email)->send(new otpGenerate($user));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid email or password'], 400);
        }

        $user = Users::where('email',$request->email)->first();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout()
    {

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {

        $user =  auth()->user();

        return response()->json([
            $user
        ]);
    }

    public function verification_account(Request $request){

        $validator = Validator::make($request->all(),[
            'otp' => 'required|min:6|integer'
        ]);

        if($validator->fails()){
            return response()->json([
                "message" => "otp tidak valid"
            ],400);
        }

        $otp = Otp::where('otp',$request->otp)->first();

        if(!$otp){
            return response()->json([
                "message" => "Otp tidak ditemukan",
                "data" => $otp
            ],400);
        }

        $now = Carbon::now();

        if($now > $otp->validate_until){
            return response()->json([
                "message" => "Otp sudah kadaluarsa,silakan buat ulang kode otp anda",
                "time" => Carbon::now()
            ],400);
        }


        $user = Users::find($otp->user_id);

        $user->email_verified_at = Carbon::now();

        $user->save();

        $otp->delete();

        return response()->json([
            "message" => "Verifikasi akun berhasil"
        ]);
    }

}
