<?php

namespace Database\Factories;

use App\Models\SupplierPic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierPic>
 */
class SupplierPicFactory extends Factory
{

    protected $model = SupplierPic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pic_name' => $this->faker->name,
            'pic_telephone' => $this->faker->phoneNumber,
            'pic_email' => $this->faker->email,
            'pic_assignment_date' => $this->faker->date
        ];
    }
}
