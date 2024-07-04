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
        Schema::create('data_perjadinlangsungs', function (Blueprint $table) {
            $table->id();
            $table->string('status_pegawai');
            $table->foreignId('info_perjadinlangsung');
            $table->foreignId('pegawai_id')->nullable();
            $table->foreignId('non_pegawai_id')->nullable();
            $table->string('status_persetujuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_perjadinlangsungs');
    }
};
