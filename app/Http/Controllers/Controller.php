<?php

namespace App\Http\Controllers;

use App\Jobs\updateSoftbrickCalendar;
use App\Models\Rooster;
use App\Models\Softbrick;
use DateTime;
use DateTimeZone;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Enums\ParticipationStatus;
use Spatie\IcalendarGenerator\Enums\RecurrenceFrequency;
use Spatie\IcalendarGenerator\ValueObjects\RRule;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display the settings view.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $softbrick = Softbrick::where('user', Auth::id())->first();

        return view('dashboard', ['softbrick' => $softbrick]);
    }


    /**
     * Display the settings view.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $softbrick = Softbrick::where('user', Auth::id())->first();

        return view('settings', ['softbrick' => $softbrick]);
    }

    /**
     * Get the calendar
     */
    public function calendar($uuid) {
        $softbrick = Softbrick::where('uuid', $uuid)->first();
        if (!$softbrick) {
            $calendar = Calendar::create('[E2C] FOUT BIJ SYNCHRONISATIE!')
                ->name('[E2C] FOUT BIJ SYNCHRONISATIE!')
                ->description('Softbrick werkrooster via https://e2c.jasperjakobs.nl/');

            $calendar->event(\Spatie\IcalendarGenerator\Components\Event::create()
                ->name('FOUT BIJ SYNCHRONISATIE!')
                ->rrule(RRule::frequency(RecurrenceFrequency::daily()))
                ->transparent()
                ->address('Gegevens resetten: https://e2c.jasperjakobs.nl/')
                ->description('Gegevens resetten: https://e2c.jasperjakobs.nl/')
                ->startsAt(new DateTime(Carbon::now()->toDateString() . '09:00:00', new DateTimeZone('Europe/Amsterdam')))
                ->endsAt(new \DateTime(Carbon::now()->toDateString() . '18:00:00', new DateTimeZone('Europe/Amsterdam'))));

            return response($calendar->get())
                ->header('Content-Type', 'text/calendar; charset=utf-8');
        }

        $calendar = Calendar::create('Softbrick van ' . $softbrick->email)
            ->name('Softbrick van ' . $softbrick->email)
            ->description('Softbrick werkrooster via https://e2c.jasperjakobs.nl/');

        $entries = Rooster::where('user', $softbrick->user)->each(function ($entry) use ($calendar) {
            $event = $calendar->event(\Spatie\IcalendarGenerator\Components\Event::create()
                ->name('Werken')
                ->transparent()
                ->description('Beheerd door https://e2c.jasperjakobs.nl/')
                ->address($entry->location)
                ->attendee('jasperjakobs@e2c.jasperjakobs.nl', 'Jasper Jakobs', ParticipationStatus::accepted())
                ->attendee('kerlijnvogel@e2c.jasperjakobs.nl', 'Kerlijn Vogel', ParticipationStatus::declined())
                ->startsAt(new DateTime($entry->day . $entry->from, new DateTimeZone('Europe/Amsterdam')))
                ->endsAt(new \DateTime($entry->day . $entry->until, new DateTimeZone('Europe/Amsterdam'))));
        });

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar; charset=utf-8');
    }

}
