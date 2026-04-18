<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        // store role from query (?role=doctor / patient)
        session(['role' => request('role', 'patient'),
        'type' => request('type')
        ]);

        return Socialite::driver('google')->redirect();
    }

   public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        if (!$googleUser || !$googleUser->getEmail()) {
            abort(403, 'Google authentication failed.');
        }

        $role = session('role');
        $type = session('type'); // 👈 NEW

        $user = User::where('email', $googleUser->getEmail())->first();

        // ✅ LOGIN FLOW
        if ($type === 'login') {
            if (!$user) {
                return redirect('/login')->withErrors([
                    'email' => 'No account found for chosen email. Please register first.'
                ]);
            }

            Auth::login($user);

            $uuid = $user->ensureUuid();

            return redirect("/profile/{$uuid}");
        }

        // ✅ REGISTER FLOW (your existing logic untouched)
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(str()->random(32)),
                'email_verified_at' => now(),
                'role' => $role,
            ]);
        } else {
            if (empty($user->role)) {
                $user->role = $role;
                $user->save();
            }
        }

        Auth::login($user);

        session()->forget(['role', 'type']);

        $uuid = $user->ensureUuid();

        return redirect("/profile/{$uuid}");

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}




// facebook functions

public function redirectToFacebook()
{
    session([
        'role' => request('role'),
        'type' => request('type')
    ]);

    return Socialite::driver('facebook')->redirect();
}


public function handleFacebookCallback()
{
    try {
        $fbUser = Socialite::driver('facebook')->stateless()->user();

        if (!$fbUser || !$fbUser->getEmail()) {
            abort(403, 'Facebook authentication failed.');
        }

        $role = session('role');
        $type = session('type');

        $user = User::where('email', $fbUser->getEmail())->first();

        // ✅ LOGIN FLOW
        if ($type === 'login') {
            if (!$user) {
                return redirect('/login')->withErrors([
                    'email' => 'No account found for chosen email. Please register first.'
                ]);
            }

            Auth::login($user);

            $uuid = $user->ensureUuid();

            return redirect("/profile/{$uuid}");
        }

        // ✅ REGISTER FLOW
        if (!$user) {
            $user = User::create([
                'name' => $fbUser->getName(),
                'email' => $fbUser->getEmail(),
                'password' => bcrypt(str()->random(32)),
                'email_verified_at' => now(),
                'role' => $role,
            ]);
        } else {
            if (empty($user->role)) {
                $user->role = $role;
                $user->save();
            }
        }

        Auth::login($user);

        session()->forget(['role', 'type']);

        $uuid = $user->ensureUuid();

        return redirect("/profile/{$uuid}");

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}

}