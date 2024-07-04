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
        Schema::create('mobilitas_perjadinkegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('mobilitas');
            $table->string('tujuan_penggunaan');
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai');
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->string('alamat');
            $table->string('status');
            $table->foreignId('data_perjadinkegiatan');
            $table->foreignId('versi_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobilitas_perjadinkegiatans');
    }
};
