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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('NIP_NIK')->unique();
            $table->string('password');
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin');
            $table->string('status');
            $table->string('golongan');
            $table->string('pangkat');
            $table->string('no_telp');
            $table->string('email');
            $table->string('no_rekening');
            $table->boolean('is_aktif');
            $table->foreignId('jabatan_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
