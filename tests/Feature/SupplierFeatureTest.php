<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\SupplierPic;

class SupplierFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_add_supplier_without_supplier_pic()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->company,
            'address' => $faker->address,
            'telephone' => $faker->phoneNumber
        ];

        $response = $this->postJson('/supplier/add', $requestData);

        // $response = $this->get('/');

        $response->assertStatus(201);
    }
}
