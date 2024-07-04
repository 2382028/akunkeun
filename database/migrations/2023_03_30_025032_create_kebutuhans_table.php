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
        Schema::create('kebutuhans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jumlah_frekuensi');
            $table->integer('satuan')->nullable();
            $table->string('detail_satuan')->nullable();
            $table->string('tipe_pendanaan');
            $table->string('ket');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhans');
    }
};
