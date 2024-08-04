<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle(Request $request) {

        Session::put('googleToken', $request->googleToken);

        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback() {

        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();

        if(!$user) {
            $user = User::create(['name' => $googleUser->name, 'email' => $googleUser->email, 'password' => Hash::make(rand(100000,999999))]);

            Mail::to($googleUser->email)->send(new WelcomeMail($googleUser->name));
        }

        Auth::login($user);

        $token = $user->createToken('token');

        return redirect(config('app.front_url').'/app?googleToken='.Session::get('googleToken').'&token=' . $token->plainTextToken);
    }
}
