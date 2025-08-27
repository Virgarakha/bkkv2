<?php

namespace App\Http\Controllers\ADMIN_ROLE;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerusahaanController extends Controller
{
    public function index(){
        $perusahaan = User::where('role', 'perusahaan')->get();
        return response()->json($perusahaan, 200);
    }

    public function lowongan(){
        $perusahaan = Perusahaan::with('user')->get();
        return response()->json($perusahaan, 200);
    }

    public function show($id){
        $perusahaan = Perusahaan::where('id', $id)->with('user')->first();
        return response()->json($perusahaan, 200);
    }

    public function verified(Request $request, $id){
        $perusahaan = Perusahaan::find($id);
        $validator = Validator::make($request->all(), [
            'verifikasi' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $perusahaan->update([
            'verifikasi' => $request->verikasi,
        ]);

        return response()->json([
            'message' => 'berhasil mengupdate status vaerifikasi'
        ]);
    }

    public function destroy($id){
        $lowongan = Perusahaan::where('id', $id)->first();
        $lowongan->delete();

        return response()->json([
            'message' => 'Berhasil lowongan'
        ], 200);
    }
}
