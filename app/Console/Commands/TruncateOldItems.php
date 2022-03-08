<?php

namespace App\Console\Commands;

use App\Models\Rooster;
use Illuminate\Console\Command;

class TruncateOldItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendars:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old calendar items from the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Rooster::where('day', '<', now()->subWeeks(5)->startOfWeek()->format('Ymd'))->each(function ($entry) {
           $entry->delete();
        });

        $this->info("Removed old calendar entries!");
        return Command::SUCCESS;
    }
}
