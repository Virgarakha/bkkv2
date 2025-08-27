<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    use HasFactory;
    protected $table = 'lamaran';
    protected $guarded = [];

    public $timestamps = false;

    public function alumni() {
        return $this->belongsTo(Alumni::class, 'id_alumni');
    }

    public function perusahaan() {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }


}
