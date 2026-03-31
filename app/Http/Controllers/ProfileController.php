<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Exibe o formulário de perfil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Atualiza nome, email e avatar.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'nome'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user->nome  = $request->nome;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado!');
    }

    /**
     * Atualiza a senha.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);


        if (!Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->to(route('profile.edit') . '#senha')
                ->withErrors(['current_password' => 'A senha atual está incorreta.'])
                ->withInput();
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->to(route('profile.edit') . '#senha')
            ->with('senha_success', 'Senha atualizada com sucesso!');
    }

    /**
     * Remove o avatar.
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Avatar removido!');
    }

    /**
     * Exclui a conta do usuário.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
