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
        Schema::create('data_penyedias', function (Blueprint $table) {
            $table->id();
            $table->string('NPWP')->nullable();
            $table->string('nama_CV');
            $table->string('penanggung_jawab');
            $table->string('jabatan');
            $table->string('no_telp');
            $table->string('alamat');
            $table->string('kategori');
            $table->string('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_penyedias');
    }
};
