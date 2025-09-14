<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('village')->nullable();
            $table->string('district')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('user_type', ['konsumen', 'produsen', 'admin', 'kurir'])->default('konsumen');
            $table->boolean('is_verified')->default(false);
            // $table->timestamp('email_verified_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'village', 'district', 'avatar', 'user_type', 'is_verified']);
        });
    }
};
