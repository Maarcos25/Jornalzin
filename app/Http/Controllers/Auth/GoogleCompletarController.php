<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleCompletarController extends Controller
{
    public function create()
    {
        if (!session('google_user')) {
            return redirect()->route('login');
        }

        return view('auth.google-completar');
    }

    public function store(Request $request)
    {
        if (!session('google_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'ra'              => ['required', 'unique:users,ra'],
            'telefone'        => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['nullable', 'date'],
        ]);

        $googleUser = session('google_user');

        $user = User::create([
            'nome'            => $googleUser['nome'],
            'sobrenome'       => $googleUser['sobrenome'],
            'email'           => $googleUser['email'],
            'ra'              => $request->ra,
            'telefone'        => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'tipo'            => 'leitor',
            'avatar'          => $googleUser['avatar'] ?? null,
            'password'        => Hash::make(str()->random(32)),
        ]);

        session()->forget('google_user');

        Auth::login($user);

        return redirect()->route('home');
    }
}
