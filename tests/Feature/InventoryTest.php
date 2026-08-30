<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'mastervi',
            'email' => 'mastervi@proatsmusic.com',
            'password' => bcrypt('pisangkeju'),
        ]);
    }

    /** @test */
    public function test_guest_cannot_access_inventory()
    {
        $response = $this->get('/inventory');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_user_can_view_inventory_page_with_seeded_items()
    {
        $item = Inventory::create([
            'item_id' => 'INS-999',
            'name' => 'Yamaha Test Piano',
            'category' => 'Pianos',
            'stock' => 10,
            'price' => 5000.00,
            'vendor' => 'Yamaha Corp',
        ]);

        $response = $this->actingAs($this->user)->get('/inventory');
        
        $response->assertStatus(200);
        $response->assertSee('Yamaha Test Piano');
        $response->assertSee('INS-999');
        $response->assertSee('Pianos');
        $response->assertSee('Optimal');
    }

    /** @test */
    public function test_user_can_add_inventory_item()
    {
        $response = $this->actingAs($this->user)->post('/inventory', [
            'name' => 'Gibson Les Paul',
            'category' => 'Guitars',
            'stock' => 4,
            'price' => 2400.00,
            'vendor' => 'Gibson Brands',
        ]);

        $response->assertRedirect(route('inventory'));
        $this->assertDatabaseHas('inventories', [
            'name' => 'Gibson Les Paul',
            'category' => 'Guitars',
            'stock' => 4,
            'price' => 2400.00,
            'vendor' => 'Gibson Brands',
        ]);
    }

    /** @test */
    public function test_user_can_update_inventory_item()
    {
        $item = Inventory::create([
            'item_id' => 'INS-101',
            'name' => 'Stradivarius Violin',
            'category' => 'Strings',
            'stock' => 1,
            'price' => 9999.00,
            'vendor' => 'Stradivarius Corp',
        ]);

        $response = $this->actingAs($this->user)->put("/inventory/{$item->id}", [
            'name' => 'Stradivarius Violin Deluxe',
            'category' => 'Strings',
            'stock' => 3,
            'price' => 12500.00,
            'vendor' => 'Stradivarius Corp',
        ]);

        $response->assertRedirect(route('inventory'));
        $this->assertDatabaseHas('inventories', [
            'id' => $item->id,
            'name' => 'Stradivarius Violin Deluxe',
            'stock' => 3,
            'price' => 12500.00,
        ]);
    }

    /** @test */
    public function test_user_can_delete_inventory_item()
    {
        $item = Inventory::create([
            'item_id' => 'INS-102',
            'name' => 'Yamaha Flute',
            'category' => 'Keyboards',
            'stock' => 5,
            'price' => 350.00,
            'vendor' => 'Yamaha Corp',
        ]);

        $response = $this->actingAs($this->user)->delete("/inventory/{$item->id}");

        $response->assertRedirect(route('inventory'));
        $this->assertDatabaseMissing('inventories', [
            'id' => $item->id,
        ]);
    }

    /** @test */
    public function test_user_can_filter_by_category()
    {
        Inventory::create([
            'item_id' => 'INS-201',
            'name' => 'Seeded Piano',
            'category' => 'Pianos',
            'stock' => 10,
            'price' => 4500.00,
            'vendor' => 'Yamaha',
        ]);
        Inventory::create([
            'item_id' => 'INS-202',
            'name' => 'Seeded Guitar',
            'category' => 'Guitars',
            'stock' => 5,
            'price' => 800.00,
            'vendor' => 'Fender',
        ]);

        $response = $this->actingAs($this->user)->get('/inventory?categories[]=Pianos');

        $response->assertSee('Seeded Piano');
        $response->assertDontSee('Seeded Guitar');
    }

    /** @test */
    public function test_user_can_search_items()
    {
        Inventory::create([
            'item_id' => 'INS-301',
            'name' => 'Unique Piano Match',
            'category' => 'Pianos',
            'stock' => 10,
            'price' => 4500.00,
            'vendor' => 'Yamaha',
        ]);
        Inventory::create([
            'item_id' => 'INS-302',
            'name' => 'Common Keyboard',
            'category' => 'Keyboards',
            'stock' => 5,
            'price' => 800.00,
            'vendor' => 'Roland',
        ]);

        $response = $this->actingAs($this->user)->get('/inventory?search=Unique');

        $response->assertSee('Unique Piano Match');
        $response->assertDontSee('Common Keyboard');
    }

    /** @test */
    public function test_user_can_export_inventory_to_csv()
    {
        Inventory::create([
            'item_id' => 'INS-401',
            'name' => 'Exportable Violin',
            'category' => 'Strings',
            'stock' => 4,
            'price' => 1500.00,
            'vendor' => 'Strings Inc',
        ]);

        $response = $this->actingAs($this->user)->get('/inventory/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="inventory_export.csv"');
        $this->assertStringContainsString('Exportable Violin', $response->streamedContent());
    }
}
