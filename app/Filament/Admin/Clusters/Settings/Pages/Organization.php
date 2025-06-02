<?php

namespace App\Filament\Admin\Clusters\Settings\Pages;

use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Livewire\Attributes\Locked;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use App\Filament\Admin\Clusters\Settings;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Concerns\InteractsWithFormActions;
use App\Models\Organization\Organization as OrganizationModel;

class Organization extends Page
{
    use InteractsWithFormActions;

    protected static ?string $title = 'Organization';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.clusters.settings.pages.organization';

    protected static ?string $cluster = Settings::class;

    public ?array $data = [];

    #[Locked]
    public ?OrganizationModel $record = null;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenTwoExtraLarge;
    }

    public function mount(): void
    {
        $this->record = Filament::getTenant();

        $this->fillForm();
    }

    public function fillForm(): void
    {
        $data = $this->record->attributesToArray();

        $this->form->fill($data);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')
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
