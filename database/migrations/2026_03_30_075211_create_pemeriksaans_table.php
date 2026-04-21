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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_balita');
            $table->foreign('kode_balita')->references('kode_balita')->on('balitas')->onDelete('cascade');
            $table->decimal('usia_saat_periksa', 5, 1)->nullable();
            $table->date('tanggal_pemeriksaan');
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->float('lingkar_lengan_atas')->nullable();
            $table->float('lingkar_kepala')->nullable();
            $table->enum('status_stunting', ['Rendah', 'Sedang', 'Tinggi'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
