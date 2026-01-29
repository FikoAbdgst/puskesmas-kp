<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi
    protected $fillable = ['nama_poli', 'deskripsi'];

    // Relasi (Opsional, untuk mempermudah kedepannya)
    public function dokters()
    {
        return $this->hasMany(Dokter::class);
    }
}
