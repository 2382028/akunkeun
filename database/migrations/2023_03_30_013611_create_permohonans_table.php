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
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_permohonan')->nullable();
            $table->string('no_BMN')->nullable();
            $table->dateTime('tgl_permohonan');
            $table->dateTime('tgl_pemeriksaan')->nullable();
            $table->dateTime('tgl_pengerjaan')->nullable();
            $table->dateTime('tgl_selesai')->nullable();
            $table->string('alasan_ket')->nullable();
            $table->string('status')->nullable();
            $table->string('MAK')->nullable();
            $table->string('dokumen_nota')->nullable();
            $table->foreignId('admin')->nullable();
            $table->foreignId('data_penanggungjawab_id')->nullable();
            $table->foreignId('akun_x_rkakl_id')->nullable();
            $table->foreignId('ref_sbm_id')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->bigInteger('pph')->nullable();
            $table->bigInteger('pph22')->nullable();
            $table->bigInteger('pph23')->nullable();
            $table->bigInteger('ppn')->nullable();
            $table->dateTime('tgl_bayar')->nullable();
            $table->bigInteger('total')->nullable();
            $table->foreignId('ruangan_id')->nullable();
            $table->foreignId('kendaraan_id')->nullable();
            $table->foreignId('asset_id')->nullable();
            $table->foreignId('data_penyedia_id')->nullable();
            $table->string('is_acceptBMN')->nullable();
            $table->string('is_acceptKeu')->nullable();
            $table->string('is_acceptBend')->nullable();
            $table->foreignId('admin_BMN')->nullable();
            $table->foreignId('admin_Keu')->nullable();
            $table->foreignId('admin_Bend')->nullable();
            $table->foreignId('service_id')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->foreignId('versi_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonans');
    }
};
