<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Sitedown;

class CheckSiteHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-site-health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check the health of the site and database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        try {
            //code...
            DB::connection()->getPdo();
            $this->info('Database is up');
        } catch (\Throwable $th) {
            //throw $th;
            $this->error('Database connection failed!');
            Log::error('DB connection failed: ' . $th->getMessage());
            Notification::route('mail', 'chukwuemekavictor693@gmail.com')
                ->notify(new Sitedown('Database connection failed!'));
        }

        try {
            //code...
            $response = Http::get(config('app.url'));
            if ($response->status() != 200) {
                $this->error('Site is down! HTTP status: ' . $response->status());
                Log::error('Site down! HTTP status: ' . $response->status());
                Notification::route('mail', 'chukwuemekavictor693@gmail.com')
                    ->notify(new Sitedown('Site is down! HTTP status: ' . $response->status()));
            }else{
               
                Notification::route('mail', 'chukwuemekavictor693@gmail.com')
                    ->notify(new Sitedown('Site is up! HTTP status: ' . $response->status()));
                $this->info("your site is up");
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->error('Site check failed!');
            Log::error('HTTP check failed: ' . $th->getMessage());
            Notification::route('mail', 'chukwuemekavictor693@gmail.com')
                ->notify(new Sitedown('Site check failed: ' . $th->getMessage()));
        }
    }
}
