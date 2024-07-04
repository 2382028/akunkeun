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
        Schema::create('ref_rkakl_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ref_rkakl_program_id");
            $table->string("kode_kegiatan");
            $table->string("nama_kegiatan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_rkakl_kegiatans');
    }
};
