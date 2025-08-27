<?php

namespace App\Http\Controllers\PERUSAHAAN_ROLE;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LowonganController extends Controller
{
    public function index(){
        $lowongan = Perusahaan::with('user')->get();
        return response()->json($lowongan, 200);
    }

    public function show($id){
        $lowongan = Perusahaan::where('id', $id)->with('user')->first();
        return response()->json($lowongan, 200);
    }

    public function store(Request $request, $id){
        $userId = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'telepon' => 'required',
            'deskripsi' => 'required',
            'industry' => 'required',
            'website' => 'required',
            'lowongan' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        Perusahaan::create([
            'user_id' => $userId,
            'telepon' => $request->telepon,
            'deskripsi' => $request->deskripsi,
            'industry' => $request->industry,
            'website' => $request->website,
            'lowongan' => $request->lowongan,
            'verifikasi' => 'belum',
        ]);

        return response()->json([
            'message' => 'berhasil membuat lowongan baru'
        ], 200);
    }


    public function update(Request $request, $id){
        $lowongan = Perusahaan::find('id', $id);
        $validator = Validator::make($request->all(), [
            'telepon' => 'required',
            'deskripsi' => 'required',
            'industry' => 'required',
            'website' => 'required',
            'lowongan' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $lowongan->update([
            'telepon' => $request->telepon,
            'deskripsi' => $request->deskripsi,
            'industry' => $request->industry,
            'website' => $request->website,
            'lowongan' => $request->lowongan,
        ]);

        return response()->json([
            'message' => 'berhasil mengedit lowongan'
        ], 200);
    }

    public function destroy($id){
        $lowongan = Perusahaan::find($id);
        $lowongan->delete();
        return response()->json([
            'message' => 'Berhasil menghapus data alumni'
        ], 200);
    }
}
