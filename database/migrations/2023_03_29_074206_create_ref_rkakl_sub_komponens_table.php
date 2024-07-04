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
        Schema::create('ref_rkakl_sub_komponens', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ref_rkakl_komponen_id");
            $table->string("kode_sub_kegiatan");
            $table->string("nama_sub_kegiatan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_rkakl_sub_komponens');
    }
};
