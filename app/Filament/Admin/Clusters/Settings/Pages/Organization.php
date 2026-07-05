<?php

namespace App\Filament\Admin\Clusters\Settings\Pages;

use App\Filament\Admin\Clusters\Settings;
use App\Models\Organization\Organization as OrganizationModel;
use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Attributes\Locked;

class Organization extends Page
{
    use InteractsWithFormActions;

    protected static ?string $title = 'Organization';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.admin.clusters.settings.pages.organization';

    protected static ?string $cluster = Settings::class;

    public ?array $data = [];

    #[Locked]
    public ?OrganizationModel $record = null;

    public function mount(): void
    {
        $this->record = Filament::getTenant();

        $this->fillForm();
    }

    public function fillForm(): void
    {
        $data = $this->record->attributesToArray();

        $this->content->fill($data);
    }

    public function save(): void
    {
        try {
            $data = $this->content->getState();

            $this->handleRecordUpdate($this->record, $data);
        } catch (Exception $e) {
            throw $e;
        }

        $this->getSavedNotification()->send();
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'));
    }

    public function content(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('General')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(table: 'organizations', column: 'code', ignoreRecord: true),
                        Forms\Components\TextInput::make('domain')
                            ->required()
                            ->unique(table: 'organizations', column: 'domain', ignoreRecord: true)
                    ])
                    ->columns(2)
            ])
            ->model($this->record)
            ->statePath('data')
            ->operation('edit');
    }

    protected function handleRecordUpdate(OrganizationModel $record, array $data): OrganizationModel
    {
        $record->fill($data);

        $keysToWatch = [
            'name',
            'code',
            'domain'
        ];

        if ($record->isDirty($keysToWatch)) {
            $this->dispatch('organizationUpdated', code: data_get($data, 'code'));
        }

        $record->save();

        return $record;
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }
}
