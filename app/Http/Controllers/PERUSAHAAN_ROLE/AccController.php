<?php

namespace App\Http\Controllers\PERUSAHAAN_ROLE;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Lamaran;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccController extends Controller
{
    public function pelamar() {
        $user = auth()->user()->id;
        $perusahaan = Perusahaan::where('user_id', $user)->first();
        $lamaran = Lamaran::where('id_perusahaan', $perusahaan->id)->with(['perusahaan','alumni'])->get();
        return response()->json($lamaran);
    }

public function showPelamar($id){
    $lamaran = Lamaran::with(['alumni.user', 'perusahaan'])->where('id', $id)->first();

    if (!$lamaran) {
        return response()->json(['message' => 'Lamaran tidak ditemukan'], 404);
    }

    return response()->json($lamaran, 200);
}


    public function setSiswa(Request $request, $id){
        $lamaran = Lamaran::find($id);
        $validator = Validator::make($request->all(), [
            'status_lamaran' => 'required'
        ]);

        $alumni = Alumni::where('id', $lamaran->id_alumni)->first();

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 200);
        }

        $alumni->update([
            'status' => 'Bekerja'
        ]);

        $lamaran->update([
            'status_lamaran' => $request->status_lamaran,
        ]);

        return response()->json([
            'message' => 'berhasil mengupdate status'
        ], 200);
    }
}
