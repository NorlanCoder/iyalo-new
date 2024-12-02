<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Visit;
use Carbon\Carbon;

class ValidateVisite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:validate-visite';

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
        Log::info("Cron Validate Visite");
        
        $dateLimite = Carbon::now()->subHours(48);
        User::where('date_visite', '<=', $dateLimite)->update(['visited' => true, 'confirm_owner'=> true, 'confirm_client'=> true]);
    }
}
