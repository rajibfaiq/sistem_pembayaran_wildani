<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\PaymentType;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bill>
 */
class BillFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'payment_type_id' => PaymentType::factory(),
            'amount' => fake()->randomElement([150000, 250000, 500000, 750000]),
            'status' => fake()->randomElement(['pending', 'paid', 'overdue']),
            'due_date' => fake()->dateTimeBetween('now', '+3 months'),
            'paid_at' => null,
        ];
    }

    /**
     * Indicate the bill has been paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate the bill is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate the bill is waiting verification.
     */
    public function waitingVerification(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'waiting_verification',
            'payment_proof' => 'payment-proofs/sample.jpg',
            'whatsapp_number' => '08123456789',
        ]);
    }

    /**
     * Indicate the bill was rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'rejected',
            'rejected_reason' => 'Bukti bayar tidak terbaca.',
            'whatsapp_number' => '08123456789',
        ]);
    }
}
