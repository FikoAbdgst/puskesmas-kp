<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id',
        'poli_id',
        'dokter_id',
        'tanggal_kunjungan',
        'nomor_antrian',
        'keluhan',
        'status'
    ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }
}
