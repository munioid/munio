<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\ChangePasswordRequest;
use App\Http\Requests\Authentication\UpdateProfile;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Authentication/Profile', [
            'user' => $user,
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Authentication/EditProfile', [
            'user' => $user,
        ]);
    }

    public function updateProfile(UpdateProfile $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // Update profile
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? $user->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function changePassword(Request $request)
    {
        return Inertia::render('Authentication/ChangePassword');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }

    public function myReservations(Request $request)
    {
        $user = $request->user();

        $reservations = $user->reservations()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Authentication/MyReservations', [
            'reservations' => $reservations,
        ]);
    }
}
