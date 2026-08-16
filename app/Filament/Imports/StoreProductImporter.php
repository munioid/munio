<?php

namespace App\Filament\Imports;

use App\Enums\StoreProductStockStatusEnum;
use App\Models\Organization\Organization;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreProduct;
use App\Models\Store\StoreTag;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreProductImporter extends Importer
{
    protected static ?string $model = StoreProduct::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('category_id')
                ->label('Category')
                ->guess(['category'])
                ->relationship('category', resolveUsing: function (string $state, StoreProductImporter $importer): ?StoreCategory {
                    return $importer->findOrCreateCategory($state);
                })
                ->example('electronics'),

            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required'])
                ->example('Wireless Headphones'),

            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required'])
                ->fillRecordUsing(function (StoreProduct $record, mixed $state): void {
                    $record->slug = Str::slug(trim((string) $state));
                })
                ->example('wireless-headphones'),

            ImportColumn::make('description')
                ->ignoreBlankState()
                ->example('Rich text description.'),

            ImportColumn::make('price')
                ->numeric(decimalPlaces: 2)
                ->rules(['required', 'numeric', 'min:0'])
                ->example('199000'),

            ImportColumn::make('stock_quantity')
                ->integer()
                ->rules(['required', 'integer', 'min:0'])
                ->example('15'),

            ImportColumn::make('stock_status')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? Str::lower(trim((string) $state)) : $state)
                ->rules(['nullable', Rule::enum(StoreProductStockStatusEnum::class)])
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
                ->label('Tags')
                ->guess(['tag', 'tags'])
                ->relationship('tags', resolveUsing: function (array $state, StoreProductImporter $importer): EloquentCollection {
                    return $importer->findOrCreateTags($state);
                })
                ->multiple(',')
                ->saveRelationshipsUsing(function (StoreProduct $record, array $state, StoreProductImporter $importer): void {
                    $record->tags()->sync($importer->findOrCreateTags($state)->modelKeys());
                })
                ->example('audio,wireless'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $slug = $this->normalizeSlug($this->data['slug'] ?? null);

        if (blank($slug)) {
            return new StoreProduct;
        }

        $record = StoreProduct::query()
            ->where('organization_id', $this->getOrganizationId())
            ->where('slug', $slug)
            ->first();

        return $record ?: new StoreProduct;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Imported '.number_format($import->successful_rows).' products.'.
            ($import->getFailedRowsCount() ? ' '.number_format($import->getFailedRowsCount()).' rows failed validation.' : '');
    }

    protected function beforeValidate(): void
    {
        $this->ensureTenant();
    }

    protected function beforeSave(): void
    {
        $this->record->organization_id = $this->getOrganizationId();
        $this->ensureTenant();
    }

    public function findOrCreateCategory(mixed $state): ?StoreCategory
    {
        $slug = $this->normalizeSlug($state);

        if (blank($slug)) {
            return null;
        }

        $organizationId = $this->getOrganizationId();
        $this->ensureTenant();

        $category = StoreCategory::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->where('slug', $slug)
            ->first();

        if ($category) {
            return $category;
        }

        $category = new StoreCategory;
        $category->forceFill([
            'organization_id' => $organizationId,
            'name' => Str::headline($slug),
            'slug' => $slug,
            'description' => null,
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $category->save();

        return $category;
    }

    /**
     * @param  array<int, mixed>  $state
     */
    public function findOrCreateTags(array $state): EloquentCollection
    {
        $organizationId = $this->getOrganizationId();
        $this->ensureTenant();

        $tags = collect($state)
            ->map(fn (mixed $tag): ?string => $this->normalizeSlug($tag))
            ->filter()
            ->unique()
            ->map(function (string $slug) use ($organizationId): StoreTag {
                $tag = StoreTag::withoutGlobalScopes()
                    ->where('organization_id', $organizationId)
                    ->where('slug', $slug)
                    ->first();

                if ($tag) {
                    return $tag;
                }

                $tag = new StoreTag;
                $tag->forceFill([
                    'organization_id' => $organizationId,
                    'name' => Str::headline($slug),
                    'slug' => $slug,
                    'description' => null,
                    'is_active' => true,
                    'sort_order' => 0,
                ]);
                $tag->save();

                return $tag;
            })
            ->values()
            ->all();

        return new EloquentCollection($tags);
    }

    protected function ensureTenant(): void
    {
        if (Filament::getTenant() instanceof Organization) {
            return;
        }

        Filament::setTenant(Organization::query()->findOrFail($this->getOrganizationId()), true);
    }

    protected function normalizeSlug(mixed $state): ?string
    {
        $slug = Str::slug(trim((string) $state));

        return filled($slug) ? $slug : null;
    }

    protected function getOrganizationId(): string
    {
        $organizationId = $this->getOptions()['organization_id'] ?? null;

        if (blank($organizationId)) {
            throw ValidationException::withMessages([
                'organization_id' => 'Missing tenant context for import.',
            ]);
        }

        return (string) $organizationId;
    }
}
