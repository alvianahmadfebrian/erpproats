<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorTest extends TestCase
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
    public function test_guest_cannot_access_vendors()
    {
        $response = $this->get('/vendors');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_user_can_view_vendors_page_with_seeded_vendors()
    {
        $v = Vendor::create([
            'name' => 'Yamaha Test',
            'category' => 'Alat Musik',
            'contact' => 'sales@yamaha.co.id',
            'address' => 'Jakarta',
            'status' => 'Aktif',
            'procurement_cost' => 150000000.00,
        ]);

        $response = $this->actingAs($this->user)->get('/vendors');
        
        $response->assertStatus(200);
        $response->assertSee('Yamaha Test');
        $response->assertSee('Alat Musik');
        $response->assertSee('sales@yamaha.co.id');
        $response->assertSee('Aktif');
        $response->assertSee('Rp 150.000.000');
    }

    /** @test */
    public function test_user_can_add_vendor()
    {
        $response = $this->actingAs($this->user)->post('/vendors', [
            'name' => 'Gibsons Supply',
            'category' => 'Alat Musik',
            'contact' => 'info@gibson.com',
            'address' => 'Bandung',
            'status' => 'Aktif',
            'procurement_cost' => 20000000.00,
        ]);

        $response->assertRedirect(route('vendors'));
        $this->assertDatabaseHas('vendors', [
            'name' => 'Gibsons Supply',
            'category' => 'Alat Musik',
            'contact' => 'info@gibson.com',
            'procurement_cost' => 20000000.00,
        ]);
    }

    /** @test */
    public function test_user_can_update_vendor()
    {
        $v = Vendor::create([
            'name' => 'Original Luthier',
            'category' => 'Service',
            'contact' => 'luthier@gmail.com',
            'address' => 'Surabaya',
            'status' => 'Aktif',
            'procurement_cost' => 5000000.00,
        ]);

        $response = $this->actingAs($this->user)->put("/vendors/{$v->id}", [
            'name' => 'Luthier Pro Bandung',
            'category' => 'Service',
            'contact' => 'luthier@gmail.com',
            'address' => 'Bandung',
            'status' => 'Suspen',
            'procurement_cost' => 12000000.00,
        ]);

        $response->assertRedirect(route('vendors'));
        $this->assertDatabaseHas('vendors', [
            'id' => $v->id,
            'name' => 'Luthier Pro Bandung',
            'status' => 'Suspen',
            'address' => 'Bandung',
            'procurement_cost' => 12000000.00,
        ]);
    }

    /** @test */
    public function test_user_can_delete_vendor()
    {
        $v = Vendor::create([
            'name' => 'Temporary Vendor',
            'category' => 'Aksesori',
            'contact' => 'temp@gmail.com',
            'address' => 'Jakarta',
            'status' => 'Aktif',
            'procurement_cost' => 1000000.00,
        ]);

        $response = $this->actingAs($this->user)->delete("/vendors/{$v->id}");

        $response->assertRedirect(route('vendors'));
        $this->assertDatabaseMissing('vendors', [
            'id' => $v->id,
        ]);
    }

    /** @test */
    public function test_user_can_filter_vendors()
    {
        Vendor::create([
            'name' => 'Filter Instrument',
            'category' => 'Alat Musik',
            'contact' => 'music@test.com',
            'address' => 'Jakarta',
            'status' => 'Aktif',
            'procurement_cost' => 1000000.00,
        ]);
        Vendor::create([
            'name' => 'Filter Service',
            'category' => 'Service',
            'contact' => 'service@test.com',
            'address' => 'Bandung',
            'status' => 'Suspen',
            'procurement_cost' => 500000.00,
        ]);

        $response = $this->actingAs($this->user)->get('/vendors?category=Alat+Musik');

        $response->assertSee('Filter Instrument');
        $response->assertDontSee('Filter Service');
    }

    /** @test */
    public function test_user_can_search_vendors()
    {
        Vendor::create([
            'name' => 'Search Match Special',
            'category' => 'Alat Musik',
            'contact' => 'special@test.com',
            'address' => 'Jakarta',
            'status' => 'Aktif',
            'procurement_cost' => 1000000.00,
        ]);
        Vendor::create([
            'name' => 'Normal Vendor',
            'category' => 'Alat Musik',
            'contact' => 'normal@test.com',
            'address' => 'Jakarta',
            'status' => 'Aktif',
            'procurement_cost' => 1000000.00,
        ]);

        $response = $this->actingAs($this->user)->get('/vendors?search=Special');

        $response->assertSee('Search Match Special');
        $response->assertDontSee('Normal Vendor');
    }

    /** @test */
    public function test_user_can_export_vendors_to_csv()
    {
        Vendor::create([
            'name' => 'Exportable Vendor Ltd',
            'category' => 'Alat Musik',
            'contact' => 'export@test.com',
            'address' => 'Surabaya',
            'status' => 'Aktif',
            'procurement_cost' => 1000000.00,
        ]);

        $response = $this->actingAs($this->user)->get('/vendors/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="vendors_export.csv"');
        $this->assertStringContainsString('Exportable Vendor Ltd', $response->streamedContent());
    }
}
