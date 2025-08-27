<?php

namespace App\Http\Controllers\BK_ROLE;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $users = Alumni::with('user')->get();
        return response()->json($users, 200);
    }

    public function show($id){
        $alumni = Alumni::where('id', $id)->with('user')->first();
        $user = User::where('id', $alumni->user_id)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json($alumni, 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'required|min:7',
            'alamat' => 'required',
            'password' => 'required|min:6',
            'nik' => 'required',
            'nis' => 'required',
            'nisn' => 'required',
            'ortu' => 'required',
            'jenis_kelamin' => 'required',
            'jurusan' => 'required',
            'tahun_lulus' => 'required',
            'skill' => 'required',
            'experience' => 'required',
            'cv' => 'required|file|mimes:pdf|max:2048',
            'status_ditrima_kerja' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $cvPath = $request->file('cv')->store('cv', 'public');

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'ortu' => $request->ortu,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jurusan' => $request->jurusan,
            'tahun_lulus' => $request->tahun_lulus,
            'skill' => $request->skill,
            'experience' => $request->experience,
            'cv' => $cvPath,
            'status_ditrima_kerja' => $request->status_ditrima_kerja
        ]);

        return response()->json(['message' => 'User berhasil ditambahkan'], 201);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'no_telp' => 'required|min:7',
            'alamat' => 'required',
            'password' => 'nullable|min:6',
            'nik' => 'required',
            'nis' => 'required',
            'nisn' => 'required',
            'ortu' => 'required',
            'jenis_kelamin' => 'required',
            'jurusan' => 'required',
            'tahun_lulus' => 'required',
            'skill' => 'required',
            'experience' => 'required',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
            'status_ditrima_kerja' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }
        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $alumni = Alumni::where('user_id', $user->id)->first();

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cv', 'public');
            $alumni->cv = $cvPath;
        }

        $alumni->update([
            'nik' => $request->nik,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'ortu' => $request->ortu,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jurusan' => $request->jurusan,
            'tahun_lulus' => $request->tahun_lulus,
            'skill' => $request->skill,
            'experience' => $request->experience,
            'status_ditrima_kerja' => $request->status_ditrima_kerja
        ]);

        return response()->json(['message' => 'User berhasil diupdate'], 200);
    }

    public function destroy($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        Alumni::where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }
}
