<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Pendaftaran;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $pendaftaran = \App\Models\Pendaftaran::with(['jurusan', 'cabang'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('profile.edit', compact('user', 'pendaftaran'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
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
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pendaftaran = Pendaftaran::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return back()->withErrors([
                'pas_foto' => 'Data pendaftaran tidak ditemukan.'
            ]);
        }

        if ($pendaftaran->pas_foto &&
            Storage::disk('public')->exists($pendaftaran->pas_foto)) {

            Storage::disk('public')->delete($pendaftaran->pas_foto);
        }

        $path = $request->file('pas_foto')
            ->store('pas-foto', 'public');

        $pendaftaran->update([
            'pas_foto' => $path
        ]);

        return back()->with('success', 'Pas foto berhasil diperbarui.');
    }
}
