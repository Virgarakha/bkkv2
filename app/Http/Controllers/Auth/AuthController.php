<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signup(RequesT $request){
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:user,email',
            'no_telp' => 'required|min:7',
            'alamat' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 402);
        }

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'otp' => '00000',
            'avatar' => 'default.jpg'
        ]);

        $user = User::where('email', $request->email)->first();

        $code = strtoupper(Str::random(5));

        Otp::create([
            'user_id' => $user->id,
            'code' => $code,
            'created_at' => now()
        ]);

        Mail::raw("Halo {$user->nama_lengkap},\n\nKode OTP kamu adalah: $code (berlaku 5 menit).", function ($message) use ($user) {$message->to($user->email)->subject('Kode OTP Verifikasi Akun');});

        return response()->json([
            'message' => 'berhasil membuat akun! silahkan simpan code ini',
            'code' => $code
        ], 200);
    }

    public function verifikasi(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|max:5',
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = Otp::where('user_id', $user->id)->first();
        if($request->code !== $otp->code){
            return response()->json([
                'message' => 'Maaf kode yang anda masukan tidak valid'
            ], 401);
        }

        $user->update([
            'otp' => $request->code
        ]);

        return response()->json([
            'message' => 'berhasil verifikasi akun!'
        ], 200);

    }

    public function signin(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 402);
        }

        if(!Auth::guard('web')->attempt($request->only('nama_lengkap', 'email', 'password', 'otp'))){
            return response()->json([
                'message' => 'nama atau email atau password tidak cocok!'
            ], 402);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        $code_user = Otp::where('user_id', $user->id)->first();

        if($user->otp !== $code_user->code){
            return response()->json([
                'message' => 'Maaf akun anda belum di verifikasi'
            ], 401);
        } else {
            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }

    }

    public function signout(Request $request){
        if(!$request->user()){
            return response()->json([
                'message' => 'Akun tidak ditemukan'
            ], 404);
        }

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'berhasil keluar!'
        ], 200);
    }


}
