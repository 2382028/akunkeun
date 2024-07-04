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
        Schema::create('data_penanggungjawabs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl_mulai_digunakan');
            $table->dateTime('tgl_selesai')->nullable();
            $table->foreignId('asset_id');
            $table->foreignId('pegawai_id');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_penanggungjawabs');
    }
};
