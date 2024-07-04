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
        Schema::create('non_pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('NIP_NIK')->unique();
            $table->string('nama_lengkap');
            $table->string('golongan');
            $table->string('pangkat');
            $table->string('status')->nullable();
            $table->string('alamat')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('no_telp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_pegawais');
    }
};
