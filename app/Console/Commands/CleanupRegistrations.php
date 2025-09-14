<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Otp;
use App\Models\User;

class CleanupRegistrations extends Command
{
    protected $signature = 'registrations:cleanup';
    protected $description = 'Clean up expired OTP and unverified users';

    public function handle()
    {
        // Hapus OTP yang expired
        $expiredOtps = Otp::where('expires_at', '<=', now())->delete();

        // Hapus user yang tidak terverifikasi dan dibuat >24 jam yang lalu
        $unverifiedUsers = User::where('is_verified', false)
            ->where('created_at', '<=', now()->subDay())
            ->delete();

        $this->info("Cleaned up {$expiredOtps} expired OTPs and {$unverifiedUsers} unverified users");

        return 0;
    }
}
