<?php

namespace App\View\Components\Authentication;

use App\Models\Organization\Organization;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChangePassword extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $theme,
        public Organization $organization,
        public User $user
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme.'.components.authentication.change-password');
    }
}
