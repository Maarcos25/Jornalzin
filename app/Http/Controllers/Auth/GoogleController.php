<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update(['google_id' => $googleUser->getId()]);
        } else {
            $user = User::create([
                'nome'       => $googleUser->getName(),
                'email'      => $googleUser->getEmail(),
                'google_id'  => $googleUser->getId(),
                'password'   => bcrypt(str()->random(24)),
                'tipo'       => 'leitor',
                'ra'         => '00000',
                'sobrenome'  => '',
            ]);
        }

        Auth::login($user);

        return redirect('/');
    }
}
