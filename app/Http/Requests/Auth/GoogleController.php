<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect('/login')->withErrors(['Login com Google falhou. Tente novamente.']);
        }

        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
            Auth::login($user);
            return redirect('/');
        }

        Session::put('google_user', [
            'google_id' => $googleUser->getId(),
            'nome'      => $googleUser->user['given_name']  ?? explode(' ', $googleUser->getName())[0],
            'sobrenome' => $googleUser->user['family_name'] ?? implode(' ', array_slice(explode(' ', $googleUser->getName()), 1)),
            'email'     => $googleUser->getEmail(),
            'avatar'    => $googleUser->getAvatar(),
        ]);

        return redirect()->route('auth.google.completar');
    }
}
