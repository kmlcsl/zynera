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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('village_id')->nullable()->after('district');
            $table->foreign('village_id')->references('id')->on('regions')->onDelete('set null');
            $table->index('village_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('village_id')->nullable()->after('user_id');
            $table->foreign('village_id')->references('id')->on('regions')->onDelete('set null');
            $table->index('village_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropColumn('village_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropColumn('village_id');
        });
    }
};
