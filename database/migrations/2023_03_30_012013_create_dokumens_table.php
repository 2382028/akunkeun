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
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('info_perjadinlangsung_id');
            $table->string('surat_undangan')->nullable();
            $table->string('surat_tugas')->nullable();
            $table->string('SPPD')->nullable();
            $table->string('lap_perjadin')->nullable(); // tidak terpakai
            $table->string('nama_pelaksana')->nullable();
            $table->string('tempat_pelaksanaan')->nullable();
            $table->longtext('hasil')->nullable();
            $table->string('lap_pengeluaran')->nullable();
            $table->string('status_persetujuan');
            $table->string('ket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
