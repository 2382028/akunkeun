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
        Schema::create('info_perjadinlangsungs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai');
            $table->dateTime('tgl_keberangkatan');
            $table->dateTime('tgl_kepulangan')->nullable()->date_time_set;
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->string('alamat');
            $table->string('kode_surat_tugas')->nullable();
            $table->string('status_pengajuan')->nullable();
            $table->string('is_acceptBMN')->nullable();
            $table->string('is_acceptKeu')->nullable();
            $table->string('is_acceptBend')->nullable();
            $table->string('jenis_kegiatan')->nullable();
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
        Schema::dropIfExists('info_perjadinlangsungs');
    }
};
