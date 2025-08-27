<?php

namespace App\Http\Controllers\BK_ROLE;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;

class CountController extends Controller
{
    public function CountStatus(){
        $countBekerja = Alumni::where('status', 'Bekerja')->count();
        $countMelanjutkan = Alumni::where('status', 'Melanjutkan')->count();
        $countWirausaha = Alumni::where('status', 'Wirausaha')->count();
        $countLainnya = Alumni::where('status', 'Lainnya')->count();
        $countBelum = Alumni::where('status', 'Belum Bekerja')->count();

        return response()->json([
            'bekerja' => $countBekerja,
            'melanjutkan' => $countMelanjutkan,
            'wirausaha' => $countWirausaha,
            'lainnya' => $countLainnya,
            'belumbekerja' => $countBelum
        ], 200);
    }
}
