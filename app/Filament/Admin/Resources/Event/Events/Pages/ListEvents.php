<?php

namespace App\Filament\Admin\Resources\Event\Events\Pages;

use App\Filament\Admin\Resources\Event\Events\EventResource;
use App\Filament\Exports\EventExporter;
use App\Filament\Exports\PackageExporter;
use App\Filament\Imports\EventImporter;
use App\Filament\Imports\PackageImporter;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ActionGroup::make([
                ExportAction::make('exportEvents')
                    ->label('Export Events')
                    ->exporter(EventExporter::class)
                    ->options([
                        'organization_id' => Filament::getTenant()?->id,
                    ]),
                ExportAction::make('exportPackages')
                    ->label('Export Packages')
                    ->exporter(PackageExporter::class)
                    ->options([
                        'organization_id' => Filament::getTenant()?->id,
                    ]),
            ])
                ->label('Export')
                ->button()
                ->color('info'),
            ActionGroup::make([
                ImportAction::make('importEvents')
                    ->label('Import Events')
                    ->importer(EventImporter::class)
                    ->options([
                        'organization_id' => Filament::getTenant()?->id,
                    ]),
                ImportAction::make('importPackages')
                    ->label('Import Packages')
                    ->importer(PackageImporter::class)
                    ->options([
                        'organization_id' => Filament::getTenant()?->id,
                    ]),
            ])
                ->label('Import')
                ->button()
                ->color('info'),
        ];
    }
}
