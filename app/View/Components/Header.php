<?php

namespace App\View\Components;

use App\Models\Organization\Organization;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public function __construct(
        public Organization $organization
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header', [$this->organization]);
    }
}
