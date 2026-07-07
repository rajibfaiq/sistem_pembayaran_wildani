<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\User;
use App\Services\FonnteWhatsappService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentPaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $siswaUser;

    private Student $student;

    private Bill $bill;

    protected function setUp(): void
    {
        parent::setUp();

        // Create student and associate user
        $this->student = Student::factory()->create([
            'nama' => 'John Doe',
            'kelas' => 'TK A',
            'nisn' => '1234567890',
        ]);

        $this->siswaUser = User::factory()->create([
            'role' => 'siswa',
            'student_id' => $this->student->id,
            'nisn' => $this->student->nisn,
        ]);

        $paymentType = PaymentType::factory()->create(['name' => 'SPP Bulanan', 'amount' => 150000]);

        $this->bill = Bill::factory()->create([
            'student_id' => $this->student->id,
            'payment_type_id' => $paymentType->id,
            'amount' => 150000,
            'status' => 'pending',
        ]);
    }

    public function test_siswa_can_request_payment_and_receive_fonnte_notification(): void
    {
        $this->mock(FonnteWhatsappService::class, function ($mock) {
            $mock->shouldReceive('buildBillMessage')->once()->andReturn('Mocked message');
            $mock->shouldReceive('send')
                ->once()
                ->with('081234567890', 'Mocked message')
                ->andReturn([
                    'success' => true,
                    'message' => 'Success',
                    'detail' => [],
                ]);
        });

        $response = $this->actingAs($this->siswaUser)->post(route('student.bills.pay', $this->bill), [
            'whatsapp_number' => '081234567890',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Instruksi pembayaran telah dikirim ke WhatsApp Anda! Silakan transfer dan unggah bukti transfer di bawah.');

        // Check if whatsapp_number is stored
        $this->bill->refresh();
        $this->assertEquals('081234567890', $this->bill->whatsapp_number);
    }

    public function test_siswa_can_upload_payment_proof(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('bukti_transfer.jpg');

        $response = $this->actingAs($this->siswaUser)->post(route('student.bills.upload-proof', $this->bill), [
            'payment_proof' => $file,
        ]);

        $response->assertStatus(302);

        $this->bill->refresh();
        $this->assertEquals('waiting_verification', $this->bill->status);
        $this->assertNotNull($this->bill->payment_proof);

        // Assert file exists in public storage
        Storage::disk('public')->assertExists($this->bill->payment_proof);
    }

    public function test_admin_can_verify_payment(): void
    {
        $this->mock(FonnteWhatsappService::class, function ($mock) {
            $mock->shouldReceive('send')
                ->once()
                ->with('081234567890', \Mockery::type('string'))
                ->andReturn([
                    'success' => true,
                    'message' => 'Success',
                    'detail' => [],
                ]);
        });

        $admin = User::factory()->create(['role' => 'admin']);

        // Put bill in waiting_verification state
        $this->bill->update([
            'status' => 'waiting_verification',
            'payment_proof' => 'payment-proofs/test.jpg',
            'whatsapp_number' => '081234567890',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.bills.verify', $this->bill));

        $response->assertStatus(302);

        $this->bill->refresh();
        $this->assertEquals('paid', $this->bill->status);
        $this->assertNotNull($this->bill->paid_at);
    }

    public function test_admin_can_reject_payment(): void
    {
        $this->mock(FonnteWhatsappService::class, function ($mock) {
            $mock->shouldReceive('send')
                ->once()
                ->with('081234567890', \Mockery::type('string'))
                ->andReturn([
                    'success' => true,
                    'message' => 'Success',
                    'detail' => [],
                ]);
        });

        $admin = User::factory()->create(['role' => 'admin']);

        // Put bill in waiting_verification state
        $this->bill->update([
            'status' => 'waiting_verification',
            'payment_proof' => 'payment-proofs/test.jpg',
            'whatsapp_number' => '081234567890',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.bills.reject', $this->bill), [
            'rejected_reason' => 'Gambar buram.',
        ]);

        $response->assertStatus(302);

        $this->bill->refresh();
        $this->assertEquals('rejected', $this->bill->status);
        $this->assertEquals('Gambar buram.', $this->bill->rejected_reason);
    }
}
