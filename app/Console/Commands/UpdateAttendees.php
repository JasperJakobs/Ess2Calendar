<?php

namespace App\Console\Commands;

use App\Models\Attendee;
use Illuminate\Console\Command;

class UpdateAttendees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendees:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the employees of the day.';

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
        Attendee::truncate();

        $bar = $this->output->createProgressBar(65);
        $bar->start();

        for ($i = -30; $i < 35; $i++) {
            $bar->advance();
            updateAttendees::dispatch($i);
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
