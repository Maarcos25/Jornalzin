<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

     public function update(Request $request)
     {
         $user = auth()->user();

         // Corrigido: name
         $user->name = $request->name;
         $user->email = $request->email;

         if ($request->hasFile('avatar')) {

             // Deleta imagem antiga
             if ($user->avatar) {
                 Storage::disk('public')->delete($user->avatar);
             }

             // Salva nova
             $path = $request->file('avatar')->store('avatars', 'public');

             $user->avatar = $path;
         }

         $user->save();

         return back()->with('success', 'Perfil atualizado!');
     }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {

    $user = $request->user();

    Auth::logout(); // desloga

    $user->delete(); // deleta usuário

    return redirect('/'); // volta para home

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function deleteAvatar(Request $request)
{
    $user = $request->user();

    if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
        $user->avatar = null;
        $user->save();
    }

    return redirect()->back()->with('success', 'Avatar removido!');
}
}
