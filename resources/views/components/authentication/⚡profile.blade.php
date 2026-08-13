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
    public string $theme;

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
<x-authentication.profile :theme=$theme :user=$user :initial=$initial />