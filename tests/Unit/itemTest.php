<?php

namespace Tests\Unit;

use App\Http\Controllers\ItemController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use App\Models\Item;


class itemTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_search_items()
    {
        // Create sample items
        Item::factory()->create(['item_id' => '123', 'name' => 'Item One']);
        Item::factory()->create(['item_id' => '124', 'name' => 'Item Two']);
        Item::factory()->create(['item_id' => '125', 'name' => 'Another Item']);

        // Search by item ID
        $response = $this->getJson('/search-items?query=123');
        $response->assertStatus(200)
            ->assertJsonFragment(['item_id' => '123', 'name' => 'Item One']);

        // Search by name
        $response = $this->getJson('/search-items?query=Item');
        $response->assertStatus(200)
            ->assertJsonFragment(['item_id' => '123', 'name' => 'Item One'])
            ->assertJsonFragment(['item_id' => '124', 'name' => 'Item Two'])
            ->assertJsonFragment(['item_id' => '125', 'name' => 'Another Item']);

        // Search with no results
        $response = $this->getJson('/search-items?query=NonExistentItem');
        $response->assertStatus(200)
            ->assertJsonMissing(['item_id' => '123'])
            ->assertJsonMissing(['item_id' => '124'])
            ->assertJsonMissing(['item_id' => '125']);
    }

    public function test_addItem_invalid_name()
    {
        $controller = new ItemController();

        $faker = Faker::create();

        $requestData = [
            'item_id' => $faker->unique()->regexify('ITEM[0-9]{4}'),
            'name' => '34',
            'category' => $faker->numberBetween(0, 3),
            'description' => $faker->sentence,
            'unit_of_measurement' => $faker->numberBetween(0, 6)
        ];

        $request = new Request($requestData);

        $response = $controller->addItem($request, $responseType = 'json');

        if ($responseType === 'json') {

            $this->assertEquals(422, $response->getStatusCode());
        } else {

            $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        }
    }

    public function test_addItem_success()
    {
        $controller = new ItemController();

        $faker = Faker::create();

        $requestData = [
            'item_id' => $faker->unique()->regexify('ITEM[0-9]{4}'),
            'name' => $faker->words(10, true),
            'category' => $faker->numberBetween(0, 3),
            'description' => $faker->sentence,
            'unit_of_measurement' => $faker->numberBetween(0, 6)
        ];

        $request = new Request($requestData);

        $response = $controller->addItem($request, 'json');

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertDatabaseHas('item', [
            'item_id' => $requestData['item_id'],
            'name' => $requestData['name'],
            'category' => $requestData['category'],
            'description' => $requestData['description'],
            'unit_of_measurement' => $requestData['unit_of_measurement']
        ]);
    }

    public function test_getAllItem_success()
    {

        Item::factory()->count(3)->create();

        $controller = new ItemController();

        $response = $controller->getItems($responseType = 'json');

        if ($responseType === 'json') {

            $this->assertEquals(200, $response->getStatusCode());
        } else {

            $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        }
    }

    public function test_getItemByid_success()
    {
        // Create sample items
        Item::factory()->create(['item_id' => '123', 'name' => 'Item One']);
        Item::factory()->create(['item_id' => '124', 'name' => 'Item Two']);
        Item::factory()->create(['item_id' => '125', 'name' => 'Another Item']);

        $controller = new ItemController();

        $response = $controller->searchItemsById('124', $responseType = 'json');

        if ($responseType === 'json') {

            $this->assertEquals(200, $response->getStatusCode());
        } else {

            $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        }
    }


}
