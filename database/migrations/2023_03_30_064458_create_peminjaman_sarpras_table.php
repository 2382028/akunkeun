<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman_sarpras', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_asset')->nullable();
            $table->dateTime('tgl_peminjaman');
            $table->dateTime('tgl_pengembalian')->nullable();
            $table->foreignId('data_perjadinkegiatan')->nullable();
            $table->foreignId('pegawai_id')->nullable();
            $table->foreignId('asset');
            $table->string('status');
            $table->string('keterangan')->nullable();
            $table->foreignId('versi_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_sarpras');
    }
};
