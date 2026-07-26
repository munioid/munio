<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return view('pages.authentication.login', compact('theme', 'organization'));
    }

    public function profile(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;
        $user = Auth::guard('web')->user();

        if (!$user) {
            Notification::make()
                ->title('Anda belum login')
                ->body('Silakan login terlebih dahulu untuk melanjutkan.')
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
            return redirect()->route('login');
        }

        return view('pages.authentication.profile', compact('theme', 'organization'));
    }
}
