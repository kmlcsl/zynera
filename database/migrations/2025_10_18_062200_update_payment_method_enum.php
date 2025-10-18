<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Update payment method enum to include new values
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cod', 'transfer', 'qris', 'manual', 'midtrans') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cod', 'transfer', 'qris') NOT NULL");
    }
};