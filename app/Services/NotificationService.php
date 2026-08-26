<?php

namespace App\Services;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Type to color mapping following Filament style
     */
    protected array $typeColorMap = [
        'success' => '#10b981',  // green
        'error' => '#ef4444',    // red
        'danger' => '#ef4444',   // red (alias for error)
        'warning' => '#f59e0b',  // amber
        'info' => '#3b82f6',     // blue
    ];

    /**
     * Build a Filament notification from structured data
     */
    public function makeNotification(array $data): Notification
    {
        $type = $data['type'] ?? 'info';
        $title = $data['title'] ?? '';
        $message = $data['message'] ?? '';
        $actions = $data['actions'] ?? [];

        $notification = Notification::make()
            ->title($title)
            ->body($message);

        // Set notification type/color
        match ($type) {
            'success' => $notification->success(),
            'error', 'danger' => $notification->danger(),
            'warning' => $notification->warning(),
            'info' => $notification->info(),
            default => $notification->info(),
        };

        // Add actions if provided
        foreach ($actions as $action) {
            $notification->action(
                label: $action['label'] ?? 'Action',
                url: $action['url'] ?? '#',
                shouldOpenInNewTab: $action['newTab'] ?? false,
            );
        }

        // Add close button
        $notification->closeButton();

        return $notification;
    }

    /**
     * Send notification via Filament (for admin/staff panel)
     */
    public function sendToFilament(array $data): void
    {
        $notification = $this->makeNotification($data);
        $notification->send();
    }

    /**
     * Create a structured toast data payload
     */
    public function createToastData(array $data): array
    {
        $type = $data['type'] ?? 'info';
        $id = $data['id'] ?? Str::uuid();
        $duration = $data['duration'] ?? 5000; // milliseconds
        $dismissible = $data['dismissible'] ?? true;

        return [
            'id' => $id,
            'type' => $type,
            'title' => $data['title'] ?? '',
            'message' => $data['message'] ?? '',
            'duration' => $duration,
            'dismissible' => $dismissible,
            'actions' => $data['actions'] ?? [],
            'metadata' => $data['metadata'] ?? [],
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Flash a single toast to session
     */
    public function flashToast(array $data): void
    {
        $toastData = $this->createToastData($data);
        session()->flash('toast', $toastData);
    }

    /**
     * Queue multiple toasts (store in session as array)
     */
    public function queueToasts(array $toasts, string $key = 'toasts'): void
    {
        $structured = array_map([$this, 'createToastData'], $toasts);
        session()->flash($key, $structured);
    }

    /**
     * Flash a success toast
     */
    public function flashSuccess(string $message, string $title = 'Sukses'): void
    {
        $this->flashToast([
            'type' => 'success',
            'title' => $title,
            'message' => $message,
            'duration' => 1000000
        ]);
    }

    /**
     * Flash an error toast
     */
    public function flashError(string $message, string $title = 'Error'): void
    {
        $this->flashToast([
            'type' => 'error',
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Flash an info toast
     */
    public function flashInfo(string $message, string $title = 'Informasi'): void
    {
        $this->flashToast([
            'type' => 'info',
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Flash a warning toast
     */
    public function flashWarning(string $message, string $title = 'Peringatan'): void
    {
        $this->flashToast([
            'type' => 'warning',
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Combine flash toast for storefront + Filament notification for admin
     */
    public function flashAndNotify(array $data): void
    {
        // Always flash to storefront
        $this->flashToast($data);

        // Also send to Filament if user is in admin panel
        $user = Auth::user();
        if ($user && $this->isAdminOrStaff($user)) {
            $this->sendToFilament($data);
        }
    }

    /**
     * Check if user is admin or staff
     */
    protected function isAdminOrStaff($user): bool
    {
        // Check if user has admin role in Filament
        // Adapt this based on your actual role/permission structure
        return $user->hasRole(['admin', 'staff']) ?? false;
    }
}
