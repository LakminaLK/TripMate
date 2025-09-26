<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HotelNotification;
use Carbon\Carbon;

class CleanupReadNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--hours=24 : Hours after which read notifications should be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete read notifications older than specified hours (default: 24 hours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        
        $this->info("Cleaning up read notifications older than {$hours} hours...");
        
        // Use the model's cleanup method
        $result = HotelNotification::cleanupOld($hours);
        
        $this->info("Deleted {$result['deleted_read']} read notifications.");
        
        if ($result['deleted_old_unread'] > 0) {
            $this->info("Also deleted {$result['deleted_old_unread']} very old unread notifications (30+ days).");
        }
        
        $this->info("Total notifications deleted: {$result['total_deleted']}");
        $this->info("Notification cleanup completed successfully!");
        
        return 0;
    }
}
