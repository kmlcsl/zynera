<?php

namespace App\Console\Commands;

use App\Models\Otp;
use Illuminate\Console\Command;

class CleanupExpiredOtp extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cleanup:expired-otp';

    /**
     * The console command description.
     */
    protected $description = 'Delete expired OTP records from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Cleaning up expired OTP records...');

        $deletedCount = Otp::where('expires_at', '<', now())->delete();

        if ($deletedCount > 0) {
            $this->info("✅ Deleted {$deletedCount} expired OTP records.");
        } else {
            $this->info("✅ No expired OTP records found.");
        }

        // Show remaining OTP count
        $remainingCount = Otp::count();
        $this->line("📊 Remaining OTP records: {$remainingCount}");

        return 0;
    }
}
