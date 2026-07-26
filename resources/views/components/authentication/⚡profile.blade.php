<?php

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public User $user;
    public string $initial;

    public function mount()
    {
        $user = Auth::guard('web')->user();

        $this->user = $user;
        $this->initial = collect(explode(' ', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        Notification::make()
            ->title('Berhasil keluar')
            ->body('Anda telah berhasil keluar.')
            ->success()
            ->actions([
                Action::make('close')
                    ->label('Tutup')
                    ->button()
                    ->color('primary')
                    ->close(),
            ])
            ->send();

        return redirect('/');
    }
};
?>

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-white px-6 py-10 text-center shadow-sm">

        @if($user->avatar)
        <img
            src="{{ $user->avatar->getPath() }}"
            class="mx-auto h-24 w-24 rounded-full object-cover">
        @else
        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-primary text-3xl font-bold text-white">
            {{ $initial }}
        </div>
        @endif

        <h1 class="mt-4 text-2xl font-bold">
            {{ $user->name }}
        </h1>

        <p class="mt-1 text-gray-500">
            {{ $user->email }}
        </p>

    </div>

    {{-- Menu --}}
    <div class="mt-5 space-y-3 px-5">

        <a
            href="/profile/edit"
            class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">

            <div class="flex items-center gap-3">
                <x-heroicon-o-user class="h-6 w-6 text-primary" />
                <span>Edit Profil</span>
            </div>

            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
        </a>

        <a
            href="/profile/password"
            class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">

            <div class="flex items-center gap-3">
                <x-heroicon-o-lock-closed class="h-6 w-6 text-primary" />
                <span>Ubah Password</span>
            </div>

            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
        </a>

        <a
            href="/my-events"
            class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">

            <div class="flex items-center gap-3">
                <x-heroicon-o-calendar-days class="h-6 w-6 text-primary" />
                <span>Reservasi Acara</span>
            </div>

            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
        </a>

    </div>

    {{-- Logout --}}
    <div class="mt-8 px-5">

        <button
            wire:click="logout"
            class="w-full rounded-2xl border border-red-500 py-4 font-semibold text-red-500">

            Keluar

        </button>

    </div>

</div>