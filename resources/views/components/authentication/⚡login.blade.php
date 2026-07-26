<?php

use App\Models\Organization\Organization;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    public string $theme;
    public Organization $organization;

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public function login()
    {
        try {
            $this->validate();

            if (! Auth::guard('web')->attempt([
                'email' => $this->email,
                'password' => $this->password,
            ])) {
                throw new Exception('Email atau password yang Anda masukkan tidak sesuai.');
            }

            request()->session()->regenerate();

            Notification::make()
                ->title('Login berhasil')
                ->body('Selamat datang kembali!')
                ->success()
                ->persistent()
                ->actions([
                    Action::make('close')
                        ->label('Close')
                        ->button()
                        ->color('primary')
                        ->close(),
                ])
                ->send();

            return redirect()->intended('/');
        } catch (Exception $e) {
            Notification::make()
                ->title('Login gagal')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->actions([
                    Action::make('close')
                        ->label('Close')
                        ->button()
                        ->color('danger')
                        ->close(),
                ])
                ->send();
        }
    }
};
?>

<x-authentication.login :theme=$theme :organization=$organization />