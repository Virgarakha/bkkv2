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


class AlumniController extends Controller
{
    public function index()
    {
        $alumni = Alumni::with('user')->get();
        return response()->json([
            'data' => $alumni
        ], 200);
    }

    public function show($id){
        $alumni = Alumni::where('id', $id)->with('user');
        return response()->json($alumni, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:user,email',
            'no_telp' => 'required|min:7',
            'alamat' => 'required',
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
            'status' => 'required'
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
            'password' => Hash::make('SMKBISA'),
            'role' => 'users',
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


        $cvPath = $request->file('cv')->store('cv/alumni', 'public');

        $alumni = Alumni::create([
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
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Berhasil membuat data alumni',
            'cv_url' => asset('storage/' . $cvPath),
            'data' => $alumni
        ], 200);
    }

    public function update(Request $request, $id)
{
    $alumni = Alumni::find($id);

    if (!$alumni) {
        return response()->json(['message' => 'Alumni tidak ditemukan'], 404);
    }

    $validator = Validator::make($request->all(), [
        'nik' => 'required|string',
        'nis' => 'required|string',
        'nisn' => 'required|string',
        'ortu' => 'required|string',
        'jenis_kelamin' => 'required|string',
        'jurusan' => 'required|string',
        'tahun_lulus' => 'required|string',
        'skill' => 'required|string',
        'experience' => 'required|string',
        'cv' => 'file|mimes:pdf|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $request->nik], 422);
    }




    $data = $request->only([
        'nik', 'nis', 'nisn', 'ortu', 'jenis_kelamin',
        'jurusan', 'tahun_lulus', 'skill', 'experience'
    ]);

    if ($request->hasFile('cv')) {
        $data['cv'] = $request->file('cv')->store('cv/alumni', 'public');
    }

    $alumni->update($data);

    return response()->json([
        'message' => 'Berhasil mengubah data alumni',
        'data' => $alumni
    ], 200);

}

    public function destroy($id)
    {
        $alumni = Alumni::find($id);
        $alumni->delete();
        return response()->json([
            'message' => 'Berhasil menghapus data alumni'
        ], 200);
    }
}
