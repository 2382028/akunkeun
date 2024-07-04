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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('merek')->nullable();
            $table->string('no_polisi')->nullable();
            $table->string('no_mesin')->nullable();
            $table->string('no_stnk')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('legalitas')->nullable();
            $table->string('legalitas_5th')->nullable();
            $table->string('tipe')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
