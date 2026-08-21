<?php

namespace Tests\Feature;

use App\Enums\StoreProductStockStatusEnum;
use App\Filament\Exports\StoreProductExporter;
use App\Models\Organization\Organization;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreProduct;
use App\Models\Store\StoreTag;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreProductExporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_exports_products_with_correct_column_mapping(): void
    {
        // Get exporter columns
        $columns = StoreProductExporter::getColumns();

        $this->assertCount(11, $columns);

        // Verify column keys match importer's expected column order
        $columnKeys = array_map(fn ($col) => $col->getName(), $columns);
        $expectedColumns = ['name', 'slug', 'description', 'price', 'stock_quantity', 'stock_status', 'weight', 'category_id', 'tags', 'is_active', 'sort_order'];
        $this->assertSame($expectedColumns, $columnKeys);
    }

    public function test_it_exports_completed_notification_body(): void
    {
        $notification = StoreProductExporter::getCompletedNotificationBody(
            (object) ['successful_rows' => 42]
        );

        $this->assertStringContainsString('42', $notification);
        $this->assertStringContainsString('products', $notification);
    }
}
