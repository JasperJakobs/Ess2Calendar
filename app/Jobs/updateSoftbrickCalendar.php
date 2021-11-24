<?php

namespace App\Jobs;

use App\Models\Softbrick;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class updateSoftbrickCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $softbrick;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Softbrick $softbrick)
    {
        $this->softbrick=$softbrick;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->softbrick->token == null) return;

        $response = Http::acceptJson()->withHeaders([
            'content-type' => 'application/json'
        ])->post('https://bcc.softbrick.com:3000/weekrooster?version=4', [
            'badnum' => $this->softbrick->badnum,
            'user' => $this->softbrick->email,
            'token' => $this->softbrick->token,
            'datumvanaf' =>now()->subWeeks(5)->startOfWeek()->format('Ymd'),
            'datumtm' => now()->addWeeks(5)->endOfWeek()->format('Ymd'),
            'language' => 'nl',
        ])->throw()->json();

        if ($response["success"] == "false") {
            $this->softbrick->update([
                'password' => null,
            ]);
//            TODO: Mail voor error.
        } else if ($response["success"] == "true") {

        }
    }
}
