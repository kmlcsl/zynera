<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MonitorAuthorizationCommand extends Command
{
    protected $signature = 'monitor:authorization {--lines=50}';
    protected $description = 'Monitor authorization logs in real-time';

    public function handle()
    {
        $lines = $this->option('lines');
        $logPath = storage_path('logs/laravel.log');

        $this->info("🔍 MONITORING AUTHORIZATION LOGS");
        $this->line("Log file: {$logPath}");
        $this->line("Showing last {$lines} lines...");
        $this->line(str_repeat('=', 60));

        if (!File::exists($logPath)) {
            $this->error("Log file not found!");
            return;
        }

        // Show recent authorization-related logs
        $this->showRecentLogs($logPath, $lines);

        $this->line(str_repeat('=', 60));
        $this->comment("💡 Tips:");
        $this->comment("- Access orders/16/success to see logs");
        $this->comment("- Check 'Order Access Attempt' entries");
        $this->comment("- Look for 'is_owner' field in logs");
    }

    private function showRecentLogs($logPath, $lines)
    {
        $command = "tail -n {$lines} " . escapeshellarg($logPath);
        $output = shell_exec($command);

        if ($output) {
            $logLines = explode("\n", trim($output));
            
            foreach ($logLines as $line) {
                if (empty($line)) continue;
                
                // Highlight authorization-related logs
                if (str_contains($line, 'Order Access Attempt') || 
                    str_contains($line, 'Unauthorized') || 
                    str_contains($line, 'authorization') ||
                    str_contains($line, 'OrderController accessed')) {
                    
                    $this->line("<fg=yellow>{$line}</>");
                } elseif (str_contains($line, 'ERROR') || str_contains($line, 'error')) {
                    $this->line("<fg=red>{$line}</>");
                } else {
                    $this->line("<fg=gray>{$line}</>");
                }
            }
        } else {
            $this->warn("Could not read log file");
        }
    }
}
