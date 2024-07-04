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
        Schema::create('data_perjadinkegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('id_iku')->nullable();
            $table->string('uraian')->nullable();
            $table->string('program_kerja')->nullable();
            $table->string('nama_kegiatan');
            $table->string('jenis_kegiatan')->nullable();
            $table->string('jumlah_peserta')->nullable();
            $table->dateTime('tgl_mulai')->nullable();
            $table->dateTime('tgl_selesai')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kab_kota')->nullable();
            $table->string('alamat')->nullable();
            $table->string('status');
            $table->foreignId('program_kerja_id')->nullable();
            $table->string('is_acceptBMN')->nullable();
            $table->string('is_acceptKeu')->nullable();
            $table->string('is_acceptBend')->nullable();
            $table->foreignId('admin_BMN')->nullable();
            $table->foreignId('admin_Keu')->nullable();
            $table->foreignId('admin_Bend')->nullable();
            $table->foreignId('versi_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_perjadinkegiatans');
    }
};
