<?php

namespace App\Filament\Admin\Resources\Store\Products\Pages;

use App\Filament\Admin\Resources\Store\Products\StoreProductResource;
use App\Filament\Exports\StoreProductExporter;
use App\Filament\Imports\StoreProductImporter;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListStoreProducts extends ListRecords
{
    protected static string $resource = StoreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(StoreProductExporter::class)
                ->label('Export')
                ->icon(Heroicon::ArrowDownTray)
                ->color('info')
                ->options(fn (): array => [
                    'organization_id' => Filament::getTenant()?->getKey(),
                ]),
            Actions\ImportAction::make()
                ->importer(StoreProductImporter::class)
                ->label('Import')
                ->icon(Heroicon::ArrowUpTray)
                ->color('info')
                ->chunkSize(1000)
                ->options(fn (): array => [
                    'organization_id' => Filament::getTenant()?->getKey(),
                ]),
        ];
    }
}
