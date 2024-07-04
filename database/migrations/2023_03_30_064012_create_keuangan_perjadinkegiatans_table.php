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
        Schema::create('keuangan_perjadinkegiatans', function (Blueprint $table) {
            $table->id();
            $table->integer('harga')->nullable();
            $table->integer('persen_pajak')->nullable();
            $table->integer('pph22')->nullable();
            $table->integer('pph23')->nullable();
            $table->integer('ppn')->nullable();
            $table->integer('jumlah_harga')->nullable();
            $table->date('tgl_bayar')->nullable();
            $table->foreignId('data_perjadinkegiatan')->nullable();
            $table->foreignId('perangkat_acara')->nullable();
            $table->foreignId('operasional')->nullable();
            $table->foreignId('ref_sbm')->nullable();
            $table->foreignId('akun_x_rkakl')->nullable();
            $table->date('tgl_kwitansi')->nullable();
            $table->string('no_kwitansi')->nullable();
            $table->string('spby')->nullable();
            $table->string('drpp')->nullable();
            $table->string('jurnal')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_perjadinkegiatans');
    }
};
