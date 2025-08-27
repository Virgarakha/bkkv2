<?php

namespace App\Http\Controllers\USER_ROLE;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Lamaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DaftarLowonganController extends Controller
{
    public function daftar(Request $request){
        $userId = auth()->user()->id;
        $alumni = Alumni::where('user_id', $userId)->first();

        $validator = Validator::make($request->all(), [
            'id_perusahaan' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        Lamaran::create([
            'id_alumni' => $alumni->id,
            'status_lamaran' => 'sedang dicek',
            'id_perusahaan' => $request->id_perusahaan,
            'created_at' => now()
        ]);

        return response()->json([
            'message' => 'berhasil melamar pekerjaan!'
        ], 200);
    }

    public function lamaranKamu(){
        $getUser = auth()->user()->id;
        $userId = Alumni::where('user_id', $getUser)->first();
        $lamaran = Lamaran::where('id_alumni', $userId->id)->get();
        return response()->json($lamaran, 200);
    }

    public function cancelLamaran($id){
        $lamaran = Lamaran::find($id);

        $lamaran->update([
            'status_lamaran' => 'dibatalkan'
        ]);

        return response()->json([
            'message' => 'berhasil membatalkan lamaran'
        ], 200);
    }
}
