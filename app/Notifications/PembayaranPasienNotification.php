<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranPasienNotification extends Notification
{
    use Queueable;

    protected $pembayaran;

    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $pasienNama = $this->pembayaran->periksa->daftarPoli->pasien->nama ?? 'Pasien';

        return [
            'title' => 'Bukti Pembayaran Diunggah',
            'message' => 'Pasien ' . $pasienNama . ' telah mengunggah bukti pembayaran berobat.',
            'url' => route('periksa-pasien.index'),
        ];
    }
}