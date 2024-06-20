<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Faker\Factory as Faker;

class SupplierFormTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testSupplierForm(): void
    {
        $this->browse(function (Browser $browser) {

            $faker = Faker::create();

            for ($i = 0; $i < 10; $i++){

                $record = [
                    'name' => $faker->company,
                    'address' => $faker->address,
                    'telephone' => $faker->phoneNumber
                ];

                $browser->visit('http://localhost:8000/supplier/form')
                        ->type('name', $record['name'])
                        ->type('address', $record['address'])
                        ->type('telephone', $record['telephone'])
                        ->press('Tambah Supplier')
                        ->pause(2000);
                        
                $browser->assertSee('Supplier berhasil ditambahkan!');
            }
        });

    }
}
