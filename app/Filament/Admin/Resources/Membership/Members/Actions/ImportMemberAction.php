<?php

namespace App\Filament\Admin\Resources\Membership\Members\Actions;

use App\Filament\Admin\Resources\Membership\Members\MemberImporter;
use App\Models\Organization\Organization;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportMemberAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'importMembers';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions::import.label', ['label' => 'Members']));
        $this->modalHeading(__('filament-actions::import.modal.heading', ['label' => 'Members']));
        $this->modalSubmitActionLabel(__('filament-actions::import.modal.actions.import.label'));
        $this->icon(FilamentIcon::resolve(Heroicon::ArrowUpTray));
        $this->color('gray');
        $this->modalWidth('lg');

        $this->schema([
            FileUpload::make('file')
                ->label(__('filament-actions::import.modal.form.file.label'))
                ->placeholder(__('filament-actions::import.modal.form.file.placeholder'))
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                    'text/csv',
                    'application/csv',
                    'text/comma-separated-values',
                ])
                ->rules([
                    'extensions:xlsx,xls,csv',
                ])
                ->storeFiles(false)
                ->visibility('private')
                ->required()
                ->hiddenLabel(),
        ]);

        $this->action(function (Component $livewire, array $data): void {
            /** @var TemporaryUploadedFile $file */
            $file = $data['file'];

            $organization = Organization::query()->findOrFail($this->getOptions()['organization_id'] ?? '');

            try {
                $importer = new MemberImporter($organization);

                Excel::import($importer, $file->getRealPath());

                $livewire->dispatch('refresh');

                Notification::make()
                    ->title('Import completed')
                    ->body('Members imported successfully.')
                    ->success()
                    ->send();
            } catch (ValidationException $exception) {
                Notification::make()
                    ->title('Import failed')
                    ->body('Validation errors occurred during import.')
                    ->danger()
                    ->send();

                throw $exception;
            } catch (Throwable $exception) {
                report($exception);

                Notification::make()
                    ->title('Import failed')
                    ->body('An error occurred during import.')
                    ->danger()
                    ->send();
            }
        });

        $this->options([
            'organization_id' => Organization::query()
                ->whereKey(Filament::getTenant()?->id)
                ->firstOrFail()
                ->id,
        ]);
    }
}
