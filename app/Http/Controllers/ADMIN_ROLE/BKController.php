<?php

namespace App\Http\Controllers\ADMIN_ROLE;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class BKController extends Controller
{
    public function index(){
        $bk = User::where('role', 'bk')->get();
        return response()->json($bk, 200);
    }

    public function show($id){
        $bk = User::find($id);
        return response()->json($bk, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:user,email',
            'no_telp' => 'required|min:7',
            'alamat' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'bk',
            'otp' => 'null',
        ]);

        $code = strtoupper(Str::random(5));

        Otp::create([
            'user_id' => $user->id,
            'code' => $code,
            'created_at' => now()
        ]);

        $user->update([
            'otp' => $code
        ]);

        return response()->json([
            'message' => 'Berhasil membuat akun bk',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:user,email',
            'no_telp' => 'required|min:7',
            'alamat' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Berhasil meperbarui akun bk',
        ], 200);
    }

    public function destroy($id){
        $bk = User::find($id);
        $bk->delete();

        return response()->json([
            'message' => 'Berhasil menghapus akun'
        ], 200);
    }
}
