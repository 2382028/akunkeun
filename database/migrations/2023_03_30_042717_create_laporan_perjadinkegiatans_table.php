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
        Schema::create('laporan_perjadinkegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('file');
            $table->string('status');
            $table->foreignId('data_perjadin_kegiatan');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_perjadinkegiatans');
    }
};
