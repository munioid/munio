<?php

namespace Database\Seeders;

use App\Enums\StoreProductStockStatusEnum;
use App\Models\Organization\Organization;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreProduct;
use App\Models\Store\StoreTag;
use Filament\Facades\Filament;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(OrganizationSeeder::class);

        $organization = Organization::query()->where('code', 'default')->firstOrFail();

        Filament::setTenant($organization, true);

        $categories = [
            'electronics' => StoreCategory::query()->firstOrCreate(
                ['slug' => 'electronics'],
                [
                    'name' => 'Electronics',
                    'description' => 'Gadget and device collections.',
                    'is_active' => true,
                    'sort_order' => 1,
                ]
            ),
            'accessories' => StoreCategory::query()->firstOrCreate(
                ['slug' => 'accessories'],
                [
                    'name' => 'Accessories',
                    'description' => 'Accessories and add-ons.',
                    'parent_id' => null, // filled below once the parent exists
                    'is_active' => true,
                    'sort_order' => 2,
                ]
            ),
            'apparel' => StoreCategory::query()->firstOrCreate(
                ['slug' => 'apparel'],
                [
                    'name' => 'Apparel',
                    'description' => 'Wearables and merch.',
                    'is_active' => true,
                    'sort_order' => 3,
                ]
            ),
        ];

        $categories['accessories']->update([
            'parent_id' => $categories['electronics']->id,
        ]);

        $tags = [
            'featured' => StoreTag::query()->firstOrCreate(
                ['slug' => 'featured'],
                [
                    'name' => 'Featured',
                    'description' => 'Highlighted store items.',
                    'is_active' => true,
                    'sort_order' => 1,
                ]
            ),
            'new-arrival' => StoreTag::query()->firstOrCreate(
                ['slug' => 'new-arrival'],
                [
                    'name' => 'New Arrival',
                    'description' => 'Freshly added products.',
                    'is_active' => true,
                    'sort_order' => 2,
                ]
            ),
            'best-seller' => StoreTag::query()->firstOrCreate(
                ['slug' => 'best-seller'],
                [
                    'name' => 'Best Seller',
                    'description' => 'Popular products with strong demand.',
                    'is_active' => true,
                    'sort_order' => 3,
                ]
            ),
        ];

        $products = [
            [
                'name' => 'Wireless Headphones',
                'slug' => 'wireless-headphones',
                'description' => 'Noise-canceling headphones for daily use.',
                'category_id' => $categories['electronics']->id,
                'price' => 129900,
                'stock_quantity' => 24,
                'stock_status' => StoreProductStockStatusEnum::IN_STOCK,
                'weight' => 0.450,
                'sort_order' => 1,
                'tag_keys' => ['featured', 'best-seller'],
            ],
            [
                'name' => 'Travel Charging Case',
                'slug' => 'travel-charging-case',
                'description' => 'Compact charging case for accessories.',
                'category_id' => $categories['accessories']->id,
                'price' => 49000,
                'stock_quantity' => 0,
                'stock_status' => StoreProductStockStatusEnum::OUT_OF_STOCK,
                'weight' => 0.180,
                'sort_order' => 2,
                'tag_keys' => ['new-arrival'],
            ],
            [
                'name' => 'Cotton Tee',
                'slug' => 'cotton-tee',
                'description' => 'Soft cotton shirt for everyday wear.',
                'category_id' => $categories['apparel']->id,
                'price' => 89000,
                'stock_quantity' => 12,
                'stock_status' => StoreProductStockStatusEnum::ON_BACKORDER,
                'weight' => 0.220,
                'sort_order' => 3,
                'tag_keys' => ['featured', 'new-arrival'],
            ],
        ];

        foreach ($products as $productData) {
            $tagKeys = $productData['tag_keys'];
            unset($productData['tag_keys']);

            $product = StoreProduct::query()->firstOrCreate(
                ['slug' => $productData['slug']],
                [
                    ...$productData,
                    'stock_status' => $productData['stock_status']->value,
                ]
            );

            $product->tags()->syncWithoutDetaching(
                collect($tagKeys)->map(fn (string $key) => $tags[$key]->id)->all()
            );
        }
    }
}
