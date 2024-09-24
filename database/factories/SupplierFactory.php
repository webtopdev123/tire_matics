<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_name' => $this->faker->company,
            'supplier_email' => $this->faker->unique()->safeEmail,
            'supplier_contact' => $this->faker->phoneNumber,
            'supplier_status' => $this->faker->numberBetween(0, 1), // Assuming status is 0 or 1
            'supplier_status_datetime' => $this->faker->dateTime,
            'merchant_id' => 1, // Assuming you have merchant IDs between 1 and 100
        ];
    }
}
