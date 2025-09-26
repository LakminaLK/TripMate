<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LoginAttemptService;

class CleanLoginAttemptsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'login-attempts:cleanup {--days=30 : Number of days of records to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old failed login attempts records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Cleaning up login attempts older than {$days} days...");
        
        $loginAttemptService = new LoginAttemptService();
        $loginAttemptService->cleanupOldAttempts($days);
        
        $this->info('Login attempts cleanup completed successfully.');
        
        return 0;
    }
}
