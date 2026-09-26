<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $adherent = Auth::guard('adherent')->user();
        return view('adherent.profile', compact('adherent'));
    }

    public function update(Request $request)
    {
        $adherent = Auth::guard('adherent')->user();

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:30',
            'ville' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'etablissement' => 'nullable|string|max:255',
        ]);

        $adherent->update($validated);

        return redirect()->route('adherent.profile.edit')
            ->with('success', 'Vos informations ont été mises à jour.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $adherent = Auth::guard('adherent')->user();

        if (!Hash::check($request->current_password, $adherent->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $adherent->update(['password' => Hash::make($request->password)]);

        return redirect()->route('adherent.profile.edit')
            ->with('success', 'Votre mot de passe a été modifié.');
    }
}