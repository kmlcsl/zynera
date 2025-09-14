<?php

// BUAT FILE MIGRATION BARU: database/migrations/xxxx_xx_xx_add_assigned_by_to_deliveries_table.php

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
        Schema::table('deliveries', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_by')->nullable()->after('courier_id');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');

            // Index for better performance
            $table->index('assigned_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->dropIndex(['assigned_by']);
            $table->dropColumn('assigned_by');
        });
    }
};
