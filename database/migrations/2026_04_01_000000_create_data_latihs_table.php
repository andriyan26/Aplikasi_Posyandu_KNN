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
        Schema::create('data_latihs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->float('z_score')->nullable();
            $table->enum('status_stunting', ['Rendah', 'Sedang', 'Tinggi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_latihs');
    }
};
