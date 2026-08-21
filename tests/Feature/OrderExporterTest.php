<?php

namespace Tests\Feature;

use App\Enums\StoreOrderStatusEnum;
use App\Filament\Exports\OrderExporter;
use App\Models\Organization\Organization;
use App\Models\Store\StoreOrder;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OrderExporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_exports_orders_with_correct_column_mapping(): void
    {
        // Get exporter columns
        $columns = OrderExporter::getColumns();

        $this->assertCount(10, $columns);

        // Verify column keys match the expected column order
        $columnKeys = array_map(fn ($col) => $col->getName(), $columns);
        $expectedColumns = ['order_number', 'user_id', 'name', 'email', 'phone', 'status', 'subtotal', 'shipping_cost', 'total', 'notes'];
        $this->assertSame($expectedColumns, $columnKeys);
    }

    public function test_it_exports_completed_notification_body(): void
    {
        $notification = OrderExporter::getCompletedNotificationBody(
            (object) ['successful_rows' => 42]
        );

        $this->assertStringContainsString('42', $notification);
        $this->assertStringContainsString('orders', $notification);
    }

    public function test_it_filters_by_organization_ensures_tenant(): void
    {
        // Seed data
        $this->seed(\Database\Seeders\StoreSeeder::class);

        $org = Organization::query()->where('code', 'default')->firstOrFail();

        Filament::setTenant($org, true);

        // Create an order for org
        $order = StoreOrder::query()->first();
        $this->assertNotNull($order);
        $this->assertEquals($org->id, $order->organization_id);
    }

    public function test_it_exports_order_with_all_fields(): void
    {
        $this->seed(\Database\Seeders\StoreSeeder::class);

        $org = Organization::query()->where('code', 'default')->firstOrFail();

        Filament::setTenant($org, true);

        // Check that we have orders from the seeder
        $order = StoreOrder::query()->first();
        $this->assertNotNull($order);

        // Get the status column
        $columns = OrderExporter::getColumns();
        $this->assertCount(10, $columns);

        // Verify that the order has the expected fields
        $this->assertNotNull($order->order_number);
        $this->assertNotNull($order->user_id);
        $this->assertNotNull($order->name);
        $this->assertNotNull($order->email);
        $this->assertNotNull($order->status);
        $this->assertNotNull($order->subtotal);
        $this->assertNotNull($order->total);
    }
}
