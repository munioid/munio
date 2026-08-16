<?php

namespace Tests\Feature;

use App\Enums\StoreProductStockStatusEnum;
use App\Filament\Imports\StoreProductImporter;
use App\Models\Organization\Organization;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreProduct;
use App\Models\Store\StoreTag;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Throwable;

class StoreProductImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_missing_categories_and_tags_by_slug(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Acme Store',
            'code' => 'acme-store',
            'subdomain' => 'acme-store',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'products.csv',
            'file_path' => 'imports/products.csv',
            'importer' => StoreProductImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'category_id' => 'Electronics',
            'name' => 'Wireless Headphones',
            'slug' => 'Wireless Headphones',
            'description' => 'Noise-canceling headphones.',
            'price' => '129900',
            'stock_quantity' => '24',
            'stock_status' => 'IN_STOCK',
            'weight' => '0.450',
            'is_active' => '1',
            'sort_order' => '1',
            'tags' => 'Featured, New Arrival',
        ]);

        $product = StoreProduct::query()->where('slug', 'wireless-headphones')->first();

        $this->assertNotNull($product);
        $this->assertSame($organization->id, $product->organization_id);
        $this->assertSame('electronics', $product->category?->slug);
        $this->assertSame(StoreProductStockStatusEnum::IN_STOCK, $product->stock_status);
        $this->assertSame(2, $product->tags()->count());

        $this->assertSame(1, StoreCategory::query()->where('organization_id', $organization->id)->where('slug', 'electronics')->count());
        $this->assertSame(2, StoreTag::query()->where('organization_id', $organization->id)->whereIn('slug', ['featured', 'new-arrival'])->count());
    }

    public function test_it_syncs_tags_from_import_without_overwriting_other_tag_records(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Acme Store',
            'code' => 'acme-store-3',
            'subdomain' => 'acme-store-3',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $existingTag = StoreTag::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Audio',
            'slug' => 'audio',
            'description' => 'Old description',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $extraTag = StoreTag::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Old Tag',
            'slug' => 'old-tag',
            'description' => null,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $import = Import::query()->create([
            'file_name' => 'products.csv',
            'file_path' => 'imports/products.csv',
            'importer' => StoreProductImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'category_id' => 'Electronics',
            'name' => 'Wireless Headphones',
            'slug' => 'Wireless Headphones',
            'description' => 'Noise-canceling headphones.',
            'price' => '129900',
            'stock_quantity' => '24',
            'stock_status' => 'in_stock',
            'weight' => '0.450',
            'is_active' => '1',
            'sort_order' => '1',
            'tags' => 'Audio, wireles',
        ]);

        $product = StoreProduct::query()->where('slug', 'wireless-headphones')->first();

        $this->assertNotNull($product);
        $this->assertSame(['audio', 'wireles'], $product->tags()->orderBy('slug')->pluck('slug')->all());
        $this->assertSame('Old description', $existingTag->refresh()->description);
        $this->assertTrue(StoreTag::query()->whereKey($extraTag->id)->exists());
        $this->assertFalse($product->tags()->whereKey($extraTag->id)->exists());
    }

    public function test_it_rejects_invalid_rows_without_affecting_valid_rows(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Acme Store',
            'code' => 'acme-store-2',
            'subdomain' => 'acme-store-2',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'products.csv',
            'file_path' => 'imports/products.csv',
            'importer' => StoreProductImporter::class,
            'processed_rows' => 0,
            'total_rows' => 2,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'category_id' => 'Electronics',
            'name' => 'Wireless Headphones',
            'slug' => 'Wireless Headphones',
            'description' => 'Noise-canceling headphones.',
            'price' => '129900',
            'stock_quantity' => '24',
            'stock_status' => 'in_stock',
            'weight' => '0.450',
            'is_active' => '1',
            'sort_order' => '1',
            'tags' => 'Featured, New Arrival',
        ]);

        try {
            $importer([
                'category_id' => 'Accessories',
                'name' => 'Bad Product',
                'slug' => 'Bad Product',
                'description' => 'This row should fail.',
                'price' => '-10',
                'stock_quantity' => '-1',
                'stock_status' => 'broken',
                'weight' => '0.100',
                'is_active' => '1',
                'sort_order' => '2',
                'tags' => 'Broken, Invalid',
            ]);

            $this->fail('Expected validation to fail for the invalid product row.');
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(ValidationException::class, $throwable);
            $errors = method_exists($throwable, 'errors') ? $throwable->errors() : [];
            $this->assertArrayHasKey('price', $errors);
            $this->assertArrayHasKey('stock_quantity', $errors);
            $this->assertArrayHasKey('stock_status', $errors);
        }

        $this->assertSame(1, StoreProduct::query()->where('organization_id', $organization->id)->count());
        $this->assertNotNull(StoreProduct::query()->where('slug', 'wireless-headphones')->first());
        $this->assertNull(StoreProduct::query()->where('slug', 'bad-product')->first());
    }

    /**
     * @return array<string, string>
     */
    private function columnMap(): array
    {
        return [
            'category_id' => 'category_id',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
            'price' => 'price',
            'stock_quantity' => 'stock_quantity',
            'stock_status' => 'stock_status',
            'weight' => 'weight',
            'is_active' => 'is_active',
            'sort_order' => 'sort_order',
            'tags' => 'tags',
        ];
    }
}
