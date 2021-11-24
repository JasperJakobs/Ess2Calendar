<?php

namespace App\Jobs;

use App\Http\Controllers\SoftbrickController;
use App\Models\Softbrick;
use DateTime;
use DateTimeZone;
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

            $calendar = Calendar::create('Softbrick van ' . $this->softbrick->email)
                ->name('Softbrick van ' . $this->softbrick->email)
                ->description('Softbrick werkrooster via https://e2c.jasperjakobs.nl/');
            $data = $response['data'];

            foreach ($data as $item) {
                if ($item['naam'] == 'plan') {

                    $address = "0036: BCC Arnhem";

                    if ($item['locatie'] != null) {
                        $locations = explode(',', $item['locatie']);
                        $address = $locations[0];
                    }

                    $calendar->event(\Spatie\IcalendarGenerator\Components\Event::create()
                        ->name('Werken')
                        ->description('Beheerd door e2c.jasperjakobs.nl')
                        ->address($address)
                        ->startsAt(new DateTime($item['datum'] . $item['van'], new DateTimeZone('Europe/Amsterdam')))
                        ->endsAt(new \DateTime($item['datum'] . $item['tot'], new DateTimeZone('Europe/Amsterdam'))));
                }
            }

            Storage::put('public/calendar/feed/' . $this->softbrick->uuid . '.ics', $calendar->get());
        }
    }
}
