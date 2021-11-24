<?php

namespace App\Http\Controllers;

use App\Models\Softbrick;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

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
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function updateSoftbrick(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $softbrick = Softbrick::where('user', '=', Auth::id())->first();

        if (!Softbrick::where('user', '=', Auth::id())->exists()) {
             Softbrick::create([
                'user' => Auth::id(),
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ]);
        } else {
            $softbrick->update([
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ]);
        }

      return redirect(RouteServiceProvider::SETTINGS)->with('softbrick:success', '');
    }

}
