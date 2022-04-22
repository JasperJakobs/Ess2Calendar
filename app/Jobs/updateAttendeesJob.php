<?php

namespace App\Jobs;

use App\Http\Controllers\SoftbrickController;
use App\Models\Attendee;
use App\Models\Softbrick;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class updateAttendeesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $day;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dayFromNow)
    {
        $this->day = now()->addDays($dayFromNow)->format('Ymd');;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $credentials = Softbrick::whereNotNull('token')->first();

        if ($credentials == null) return;

        try {
            $response = Http::acceptJson()->withHeaders([
                'content-type' => 'application/json'
            ])->post('https://bcc.softbrick.com:3000/mijnteams?version=4', [
                'badnum'    => Crypt::decryptString($credentials->badnum),
                'token'     => $credentials->token,
                'language'  => 'nl',
                'datum'     => $this->day,
            ])->throw()->json();

            if ($response == null) return;

            $team = $response['team'][0]['collegas'];
            foreach ($team as $member) {
                $status = 'unknown';
                if ($member['plan'][0]['tekst'] == ' - Afwezig - ') {
                    $status = 'absent';
                } else $status = 'present';

                Attendee::create([
                    'day' => $this->day,
                    'firstname'   => rtrim($member['init']),
                    'lastname'  => rtrim($member['naam']),
                    'status' => $status
                ]);
            }
        } catch (\Exception $exception) {
            log::error($exception);
        }
    }
}
