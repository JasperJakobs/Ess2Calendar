<?php

namespace App\Http\Controllers;

use App\Jobs\updateCalendar;
use App\Models\Softbrick;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

}
