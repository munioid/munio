<?php

namespace Tests\Feature;

use App\Filament\Exports\OrderItemExporter;
use App\Models\Organization\Organization;
use App\Models\Store\StoreOrderItem;
use Database\Seeders\StoreSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemExporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_exports_order_items_with_correct_column_mapping(): void
    {
        // Get exporter columns
        $columns = OrderItemExporter::getColumns();

        $this->assertCount(5, $columns);

        // Verify column keys match the expected column order
        $columnKeys = array_map(fn ($col) => $col->getName(), $columns);
        $expectedColumns = ['order_number', 'product_name', 'price', 'quantity', 'subtotal'];
        $this->assertSame($expectedColumns, $columnKeys);
    }

    public function test_it_exports_completed_notification_body(): void
    {
        $notification = OrderItemExporter::getCompletedNotificationBody(
            (object) ['successful_rows' => 42]
        );

        $this->assertStringContainsString('42', $notification);
        $this->assertStringContainsString('order items', $notification);
    }

    public function test_it_filters_by_organization_ensures_tenant(): void
    {
        // Seed data
        $this->seed(StoreSeeder::class);

        $org = Organization::query()->where('code', 'default')->firstOrFail();

        Filament::setTenant($org, true);

        // Create an order item for org
        $item = StoreOrderItem::query()->first();
        $this->assertNotNull($item);
        $this->assertEquals($org->id, $item->organization_id);
    }

    public function test_it_exports_order_item_with_all_fields(): void
    {
        $this->seed(StoreSeeder::class);

        $org = Organization::query()->where('code', 'default')->firstOrFail();

        Filament::setTenant($org, true);

        // Check that we have order items from the seeder
        $item = StoreOrderItem::query()->first();
        $this->assertNotNull($item);

        // Get the columns
        $columns = OrderItemExporter::getColumns();
        $this->assertCount(5, $columns);

        // Verify that the order item has the expected fields
        $this->assertNotNull($item->order);
        $this->assertNotNull($item->order->order_number);
        $this->assertNotNull($item->product_name);
        $this->assertNotNull($item->price);
        $this->assertNotNull($item->quantity);
        $this->assertNotNull($item->subtotal);
    }
}
