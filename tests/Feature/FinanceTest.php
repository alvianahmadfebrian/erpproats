<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceTest extends TestCase
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
    public function test_guest_cannot_access_finance()
    {
        $response = $this->get('/finance');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_user_can_view_finance_page_with_seeded_transactions()
    {
        TransactionCategory::create(['name' => 'Kursus']);
        $trx = Transaction::create([
            'transaction_id' => 'TRX-11111',
            'type' => 'income',
            'description' => 'Test Course Piano Fee',
            'category' => 'Kursus',
            'amount' => 1500000.00,
            'date' => '2024-04-20',
        ]);

        $response = $this->actingAs($this->user)->get('/finance');
        
        $response->assertStatus(200);
        $response->assertSee('Test Course Piano Fee');
        $response->assertSee('TRX-11111');
        $response->assertSee('Kursus');
        $response->assertSee('Rp 1.500.000');
    }

    /** @test */
    public function test_user_can_record_income()
    {
        TransactionCategory::create(['name' => 'Kursus']);
        $response = $this->actingAs($this->user)->post('/finance', [
            'type' => 'income',
            'description' => 'Sarah Piano Tuition Fee',
            'category' => 'Kursus',
            'amount' => 1250000.00,
            'date' => '2024-04-19',
        ]);

        $response->assertRedirect(route('finance'));
        $this->assertDatabaseHas('transactions', [
            'type' => 'income',
            'description' => 'Sarah Piano Tuition Fee',
            'category' => 'Kursus',
            'amount' => 1250000.00,
        ]);
    }

    /** @test */
    public function test_user_can_record_expense()
    {
        TransactionCategory::create(['name' => 'Aksesori']);
        $response = $this->actingAs($this->user)->post('/finance', [
            'type' => 'expense',
            'description' => 'Ernie Ball String purchase',
            'category' => 'Aksesori',
            'amount' => 3500000.00,
            'date' => '2024-04-18',
        ]);

        $response->assertRedirect(route('finance'));
        $this->assertDatabaseHas('transactions', [
            'type' => 'expense',
            'description' => 'Ernie Ball String purchase',
            'category' => 'Aksesori',
            'amount' => 3500000.00,
        ]);
    }

    /** @test */
    public function test_user_can_filter_transactions_by_type()
    {
        TransactionCategory::create(['name' => 'Kursus']);
        TransactionCategory::create(['name' => 'Operasional']);

        Transaction::create([
            'transaction_id' => 'TRX-201',
            'type' => 'income',
            'description' => 'Income Item',
            'category' => 'Kursus',
            'amount' => 1000.00,
            'date' => '2024-04-20',
        ]);
        Transaction::create([
            'transaction_id' => 'TRX-202',
            'type' => 'expense',
            'description' => 'Expense Item',
            'category' => 'Operasional',
            'amount' => 500.00,
            'date' => '2024-04-20',
        ]);

        $response = $this->actingAs($this->user)->get('/finance?type=income');

        $response->assertSee('Income Item');
        $response->assertDontSee('Expense Item');
    }

    /** @test */
    public function test_user_can_search_transactions()
    {
        TransactionCategory::create(['name' => 'Kursus']);
        TransactionCategory::create(['name' => 'Operasional']);

        Transaction::create([
            'transaction_id' => 'TRX-301',
            'type' => 'income',
            'description' => 'Unique Key Search',
            'category' => 'Kursus',
            'amount' => 1000.00,
            'date' => '2024-04-20',
        ]);
        Transaction::create([
            'transaction_id' => 'TRX-302',
            'type' => 'expense',
            'description' => 'Common Rental Fee',
            'category' => 'Operasional',
            'amount' => 500.00,
            'date' => '2024-04-20',
        ]);

        $response = $this->actingAs($this->user)->get('/finance?search=Unique');

        $response->assertSee('Unique Key Search');
        $response->assertDontSee('Common Rental Fee');
    }

    /** @test */
    public function test_user_can_export_transactions_to_csv()
    {
        TransactionCategory::create(['name' => 'Kursus']);
        Transaction::create([
            'transaction_id' => 'TRX-401',
            'type' => 'income',
            'description' => 'Exportable Course Payment',
            'category' => 'Kursus',
            'amount' => 2000.00,
            'date' => '2024-04-20',
        ]);

        $response = $this->actingAs($this->user)->get('/finance/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="finance_export.csv"');
        $this->assertStringContainsString('Exportable Course Payment', $response->streamedContent());
    }

    /** @test */
    public function test_user_can_create_category()
    {
        $response = $this->actingAs($this->user)->post('/finance/categories', [
            'name' => 'Konser',
        ]);

        $response->assertRedirect(route('finance'));
        $this->assertDatabaseHas('transaction_categories', [
            'name' => 'Konser',
        ]);
    }

    /** @test */
    public function test_user_can_rename_category()
    {
        $cat = TransactionCategory::create(['name' => 'Konser']);
        
        $response = $this->actingAs($this->user)->put("/finance/categories/{$cat->id}", [
            'name' => 'Sponsorship',
        ]);

        $response->assertRedirect(route('finance'));
        $this->assertDatabaseHas('transaction_categories', [
            'id' => $cat->id,
            'name' => 'Sponsorship',
        ]);
    }

    /** @test */
    public function test_user_can_delete_unused_category()
    {
        $cat = TransactionCategory::create(['name' => 'Konser']);
        
        $response = $this->actingAs($this->user)->delete("/finance/categories/{$cat->id}");

        $response->assertRedirect(route('finance'));
        $this->assertDatabaseMissing('transaction_categories', [
            'id' => $cat->id,
        ]);
    }

    /** @test */
    public function test_user_cannot_delete_used_category()
    {
        $cat = TransactionCategory::create(['name' => 'Kursus']);
        Transaction::create([
            'transaction_id' => 'TRX-501',
            'type' => 'income',
            'description' => 'Sarah course payment',
            'category' => 'Kursus',
            'amount' => 1250000.00,
            'date' => '2024-04-19',
        ]);

        $response = $this->actingAs($this->user)->delete("/finance/categories/{$cat->id}");

        $response->assertSessionHasErrors('category');
        $this->assertDatabaseHas('transaction_categories', [
            'id' => $cat->id,
            'name' => 'Kursus',
        ]);
    }
}
