<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Faker\Factory as Faker;

class ItemFormTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testItemForm(): void
    {
        $this->browse(function (Browser $browser) {

            $faker = Faker::create();

            for ($i = 0; $i < 10; $i++) {
                $randomCategoryPrefix = $faker->randomElement(['KOMP', 'RAWM', 'SEMI', 'PROD']);
                $record = [
                    'item_id' => $randomCategoryPrefix.$faker->unique()->randomNumber(4),
                    'name' => $faker->word(10, true),
                    'category' => $faker->numberBetween(0, 3),
                    'unit_of_measurement' => $faker->numberBetween(0, 6),
                    'description' => $faker->sentence()
                ];

                $browser->visit('http://localhost:8000/items')
                        ->press('New Item')
                        ->pause(400)
                        ->type('item_id', $record['item_id'])
                        ->type('name', $record['name'])
                        ->select('category', $record['category'])
                        ->select('unit_of_measurement', $record['unit_of_measurement'])
                        ->type('description', $record['description'])
                        ->press('Save Item');

                $browser->waitForDialog(10)
                        ->acceptDialog();
                
                $browser->pause(1500);

                $browser->assertSee('Item berhasil ditambahkan!');                        
            }

        });
    }
}
