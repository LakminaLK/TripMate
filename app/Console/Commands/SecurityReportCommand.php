<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SecurityMonitoringService;

class SecurityReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:report {--days=7 : Number of days to include in the report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a security report for the specified number of days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');
        
        $this->info("Generating security report for the last {$days} days...");
        
        $report = SecurityMonitoringService::generateSecurityReport($days);
        
        if (isset($report['error'])) {
            $this->error($report['error']);
            return 1;
        }
        
        $this->info('Security Report');
        $this->info('==============');
        $this->info("Period: {$report['period']}");
        $this->info("Total Events: {$report['total_events']}");
        
        $this->newLine();
        $this->info('Events by Type:');
        foreach ($report['events_by_type'] as $type => $count) {
            $this->line("  {$type}: {$count}");
        }
        
        $this->newLine();
        $this->info('Top IP Addresses:');
        $topIps = array_slice($report['top_ips'], 0, 10, true);
        foreach ($topIps as $ip => $count) {
            $this->line("  {$ip}: {$count} events");
        }
        
        $this->newLine();
        $this->info('Recent Events:');
        foreach ($report['recent_events'] as $event) {
            $timestamp = $event['timestamp'] ?? 'unknown';
            $eventType = $event['event'] ?? 'unknown';
            $ip = $event['ip'] ?? 'unknown';
            $this->line("  [{$timestamp}] {$eventType} from {$ip}");
        }
        
        return 0;
    }
}