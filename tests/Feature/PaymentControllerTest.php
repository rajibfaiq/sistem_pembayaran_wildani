<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\PaymentType;
use App\Models\Student;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_dashboard_displays_payment_types(): void
    {
        PaymentType::factory()->create(['name' => 'SPP Bulanan', 'slug' => 'spp-bulanan']);
        PaymentType::factory()->create(['name' => 'Uang Gedung', 'slug' => 'uang-gedung']);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('SPP Bulanan');
        $response->assertSee('Uang Gedung');
        $response->assertSee('WILDANI');
    }

    public function test_payment_form_displays_for_valid_slug(): void
    {
        $paymentType = PaymentType::factory()->create(['name' => 'SPP Bulanan', 'slug' => 'spp-bulanan']);

        $response = $this->get(route('payment.form', 'spp-bulanan'));

        $response->assertStatus(200);
        $response->assertSee('SPP Bulanan');
        $response->assertSee('NISN');
    }

    public function test_payment_form_returns_404_for_invalid_slug(): void
    {
        $response = $this->get(route('payment.form', 'non-existent'));

        $response->assertStatus(404);
    }

    public function test_search_student_returns_data_for_valid_nisn(): void
    {
        $paymentType = PaymentType::factory()->create(['name' => 'SPP Bulanan', 'slug' => 'spp-bulanan']);
        $student = Student::factory()->create(['nisn' => '0051234001', 'nama' => 'Aisyah Putri', 'kelas' => 'TK A']);
        Bill::factory()->pending()->create([
            'student_id' => $student->id,
            'payment_type_id' => $paymentType->id,
            'amount' => 350000,
        ]);

        $response = $this->postJson(route('student.search'), ['nisn' => '0051234001']);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'nisn' => '0051234001',
                    'nama' => 'Aisyah Putri',
                    'kelas' => 'TK A',
                ],
            ]);
    }

    public function test_search_student_returns_404_for_invalid_nisn(): void
    {
        $response = $this->postJson(route('student.search'), ['nisn' => '9999999999']);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_search_student_validates_nisn_required(): void
    {
        $response = $this->postJson(route('student.search'), ['nisn' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('nisn');
    }

    public function test_create_bill_with_valid_data(): void
    {
        $this->mock(\App\Services\FonnteWhatsappService::class, function ($mock) {
            $mock->shouldReceive('buildBillMessage')->once()->andReturn('Mocked message');
            $mock->shouldReceive('send')
                ->once()
                ->with('081234567890', 'Mocked message')
                ->andReturn([
                    'success' => true,
                    'message' => 'Notifikasi WhatsApp berhasil dikirim.',
                    'detail' => [],
                ]);
        });

        $paymentType = PaymentType::factory()->create(['amount' => 350000]);
        $student = Student::factory()->create();

        $response = $this->postJson(route('payment.create'), [
            'student_id' => $student->id,
            'payment_type_id' => $paymentType->id,
            'phone' => '081234567890',
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tagihan berhasil dibuat! Notifikasi WhatsApp berhasil dikirim.',
            ]);

        $this->assertDatabaseHas('bills', [
            'student_id' => $student->id,
            'payment_type_id' => $paymentType->id,
            'amount' => 350000,
            'status' => 'pending',
        ]);
    }

    public function test_create_bill_validates_required_fields(): void
    {
        $response = $this->postJson(route('payment.create'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_id', 'payment_type_id', 'phone', 'payment_method']);
    }

    public function test_search_student_includes_total_tagihan(): void
    {
        $paymentType = PaymentType::factory()->create();
        $student = Student::factory()->create(['nisn' => '0051234099']);

        Bill::factory()->pending()->create([
            'student_id' => $student->id,
            'payment_type_id' => $paymentType->id,
            'amount' => 350000,
        ]);

        Bill::factory()->pending()->create([
            'student_id' => $student->id,
            'payment_type_id' => $paymentType->id,
            'amount' => 200000,
        ]);

        $response = $this->postJson(route('student.search'), ['nisn' => '0051234099']);

        $response->assertStatus(200)
            ->assertJsonPath('data.total_tagihan', 550000);
    }
}
