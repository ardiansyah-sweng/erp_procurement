<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'supplier_id' => $this->faker->unique()->regexify('SUPP[0-9]{4}'),
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber
        ];
    }
}
