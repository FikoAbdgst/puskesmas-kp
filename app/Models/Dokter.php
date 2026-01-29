<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi
    protected $fillable = ['nama_dokter', 'poli_id', 'jadwal_praktek'];

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
}
