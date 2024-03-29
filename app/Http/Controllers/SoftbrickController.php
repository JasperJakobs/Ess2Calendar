<?php

namespace App\Http\Controllers;

use App\Jobs\updateCalendar;
use App\Jobs\updateSoftbrickCalendar;
use App\Models\Softbrick;
use App\Notifications\softbrickError;
use App\Providers\RouteServiceProvider;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpParser\Node\Scalar\String_;

class SoftbrickController extends Controller
{
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
                'password' => Crypt::encryptString($request->get('password')),
                'uuid' => Str::uuid()
            ]);
        } else {
            $softbrick->update([
                'email' => $request->get('email'),
                'password' => Crypt::encryptString($request->get('password'))
            ]);
        }

        $authSuccess = $this->authSoftbrick($softbrick);

        $alert = explode(':', $authSuccess);

        switch ($alert[0]) {
            case 'success':
                updateSoftbrickCalendar::dispatchSync($softbrick);
                return redirect(RouteServiceProvider::HOME)->with('softbrick:' . $alert[0], $alert[1]);
            case 'error':
                return redirect(RouteServiceProvider::SETTINGS)->with('softbrick:' . $alert[0], $alert[1]);
        }
        return redirect(RouteServiceProvider::SETTINGS)->with('softbrick:error', 'Something went wrong');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     *
     */
    public function authSoftbrick(Softbrick $softbrick, $notify = true) {
        if ($softbrick->password == null) return "error:Er is geen wachtwoord aanwezig.";

        $response = Http::acceptJson()->withHeaders([
            'content-type' => 'application/json'
        ])->post('https://bcc.softbrick.com:3000/logon?version=4', [
            'user' => $softbrick->email,
            'password' => Crypt::decryptString($softbrick->password),
            'device' => '',
            'language' => 'nl',
        ])->throw()->json();

        if ($response["success"] == "false") {
            $softbrick->update([
                'password' => null
            ]);
            $user = $softbrick->parent;
            if ($notify) $user->notify(new softbrickError());

            return "error:" . $response['message'];
        } else if ($response["success"] == "true") {
            $token = $response['data']['0']['token'];
            $badnum = $response['data']['0']['badnum'];
            $softbrick->update([
                'token' => $token,
                'badnum' => Crypt::encryptString($badnum)
            ]);
        }

        return "success:Softbrick is succesvol gekoppeld!";
    }
}
