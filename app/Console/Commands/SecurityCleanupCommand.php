<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SecurityMonitoringService;

class SecurityCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:cleanup {--days=30 : Number of days of logs to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old security logs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');
        
        $this->info("Cleaning security logs older than {$days} days...");
        
        SecurityMonitoringService::cleanOldLogs($days);
        
        $this->info('Security logs cleaned successfully.');
        
        return 0;
    }
}