<?php

use App\Filament\Support\Notify;
use App\Models\File;
use App\Models\Organization\Organization;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;
    public User $user;
    public Organization $organization;
    public string $initial;

    public string $name;
    public string $email;
    public ?TemporaryUploadedFile $avatar = null;

    public function mount()
    {
        $this->user = $user = Auth::user();
        $this->initial = collect(explode(' ', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function save()
    {
        try {
            DB::beginTransaction();

            $user = $this->user;
            $user->name = $this->name;
            $user->email = $this->email;

            $emailChanged = $user->isDirty('email');

            $user->save();

            DB::commit();

            if ($emailChanged) {
                Auth::guard('web')->logout();

                session()->invalidate();
                session()->regenerateToken();

                Notify::success('Profil berhasil diperbarui', 'Silakan masuk kembali menggunakan email baru Anda.');
                $this->redirectRoute('login', navigate: true);
            } else {
                Notify::success('Profil berhasil diperbarui');
            }
        } catch (Throwable $th) {
            DB::rollBack();
            Notify::danger('Profil gagal diperbarui', $th->getMessage());
        }
    }

    public function updatedAvatar(TemporaryUploadedFile $avatar): void
    {
        Filament::setTenant($this->organization, true);

        $this->validate([
            'avatar' => 'image|max:2048', // 2 MB
        ]);

        try {
            DB::transaction(function () use ($avatar) {

                // Hapus avatar lama jika ada
                if ($this->user->avatar) {
                    $this->user->avatar->delete();
                }

                // Simpan avatar baru
                $media = File::upload($avatar, 'avatar', 'users'); // sesuaikan dengan class Media Anda

                $this->user->avatar()->save($media);
                $this->user->save();
            });

            $this->user->refresh();

            Notify::success('Foto profil berhasil diperbarui');
        } catch (Throwable $th) {
            report($th);

            Notify::danger(
                'Foto profil gagal diperbarui',
                $th->getMessage()
            );
        }
    }
};
?>
<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="relative bg-primary px-6 pb-16 pt-8 text-center">

        <button
            type="button"
            onclick="history.back()"
            class="absolute left-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur">

            <x-heroicon-o-arrow-left class="h-5 w-5" />

        </button>

        <h1 class="text-xl font-semibold text-white">
            Edit Profil
        </h1>

        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2">

            <div class="relative">

                @if($user->avatar)
                <img
                    src="{{ $user->avatar->getPath() }}"
                    class="h-28 w-28 rounded-full border-4 border-white object-cover shadow-xl">
                @else
                <div class="flex h-28 w-28 items-center justify-center rounded-full border-4 border-white bg-white text-4xl font-bold text-primary shadow-xl">
                    {{ $initial }}
                </div>
                @endif

                <label
                    for="avatar"
                    class="absolute bottom-1 right-1 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-primary text-white shadow-lg">

                    <x-heroicon-o-camera class="h-5 w-5" />

                </label>

                <input
                    id="avatar"
                    type="file"
                    wire:model="avatar"
                    class="hidden">

            </div>

        </div>

    </div>

    <form wire:submit="save">

        <div class="px-5 pb-8 pt-20">

            {{-- Form Card --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                <div class="divide-y divide-gray-100">

                    {{-- Nama --}}
                    <div class="p-5">
                        <label class="mb-2 block text-sm font-medium text-gray-500">
                            Nama
                        </label>

                        <x-filament::input.wrapper>
                            <x-filament::input
                                wire:model="name"
                                type="text" />
                        </x-filament::input.wrapper>
                    </div>

                    {{-- Email --}}
                    <div class="p-5">
                        <label class="mb-2 block text-sm font-medium text-gray-500">
                            Email
                        </label>

                        <x-filament::input.wrapper>
                            <x-filament::input
                                wire:model="email"
                                type="email" />
                        </x-filament::input.wrapper>
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="mt-6 w-full rounded-2xl bg-primary py-4 font-semibold text-white transition hover:opacity-90">

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>