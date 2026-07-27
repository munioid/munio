<?php

use App\Models\Event\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public string $theme;
    public int $perPage = 5;

    public User $user;

    public function mount()
    {
        $this->user = Auth::user();
    }
    public function getReservationsProperty()
    {
        return Reservation::query()
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function loadMore(): void
    {
        $this->perPage += 5;
    }
};
?>

<x-authentication.my-reservation :theme=$theme :reservations="$this->reservations" />