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
             $softbrick = Softbrick::create([
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

        $response = Http::acceptJson()->withHeaders([
            'content-type' => 'application/json'
        ])->post('https://bcc.softbrick.com:3000/logon?version=4', [
            'user' => $softbrick->email,
            'password' => $softbrick->password,
            'device' => '',
            'language' => 'nl',
        ])->throw()->json();

//        dd($response['data']['0']['token']);

        if ($response["success"] == "false") {
            return redirect(RouteServiceProvider::SETTINGS)->with('softbrick:error', $response['message']);
        }

//        dd($response);
        return redirect(RouteServiceProvider::SETTINGS)->with('softbrick:success', '');
    }

}
