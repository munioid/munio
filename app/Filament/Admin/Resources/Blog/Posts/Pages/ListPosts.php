<?php

namespace App\Filament\Admin\Resources\Blog\Posts\Pages;

use App\Filament\Admin\Resources\Blog\Posts\PostResource;
use App\Filament\Exports\BlogPostExporter;
use App\Filament\Imports\BlogPostImporter;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(BlogPostExporter::class)
                ->label('Export')
                ->icon(Heroicon::ArrowDownTray)
                ->color('info')
                ->options(fn (): array => ['organization_id' => Filament::getTenant()?->id]),
            Actions\ImportAction::make()
                ->importer(BlogPostImporter::class)
                ->label('Import')
                ->icon(Heroicon::ArrowUpTray)
                ->color('info')
                ->options(fn (): array => ['organization_id' => Filament::getTenant()?->id]),
        ];
    }
}
