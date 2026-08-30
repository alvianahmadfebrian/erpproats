<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Inventory;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_user_can_view_dashboard_with_dynamic_stats()
    {
        Inventory::create([
            'item_id' => 'INS-999',
            'name' => 'Yamaha Keyboard',
            'category' => 'Keyboards',
            'stock' => 10,
            'price' => 5000000.00,
            'vendor' => 'Yamaha Corp',
        ]);

        Employee::create([
            'employee_id' => 'EMP-999',
            'name' => 'Dani Instructor',
            'position' => 'Guitar Instructor',
            'contract_status' => 'Permanent',
            'salary' => 8000000.00,
            'leave_balance' => 12,
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('10'); // Total stock
        $response->assertSee('1');  // Active employee count
    }
}
