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
            // Add Google OAuth fields if not exists
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->index()->after('is_verified');
            }

            if (!Schema::hasColumn('users', 'login_provider')) {
                $table->string('login_provider')->nullable()->after('google_id');
            }

            // Add notification preferences JSON field
            if (!Schema::hasColumn('users', 'notification_preferences')) {
                $table->json('notification_preferences')->nullable()->after('login_provider');
            }

            // Add last login tracking
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('notification_preferences');
            }

            // Add profile completion status
            if (!Schema::hasColumn('users', 'profile_completed')) {
                $table->boolean('profile_completed')->default(false)->after('last_login_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToCheck = [
                'google_id',
                'login_provider',
                'notification_preferences',
                'last_login_at',
                'profile_completed'
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
