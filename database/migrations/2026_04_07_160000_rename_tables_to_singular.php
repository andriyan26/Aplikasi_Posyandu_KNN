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
        Schema::rename('balitas', 'balita');
        Schema::rename('pemeriksaans', 'pemeriksaan');
        Schema::rename('data_latihs', 'data_latih');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('balita', 'balitas');
        Schema::rename('pemeriksaan', 'pemeriksaans');
        Schema::rename('data_latih', 'data_latihs');
    }
};
