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
        Schema::create('peminjaman_kendaraan_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('info_perjadinlangsung')->nullable();
            $table->foreignId('kendaraan')->nullable();
            $table->foreignId('mobilitas_perjadinkegiatan')->nullable();
            $table->foreignId('pegawai_id')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_kendaraan_dinas');
    }
};
