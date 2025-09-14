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
        Schema::table('orders', function (Blueprint $table) {
            // Hanya tambahkan service_fee jika belum ada
            if (!Schema::hasColumn('orders', 'service_fee')) {
                $table->decimal('service_fee', 10, 2)->after('shipping_cost')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'service_fee')) {
                $table->dropColumn('service_fee');
            }
        });
    }
};
