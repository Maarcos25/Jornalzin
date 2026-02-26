<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController
{

    // public function __construct()
    // {
    //     $this->middleware('auth');

    //     $this->middleware(function ($request, $next) {
    //         if (auth()->user()->tipo !== 'administrador') {
    //             abort(403);
    //         }

    //         return $next($request);
    //     });
    // }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'ra' => 'required|unique:users',
            'telefone' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'tipo' => 'required|in:administrador,editor,leitor',
            'password' => 'required|min:6|confirmed'
        ]);

        User::create([
            'nome' => $request->nome,
            'sobrenome' => $request->sobrenome,
            'email' => $request->email,
            'ra' => $request->ra,
            'telefone' => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'tipo' => $request->tipo,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'ra' => 'required|unique:users,ra,' . $user->id,
            'telefone' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'tipo' => 'required|in:administrador,editor,leitor',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário removido com sucesso!');
    }
}
