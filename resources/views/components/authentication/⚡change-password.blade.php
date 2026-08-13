<?php

use App\Filament\Support\Notify;
use App\Models\Organization\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component
{
    public User $user;
    public Organization $organization;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function save()
    {
        $this->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus terdiri dari 8 karakter.',
            'confirmed' => 'Konfirmasi password tidak sesuai.'
        ]);

        try {
            DB::beginTransaction();

            $user = $this->user;

            if (! Hash::check($this->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Password lama yang Anda masukkan tidak sesuai.',
                ]);
            }

            $user->password = Hash::make($this->password);
            $user->save();

            DB::commit();

            $this->reset([
                'current_password',
                'password',
                'password_confirmation',
            ]);

            // Pengguna dipaksa login ulang setelah ganti password,
            Auth::guard('web')->logout();

            session()->invalidate();
            session()->regenerateToken();

            Notify::success(
                'Password berhasil diperbarui',
                'Silakan masuk kembali menggunakan password baru Anda.'
            );

            $this->redirectRoute('login', navigate: true);
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (Throwable $th) {
            DB::rollBack();

            report($th);

            Notify::danger(
                'Password gagal diperbarui',
                $th->getMessage(),
            );
        }
    }
};
?>

<x-authentication.change-password :user=$user />