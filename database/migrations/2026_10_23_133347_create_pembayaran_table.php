<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_periksa')->constrained('periksa')->onDelete('cascade');
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['pending', 'lunas'])->default('pending');
            $table->timestamp('tgl_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (menghapus tabel pembayaran).
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
