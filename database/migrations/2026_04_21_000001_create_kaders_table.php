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
        Schema::create('kader', function (Blueprint $table) {
            $table->id('id_kader');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->enum('status_aktif', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->string('barcode_ttd')->nullable();
            $table->timestamps();
        });

        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kader')->nullable()->after('kode_balita');
            $table->foreign('id_kader')->references('id_kader')->on('kader')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->dropForeign(['id_kader']);
            $table->dropColumn('id_kader');
        });
        
        Schema::dropIfExists('kader');
    }
};
