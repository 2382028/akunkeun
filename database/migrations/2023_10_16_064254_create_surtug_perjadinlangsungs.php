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
        Schema::create('surtug_perjadinlangsungs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perjadinlangsung');
            $table->foreign('id_perjadinlangsung')->references('id')->on('info_perjadinlangsungs'); // Menambahkan foreign key constraint
            $table->string("paragraf_1");
            $table->string("paragraf_2");
            $table->string("paragraf_3");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surtug_perjadinlangsungs', function (Blueprint $table) {
            $table->dropForeign(['id_perjadinlangsung']); // Menghapus foreign key constraint
        });

        Schema::dropIfExists('surtug_perjadinlangsungs');
    }
};
