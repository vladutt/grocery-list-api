<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\EditProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function editProfile(EditProfileRequest $request) {

        $user = Auth::user();

        $avatar = $request->file('avatar');
        $avatarName = $user->email.'.'.$avatar->getClientOriginalExtension();
        $avatar->storeAs('public/avatars', $avatarName);

        $user->avatar = $avatarName;
        $user->name = $request->input('name');
        $user->save();

        return $this->success(['user' => $user]);
    }

    public function changePassword(ChangePasswordRequest $request) {
        $user = Auth::user();

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['errors' => ['password' => 'Current password is incorrect.']], Response::HTTP_UNAUTHORIZED);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return $this->success(['message' => 'Password changed successfully.']);
    }

    public function changeEmail(ChangeEmailRequest $request) {
        $user = Auth::user();

        $user->email = $request->input('email');
        $user->save();

        return $this->success(['message' => 'Email changed successfully.']);
    }

}
