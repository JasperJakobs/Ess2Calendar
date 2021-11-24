<?php

namespace App\Jobs;

use App\Http\Controllers\SoftbrickController;
use App\Models\Softbrick;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\IcalendarGenerator\Components\Calendar;

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
            $sbcontroller = new SoftbrickController;
            $sbcontroller->authSoftbrick($this->softbrick);
        } else if ($response["success"] == "true") {
            $calendar = Calendar::create('Softbrick - ' . $this->softbrick->email)->description('Softbrick werkrooster via https://e2c.jasperjakobs.nl/');
            $data = $response['data'];
            foreach ($data as $item) {
                if ($item['naam'] == 'plan') {
                    $calendar->event(\Spatie\IcalendarGenerator\Components\Event::create()
                        ->name('Ingeroosterd @ BCC')
                        ->startsAt(new DateTime($item['datum'] . $item['van']))
                        ->endsAt(new \DateTime($item['datum'] . $item['tot'])));
                }
            }

            Storage::put('public/calendar/feed/' . $this->softbrick->uuid . '.ics', $calendar->get());
        }
    }
}
