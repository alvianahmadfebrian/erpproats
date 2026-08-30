<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login page is rendered successfully.
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Proats Music');
        $response->assertSee('Sign In');
    }

    /**
     * Test successful authentication using username/name.
     */
    public function test_users_can_authenticate_using_username(): void
    {
        $user = User::factory()->create([
            'name' => 'mastervi',
            'email' => 'mastervi@proatsmusic.com',
            'password' => bcrypt('pisangkeju'),
        ]);

        $response = $this->post('/login', [
            'email' => 'mastervi',
            'password' => 'pisangkeju',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test successful authentication using email.
     */
    public function test_users_can_authenticate_using_email(): void
    {
        $user = User::factory()->create([
            'name' => 'mastervi',
            'email' => 'mastervi@proatsmusic.com',
            'password' => bcrypt('pisangkeju'),
        ]);

        $response = $this->post('/login', [
            'email' => 'mastervi@proatsmusic.com',
            'password' => 'pisangkeju',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test users cannot authenticate with invalid credentials.
     */
    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        User::factory()->create([
            'name' => 'mastervi',
            'email' => 'mastervi@proatsmusic.com',
            'password' => bcrypt('pisangkeju'),
        ]);

        $response = $this->post('/login', [
            'email' => 'mastervi',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test users can log out.
     */
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    /**
     * Test guests are redirected from inventory page to login page.
     */
    public function test_guests_cannot_access_inventory(): void
    {
        $response = $this->get('/inventory');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated users can access the inventory page.
     */
    public function test_authenticated_users_can_access_inventory(): void
    {
        $user = User::factory()->create();

        \App\Models\Inventory::create([
            'item_id' => 'INS-001',
            'name' => 'Yamaha U1 Upright Piano',
            'category' => 'Pianos',
            'stock' => 12,
            'price' => 8500.00,
            'vendor' => 'Yamaha Corp',
        ]);

        $response = $this->actingAs($user)->get('/inventory');

        $response->assertStatus(200);
        $response->assertSee('Inventory Management');
        $response->assertSee('INS-001');
    }

    /**
     * Test guests are redirected from finance page to login page.
     */
    public function test_guests_cannot_access_finance(): void
    {
        $response = $this->get('/finance');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated users can access the finance page.
     */
    public function test_authenticated_users_can_access_finance(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/finance');

        $response->assertStatus(200);
        $response->assertSee('Keuangan');
        $response->assertSee('Riwayat Transaksi');
    }

    /**
     * Test guests are redirected from vendors page to login page.
     */
    public function test_guests_cannot_access_vendors(): void
    {
        $response = $this->get('/vendors');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated users can access the vendors page.
     */
    public function test_authenticated_users_can_access_vendors(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/vendors');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Vendor');
        $response->assertSee('Yamaha Music Indonesia');
    }

    /**
     * Test guests are redirected from HR Management page to login page.
     */
    public function test_guests_cannot_access_hr(): void
    {
        $response = $this->get('/hr');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated users can access the HR Management page.
     */
    public function test_authenticated_users_can_access_hr(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/hr');

        $response->assertStatus(200);
        $response->assertSee('HR Management');
        $response->assertSee('Daftar Karyawan');
    }
}
