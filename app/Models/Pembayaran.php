<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'id_periksa',
        'bukti_pembayaran',
        'status_pembayaran',
        'tgl_pembayaran',
    ];

    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_periksa');
    }
}