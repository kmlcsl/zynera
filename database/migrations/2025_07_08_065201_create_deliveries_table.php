<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('courier_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['assigned', 'picked_up', 'in_transit', 'delivered', 'failed'])->default('assigned');
            $table->datetime('assigned_at')->nullable();
            $table->datetime('picked_up_at')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};
