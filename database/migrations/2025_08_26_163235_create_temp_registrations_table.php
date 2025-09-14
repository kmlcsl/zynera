<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->json('registration_data');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('email');
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_registrations');
    }
};
