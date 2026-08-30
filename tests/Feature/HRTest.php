<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\HrLeave;
use App\Models\HrDocument;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HRTest extends TestCase
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
    public function test_guest_cannot_access_hr()
    {
        $response = $this->get('/hr');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_user_can_view_hr_page_with_seeded_employees()
    {
        $emp = Employee::create([
            'employee_id' => 'EMP-111',
            'name' => 'Sarah Instructor',
            'position' => 'Piano Instructor',
            'contract_status' => 'Permanent',
            'salary' => 15000000.00,
            'leave_balance' => 10,
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        $response = $this->actingAs($this->user)->get('/hr');
        
        $response->assertStatus(200);
        $response->assertSee('Sarah Instructor');
        $response->assertSee('Piano Instructor');
        $response->assertSee('Permanent');
        $response->assertSee('ID: EMP-111');
        $response->assertSee('Rp 15.000.000');
    }

    /** @test */
    public function test_user_can_add_employee()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post('/hr', [
            'name' => 'Ahmad Dani',
            'position' => 'Guitar Instructor',
            'contract_status' => 'Contract (6 Mo)',
            'salary' => 8000000.00,
            'leave_balance' => 12,
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseHas('employees', [
            'name' => 'Ahmad Dani',
            'position' => 'Guitar Instructor',
            'contract_status' => 'Contract (6 Mo)',
            'salary' => 8000000.00,
            'leave_balance' => 12,
        ]);
    }

    /** @test */
    public function test_user_can_update_employee()
    {
        Storage::fake('public');

        $emp = Employee::create([
            'employee_id' => 'EMP-222',
            'name' => 'Luthier Chang',
            'position' => 'Piano Specialist',
            'contract_status' => 'Permanent',
            'salary' => 12000000.00,
            'leave_balance' => 5,
            'avatar' => 'https://example.com/old_avatar.jpg',
        ]);

        $response = $this->actingAs($this->user)->put("/hr/{$emp->id}", [
            'name' => 'Chang Senior',
            'position' => 'Senior Piano Specialist',
            'contract_status' => 'Permanent',
            'salary' => 15000000.00,
            'leave_balance' => 8,
            'avatar' => UploadedFile::fake()->image('avatar_new.jpg'),
        ]);

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseHas('employees', [
            'id' => $emp->id,
            'name' => 'Chang Senior',
            'position' => 'Senior Piano Specialist',
            'salary' => 15000000.00,
            'leave_balance' => 8,
        ]);
    }

    /** @test */
    public function test_user_can_delete_employee()
    {
        $emp = Employee::create([
            'employee_id' => 'EMP-333',
            'name' => 'Temporary Employee',
            'position' => 'Intern Coordinator',
            'contract_status' => 'Contract (6 Mo)',
            'salary' => 3000000.00,
            'leave_balance' => 2,
        ]);

        $response = $this->actingAs($this->user)->delete("/hr/{$emp->id}");

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseMissing('employees', [
            'id' => $emp->id,
        ]);
    }

    /** @test */
    public function test_user_can_process_payroll()
    {
        Employee::create([
            'employee_id' => 'EMP-666',
            'name' => 'Salary Man',
            'position' => 'Manager',
            'contract_status' => 'Permanent',
            'salary' => 10000000.00,
            'leave_balance' => 12,
        ]);

        $response = $this->actingAs($this->user)->post('/hr/payroll');

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseHas('transactions', [
            'type' => 'expense',
            'amount' => 10000000.00,
            'category' => 'Operasional',
        ]);
    }

    /** @test */
    public function test_user_can_approve_leave()
    {
        $emp = Employee::create([
            'employee_id' => 'EMP-777',
            'name' => 'Cuti Person',
            'position' => 'Specialist',
            'contract_status' => 'Permanent',
            'salary' => 5000000.00,
            'leave_balance' => 12,
        ]);

        $leave = HrLeave::create([
            'employee_name' => 'Cuti Person',
            'leave_type' => 'Cuti Tahunan',
            'duration' => 3,
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->user)->post("/hr/leaves/{$leave->id}/approve");

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseHas('hr_leaves', [
            'id' => $leave->id,
            'status' => 'Disetujui',
        ]);

        $this->assertDatabaseHas('employees', [
            'id' => $emp->id,
            'leave_balance' => 9, // 12 - 3
        ]);
    }

    /** @test */
    public function test_user_can_reject_leave()
    {
        $leave = HrLeave::create([
            'employee_name' => 'Michael Chang',
            'leave_type' => 'Cuti Sakit',
            'duration' => 2,
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->user)->post("/hr/leaves/{$leave->id}/reject");

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseHas('hr_leaves', [
            'id' => $leave->id,
            'status' => 'Ditolak',
        ]);
    }

    /** @test */
    public function test_user_can_upload_contract_document()
    {
        $response = $this->actingAs($this->user)->post('/hr/documents', [
            'document_file' => UploadedFile::fake()->create('Kontrak_Test_2024.pdf', 300),
        ]);

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseHas('hr_documents', [
            'filename' => 'Kontrak_Test_2024.pdf',
        ]);
    }

    /** @test */
    public function test_user_can_delete_contract_document()
    {
        $doc = HrDocument::create([
            'filename' => 'Kontrak_Delete_2024.pdf',
            'file_path' => '/uploads/documents/Kontrak_Delete_2024.pdf',
            'file_size' => '300 KB',
        ]);

        $response = $this->actingAs($this->user)->delete("/hr/documents/{$doc->id}");

        $response->assertRedirect(route('hr'));
        $this->assertDatabaseMissing('hr_documents', [
            'id' => $doc->id,
        ]);
    }
}
