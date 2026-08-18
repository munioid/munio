<?php

namespace App\Filament\Admin\Resources\Membership\Members\Pages;

use App\Filament\Admin\Resources\Membership\Members\MemberResource;
use App\Filament\Exports\MemberExporter;
use App\Filament\Imports\MemberImporter;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(MemberExporter::class)
                ->label('Export')
                ->icon(Heroicon::ArrowDownTray)
                ->color('info')
                ->options(fn (): array => ['organization_id' => Filament::getTenant()?->id]),
            Actions\ImportAction::make()
                ->importer(MemberImporter::class)
                ->label('Import')
                ->icon(Heroicon::ArrowUpTray)
                ->color('info')
                ->options(fn (): array => ['organization_id' => Filament::getTenant()?->id]),
        ];
    }
}
