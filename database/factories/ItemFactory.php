<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'item_id' => $this->faker->unique()->regexify('ITEM[0-9]{4}'),
            'name' => $this->faker->word,
            'category' => $this->faker->numberBetween(0, 3),
            'description' => $this->faker->sentence,
            'unit_of_measurement' => $this->faker->numberBetween(0, 6)
        ];
    }
}
