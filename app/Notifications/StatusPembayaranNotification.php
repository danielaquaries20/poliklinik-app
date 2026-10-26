<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusPembayaranNotification extends Notification
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
        return [
            'title' => 'Pembayaran Diverifikasi',
            'message' => 'Pembayaran berobat Anda sebesar Rp ' . number_format($this->pembayaran->periksa->biaya_periksa, 0, ',', '.') . ' telah diverifikasi LUNAS.',
            'url' => route('pasien.pembayaran.index'),
        ];
    }
}