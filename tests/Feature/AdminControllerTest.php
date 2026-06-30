<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Masuk ke WILDANI');
        $response->assertSee('NISN');
        $response->assertSee('Password');
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@wildani.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'login_id' => 'admin@wildani.sch.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.payment-report'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_cannot_login_with_incorrect_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@wildani.sch.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'login_id' => 'admin@wildani.sch.id',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('login_id');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_cannot_access_report(): void
    {
        $response = $this->get(route('admin.payment-report'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_access_report_and_logout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('admin.payment-report'));

        $response->assertStatus(200);
        $response->assertSee('Laporan');
        $response->assertSee('Pembayaran');

        $logoutResponse = $this->actingAs($user)->post(route('logout'));
        $logoutResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_student_user_cannot_access_admin_report(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get(route('admin.payment-report'));

        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_report_shows_student_payments_correctly(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $paymentType = PaymentType::factory()->create(['name' => 'SPP Bulanan']);

        // Paid student
        $paidStudent = Student::factory()->create(['nama' => 'Budi Sudarsono', 'kelas' => 'TK A']);
        Bill::factory()->create([
            'student_id' => $paidStudent->id,
            'payment_type_id' => $paymentType->id,
            'status' => 'paid',
            'amount' => 300000,
            'paid_at' => now(),
        ]);

        // Unpaid student
        $unpaidStudent = Student::factory()->create(['nama' => 'Ani Wijaya', 'kelas' => 'TK B']);
        Bill::factory()->create([
            'student_id' => $unpaidStudent->id,
            'payment_type_id' => $paymentType->id,
            'status' => 'pending',
            'amount' => 300000,
        ]);

        // Access as authenticated admin
        $response = $this->actingAs($adminUser)->get(route('admin.payment-report'));

        $response->assertStatus(200);

        // Under initial unpaid tab view, we should see Ani Wijaya
        $response->assertSee('Ani Wijaya');
        $response->assertSee('TK B');

        // Budi Sudarsono (paid) should also be present in the view's HTML context for the paid tab
        $response->assertSee('Budi Sudarsono');
        $response->assertSee('TK A');
    }
}
