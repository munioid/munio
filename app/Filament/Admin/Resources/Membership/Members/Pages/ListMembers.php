<?php

namespace App\Filament\Admin\Resources\Membership\Members\Pages;

use App\Filament\Admin\Resources\Membership\Members\MemberResource;
use App\Filament\Exports\MemberExporter;
use App\Filament\Imports\MemberImporter;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(MemberExporter::class)
                ->label('Export Members')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->options(fn (): array => ['organization_id' => Filament::getTenant()?->id]),
            Actions\ImportAction::make()
                ->importer(MemberImporter::class)
                ->label('Import Members')
                ->icon('heroicon-o-document-arrow-up')
                ->color('info')
                ->options(fn (): array => ['organization_id' => Filament::getTenant()?->id]),
        ];
    }
}
