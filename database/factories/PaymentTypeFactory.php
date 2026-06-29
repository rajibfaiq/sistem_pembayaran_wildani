<?php

namespace Database\Factories;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PaymentType>
 */
class PaymentTypeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'SPP Bulanan',
            'Uang Gedung',
            'Pembelian Seragam',
            'Uang Kegiatan',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'icon' => 'credit-card',
            'amount' => fake()->randomElement([150000, 250000, 500000, 750000, 1000000]),
        ];
    }
}
