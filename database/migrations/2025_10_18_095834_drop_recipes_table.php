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
        Schema::dropIfExists('recipes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('ingredients');
            $table->json('instructions');
            $table->string('image')->nullable();
            $table->integer('prep_time'); // dalam menit
            $table->integer('cook_time'); // dalam menit
            $table->integer('servings');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }
};