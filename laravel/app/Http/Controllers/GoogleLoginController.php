<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Notification;
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
            Notification::singleNotification('notifications', 'Welcome to Grocer Mate', "Now it's time for you to organize your shopping list and say \"stop\" to impulsive items in your cart.");
            Notification::singleNotification('paid', 'Pro account', "Because you are one of our first users we will give you a PRO account for 3 months.");
        }

        Auth::login($user);

        $token = $user->createToken('token');

        return redirect(config('app.front_url').'/app?googleToken='.Session::get('googleToken').'&token=' . $token->plainTextToken);
    }
}
