<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idJadwal;
    public $noAntrianSekarang;
    public $namaPoli;

    public function __construct($idJadwal, $noAntrianSekarang, $namaPoli = '')
    {
        $this->idJadwal = $idJadwal;
        $this->noAntrianSekarang = $noAntrianSekarang;
        $this->namaPoli = $namaPoli;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian-channel')
        ];
    }

    public function broadcastAs(): string
    {
        return 'antrian.updated';
    }
}
