<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'profile.png'
        ]);

        event(new Registered($user));

        Auth::login($user);

        Mail::to($request->email)->send(new WelcomeMail($request->name));
        Notification::singleNotification('notifications', 'Welcome to Grocer Mate', "Now it's time for you to organize your shopping list and say \"stop\" to impulsive items in your cart.");
        Notification::singleNotification('paid', 'Pro account', "Because you are one of our first users we will give you a PRO account for 3 months.");

        return response()->json([
            'id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $user->avatarPath,
            'status' => 'success',
            'message' => 'Account created successfully.',
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }
}
