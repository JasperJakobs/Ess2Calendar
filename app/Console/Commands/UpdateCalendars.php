<?php

namespace App\Console\Commands;

use App\Http\Controllers\SoftbrickController;
use App\Jobs\updateCalendar;
use App\Jobs\updateSoftbrickCalendar;
use App\Models\Softbrick;
use Illuminate\Console\Command;

class UpdateCalendars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendars:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the calendars of all users.';

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
        $softbricks = $this->withProgressBar(Softbrick::all(), function ($softbrick) {
            $this->line(" Updating " . $softbrick->email);
            updateSoftbrickCalendar::dispatch($softbrick);
        });

        return Command::SUCCESS;
    }
}
