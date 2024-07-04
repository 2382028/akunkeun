<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToDataPerjadinlangsungsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_perjadinlangsungs', function (Blueprint $table) {
            $table->date('tgl_keberangkatan')->nullable();
            $table->date('tgl_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_perjadinlangsungs', function (Blueprint $table) {
            $table->dropColumn('tgl_keberangkatan');
            $table->dropColumn('tgl_selesai');
        });
    }
}
