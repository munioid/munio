<?php

namespace App\Filament\Admin\Resources\Membership\Members;

use App\Models\Membership\Attribute;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MemberExporter extends Exporter
{
    /**
     * @return array<ExportColumn>
     */
    public static function getColumns(): array
    {
        $columns = [
            ExportColumn::make('package_code')
                ->label('package_code')
                ->state(fn (Model $record) => $record->package?->code)
                ->enabledByDefault(true),
            ExportColumn::make('number')
                ->label('number')
                ->enabledByDefault(true),
            ExportColumn::make('name')
                ->label('name')
                ->enabledByDefault(true),
            ExportColumn::make('email')
                ->label('email')
                ->enabledByDefault(true),
            ExportColumn::make('phone')
                ->label('phone')
                ->enabledByDefault(true),
        ];

        $organization = Filament::getTenant();

        if ($organization) {
            $attributes = Attribute::query()
                ->where('organization_id', $organization->id)
                ->where('is_private', false)
                ->orderBy('created_at')
                ->get();

            foreach ($attributes as $attribute) {
                $columns[] = ExportColumn::make("at_{$attribute->fieldname}")
                    ->label("at_{$attribute->fieldname}")
                    ->state(function (Model $record) use ($attribute) {
                        return $record->attributes()
                            ->where('membership_attributes.fieldname', $attribute->fieldname)
                            ->first()?->pivot?->value;
                    })
                    ->enabledByDefault(true);
            }
        }

        $columns = array_merge($columns, [
            ExportColumn::make('status')
                ->label('status')
                ->enabledByDefault(true),
            ExportColumn::make('status_updated_at')
                ->label('status_updated_at')
                ->enabledByDefault(true),
        ]);

        return $columns;
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['package', 'attributes']);
    }

    public function getFormats(): array
    {
        // XLSX only, per the issue's XLSX requirement.
        return [ExportFormat::Xlsx];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = trans_choice('filament-actions::export.notifications.completed.body', [
            'count' => number_format($export->total_rows),
        ]);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.trans_choice('filament-actions::export.notifications.completed.body.failed', [
                'count' => number_format($failedRowsCount),
            ]);
        }

        return $body;
    }
}
