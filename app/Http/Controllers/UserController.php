<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    const ADMIN_EMAILS = [
        'playerxx606@gmail.com',
        // 'segundo@admin.com',
    ];

    public function index(Request $request)
    {
        $pesquisa = $request->input('pesquisa');
        $query = User::query();

        if ($pesquisa) {
            $query->where(function($q) use ($pesquisa) {
                $q->where('nome', 'like', "%{$pesquisa}%")
                  ->orWhere('sobrenome', 'like', "%{$pesquisa}%")
                  ->orWhere('email', 'like', "%{$pesquisa}%")
                  ->orWhere('ra', 'like', "%{$pesquisa}%");
            });
        }

        $users = $query->paginate(10);
        return view('users.index', compact('users', 'pesquisa'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'sobrenome'       => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'ra'              => 'required|unique:users',
            'telefone'        => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'password'        => 'required|min:6|confirmed',
        ]);

        $tipo = in_array($request->email, self::ADMIN_EMAILS)
            ? 'administrador'
            : 'leitor';

        User::create([
            'nome'            => $request->nome,
            'sobrenome'       => $request->sobrenome,
            'email'           => $request->email,
            'ra'              => $request->ra,
            'telefone'        => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'tipo'            => $tipo,
            'password'        => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        if (auth()->user()->tipo !== 'administrador') {
            abort(403);
        }
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'sobrenome'       => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'ra'              => 'required|unique:users,ra,' . $user->id,
            'telefone'        => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'tipo'            => 'required|in:leitor,editor,administrador',
        ]);

        $user->update([
            'nome'            => $request->nome,
            'sobrenome'       => $request->sobrenome,
            'email'           => $request->email,
            'ra'              => $request->ra,
            'telefone'        => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'tipo'            => $request->tipo,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->tipo !== 'administrador') {
            abort(403, 'Apenas administradores podem excluir usuários.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário removido com sucesso!');
    }
}
