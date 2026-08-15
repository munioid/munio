<?php

namespace App\Filament\Imports;

use App\Enums\StoreProductStockStatusEnum;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreProduct;
use App\Models\Store\StoreTag;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreProductImporter extends Importer
{
    protected static ?string $model = StoreProduct::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('category_id')
                ->relationship(
                    name: 'category',
                    resolveUsing: function (string $state, array $options): ?StoreCategory {
                        return StoreCategory::query()
                            ->where('organization_id', $options['organization_id'] ?? null)
                            ->where('slug', $state)
                            ->first();
                    },
                )
                ->example('electronics'),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required'])
                ->example('Wireless Headphones'),
            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required'])
                ->example('wireless-headphones'),
            ImportColumn::make('description')
                ->ignoreBlankState()
                ->example('Rich text description.'),
            ImportColumn::make('price')
                ->numeric(decimalPlaces: 2)
                ->rules(['required', 'min:0'])
                ->example('199000'),
            ImportColumn::make('stock_quantity')
                ->integer()
                ->ignoreBlankState()
                ->rules(['min:0'])
                ->example('15'),
            ImportColumn::make('stock_status')
                ->ignoreBlankState()
                ->rules([Rule::enum(StoreProductStockStatusEnum::class)])
                ->example(StoreProductStockStatusEnum::IN_STOCK->value),
            ImportColumn::make('weight')
                ->numeric(decimalPlaces: 3)
                ->ignoreBlankState()
                ->rules(['min:0'])
                ->example('0.350'),
            ImportColumn::make('is_active')
                ->boolean()
                ->ignoreBlankState()
                ->example('1'),
            ImportColumn::make('sort_order')
                ->integer()
                ->ignoreBlankState()
                ->rules(['min:0'])
                ->example('0'),
            ImportColumn::make('tags')
                ->array()
                ->saveRelationshipsUsing(function (StoreProduct $record, array $state): void {
                    $tagIds = StoreTag::query()
                        ->where('organization_id', $this->getTenantId())
                        ->whereIn('slug', $state)
                        ->pluck('id')
                        ->all();

                    $record->tags()->sync($tagIds);
                })
                ->example('audio,wireless'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $record = static::getModel()::query()
            ->where('organization_id', $this->getTenantId())
            ->where('slug', $this->data['slug'] ?? null)
            ->first();

        return $record ?: new StoreProduct;
    }

    public function beforeValidate(): void
    {
        $this->data['organization_id'] = $this->getTenantId();
    }

    public function beforeSave(): void
    {
        $this->record->organization_id = $this->getTenantId();
        $this->record->category_id = $this->resolveCategoryId($this->data['category_id'] ?? null);
        $this->record->stock_status = $this->resolveStockStatus($this->data['stock_status'] ?? null);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Imported ' . number_format($import->successful_rows) . ' products. ' .
            ($import->getFailedRowsCount() ? number_format($import->getFailedRowsCount()) . ' rows failed validation.' : '');
    }

    protected function resolveCategoryId(mixed $state): ?string
    {
        if (blank($state)) {
            return null;
        }

        return StoreCategory::query()
            ->where('organization_id', $this->getTenantId())
            ->where('slug', $state)
            ->value('id');
    }

    protected function resolveStockStatus(mixed $state): string
    {
        if ($state instanceof StoreProductStockStatusEnum) {
            return $state->value;
        }

        if (blank($state)) {
            return StoreProductStockStatusEnum::IN_STOCK->value;
        }

        $enum = StoreProductStockStatusEnum::tryFrom((string) $state);

        if (! $enum) {
            throw ValidationException::withMessages([
                'stock_status' => 'The stock status is invalid.',
            ]);
        }

        return $enum->value;
    }

    protected function getTenantId(): string
    {
        $tenantId = $this->getOptions()['organization_id'] ?? null;

        if (blank($tenantId)) {
            throw ValidationException::withMessages([
                'organization_id' => 'Missing tenant context for import.',
            ]);
        }

        return (string) $tenantId;
    }
}
