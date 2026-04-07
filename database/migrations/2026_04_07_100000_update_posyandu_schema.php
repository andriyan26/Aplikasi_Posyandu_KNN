<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update balitas table
        Schema::table('balitas', function (Blueprint $table) {
            $table->string('kode')->unique()->after('id')->nullable();
            $table->string('nik')->after('kode')->nullable();
            $table->decimal('usia', 5, 1)->after('nama_orang_tua')->nullable();
        });

        // Update pemeriksaans table
        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->decimal('usia_saat_periksa', 5, 1)->after('balita_id')->nullable();
            // We ensure we can drop the column safely if it exists (for sqlite support typically need separate drop)
            if (Schema::hasColumn('pemeriksaans', 'z_score')) {
                $table->dropColumn('z_score');
            }
        });

        // Update data_latihs table
        Schema::table('data_latihs', function (Blueprint $table) {
            $table->decimal('usia', 5, 1)->after('tinggi_badan')->nullable();
            $table->float('lingkar_lengan_atas')->after('usia')->nullable();
            $table->float('lingkar_kepala')->after('lingkar_lengan_atas')->nullable();
            
            if (Schema::hasColumn('data_latihs', 'z_score')) {
                $table->dropColumn('z_score');
            }
        });
    }

    public function down(): void
    {
        Schema::table('balitas', function (Blueprint $table) {
            $table->dropColumn(['kode', 'nik', 'usia']);
        });

        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->dropColumn('usia_saat_periksa');
            $table->float('z_score')->nullable();
        });

        Schema::table('data_latihs', function (Blueprint $table) {
            $table->dropColumn(['usia', 'lingkar_lengan_atas', 'lingkar_kepala']);
            $table->float('z_score')->nullable();
        });
    }
};
