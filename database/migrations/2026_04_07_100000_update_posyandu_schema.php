<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
        Schema::table('data_latihs', function (Blueprint $table) {
            $table->dropColumn(['usia', 'lingkar_lengan_atas', 'lingkar_kepala']);
            $table->float('z_score')->nullable();
        });
    }
};
