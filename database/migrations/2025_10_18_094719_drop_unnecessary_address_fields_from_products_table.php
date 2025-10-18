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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'kecamatan_asal',
                'desa_asal', 
                'kode_pos_asal',
                'latitude_asal',
                'longitude_asal'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('kecamatan_asal')->nullable()->after('alamat_asal');
            $table->string('desa_asal')->nullable()->after('kecamatan_asal');
            $table->string('kode_pos_asal')->nullable()->after('desa_asal');
            $table->decimal('latitude_asal', 10, 8)->nullable()->after('kode_pos_asal');
            $table->decimal('longitude_asal', 11, 8)->nullable()->after('latitude_asal');
        });
    }
};