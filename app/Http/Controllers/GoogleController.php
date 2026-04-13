<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['Login com Google falhou. Tente novamente.']);
        }

        // Usuário já existe? Loga direto
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            // Atualiza google_id se ainda não tinha
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
            Auth::login($user);
            return redirect()->intended('/');
        }

        // Usuário novo — guarda dados do Google na session e pede campos extras
        Session::put('google_user', [
            'google_id' => $googleUser->getId(),
            'nome'      => $googleUser->user['given_name']  ?? explode(' ', $googleUser->getName())[0],
            'sobrenome' => $googleUser->user['family_name'] ?? implode(' ', array_slice(explode(' ', $googleUser->getName()), 1)),
            'email'     => $googleUser->getEmail(),
        ]);

        return redirect()->route('auth.google.completar');
    }
}
