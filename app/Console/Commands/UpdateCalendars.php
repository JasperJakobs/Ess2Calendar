<?php

namespace App\Console\Commands;

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
        $accounts = Softbrick::all();
        $bar = $this->output->createProgressBar(count($accounts));
        $bar->setFormat(sprintf('%s <info>%%status%%</info>',
            $bar->getFormatDefinition('verbose')));

        $bar->setMessage(" ", 'status');
        $bar->start();

        foreach ($accounts as $account) {
            $bar->setMessage("Updating " . $account->email, 'status');
            $bar->advance();
            updateSoftbrickCalendar::dispatch($account);
        }

        $bar->setMessage("Finished", 'status');
        $bar->finish();

        return Command::SUCCESS;
    }
}
