<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GoogleCompletarController extends Controller
{
    public function create()
    {
        // Se não veio do Google, manda pro login
        if (!Session::has('google_user')) {
            return redirect('/login');
        }
        return view('auth.google-completar');
    }

    public function store(Request $request)
    {
        if (!Session::has('google_user')) {
            return redirect('/login');
        }

        $request->validate([
            'ra'              => 'required|string|max:20|unique:users,ra',
            'telefone'        => 'required|digits:11',
            'data_nascimento' => 'required|date|before:today',
        ]);

        $googleData = Session::get('google_user');

        $user = User::create([
            'google_id'       => $googleData['google_id'],
            'nome'            => $googleData['nome'],
            'sobrenome'       => $googleData['sobrenome'],
            'email'           => $googleData['email'],
            'ra'              => $request->ra,
            'telefone'        => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'password'        => null, // login só via Google
            'tipo'            => 'aluno', // ou sua lógica de tipo por email
        ]);

        Session::forget('google_user');
        Auth::login($user);

        return redirect('/');
    }
}
