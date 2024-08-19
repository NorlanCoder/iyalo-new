<?php

namespace App\Console\Commands;

use App\Service\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PushNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:push-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       
        Log::info("Mobile notification Cron is working fine!");

        $mobile = new NotificationService();
        $mobile->sendNotificationAtVisitor();
    }
}
