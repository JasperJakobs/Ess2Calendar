<?php

namespace App\Jobs;

use App\Http\Controllers\SoftbrickController;
use App\Models\Rooster;
use App\Models\Softbrick;
use DateTime;
use DateTimeZone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        try {
            $response = Http::acceptJson()->withHeaders([
                'content-type' => 'application/json'
            ])->post('https://bcc.softbrick.com:3000/weekrooster?version=4', [
                'badnum' => Crypt::decryptString($this->softbrick->badnum),
                'user' => $this->softbrick->email,
                'token' => $this->softbrick->token,
                'datumvanaf' => now()->subWeeks(5)->startOfWeek()->format('Ymd'),
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
                    $existingEntry = null;
                    if ($item['naam'] == 'plan') {

                        $address = "0036: Arnhem";

                        if ($item['locatie'] != null) {
                            $locations = explode(',', $item['locatie']);
                            $address = $locations[0];
                        }

                        if ($address == "Vakantie") {
                            continue;
                        }

                        $day = new DateTime($item['datum'] . $item['van'], new DateTimeZone('Europe/Amsterdam'));
                        $from = new DateTime($item['datum'] . $item['van'], new DateTimeZone('Europe/Amsterdam'));
                        $until = new DateTime($item['datum'] . $item['tot'], new DateTimeZone('Europe/Amsterdam'));

                        $existingEntry = Rooster::where('user', $this->softbrick->user)->where('day', $day->format('Y-m-d'))->first();

                        if (!$existingEntry) {
                            Rooster::create([
                                'user' => $this->softbrick->user,
                                'day' => $day,
                                'from' => $from,
                                'until' => $until,
                                'location' => $address
                            ]);
                        } else {
                            if (new DateTime($existingEntry->day . $existingEntry->from, new DateTimeZone('Europe/Amsterdam')) > $from) {
                                Rooster::where('user', $this->softbrick->user)->where('day', $day->format('Y-m-d'))->update([
                                    'from' => $from,
                                ]);
                            }

                            if (new DateTime($existingEntry->day . $existingEntry->until, new DateTimeZone('Europe/Amsterdam')) < $until) {
                                Rooster::where('user', $this->softbrick->user)->where('day', $day->format('Y-m-d'))->update([
                                    'until' => $until,
                                ]);
                            }
                        }

                    }
                }

                Storage::put('public/calendar/feed/' . $this->softbrick->uuid . '.ics', $calendar->get());
            }
        } catch (\Exception $exception) {
            Log::warning("Token incorrect, retrying password.");
            $sbcontroller = new SoftbrickController;
            $sbcontroller->authSoftbrick($this->softbrick);
        }
    }
}
