<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->decimal('duration', 8, 2)->nullable()->after('delivered_at')->comment('Delivery duration in hours');
            $table->datetime('pickup_time')->nullable()->after('duration')->comment('Actual pickup time');
            $table->datetime('delivery_time')->nullable()->after('pickup_time')->comment('Actual delivery time');
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['duration', 'pickup_time', 'delivery_time']);
        });
    }
};