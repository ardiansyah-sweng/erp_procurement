<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use Faker\Factory as Faker;

class AddSupplierTest extends TestCase
{
    use RefreshDatabase;

    public function testAddSupplierAllInvalidData()
    {
        $supplierData = [
            'name' => '',
            'address' => '',
            'telephone' => ''
        ];

        $response = $this->postJson('/supplier/add', $supplierData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function testAddSupplierInvalidName()
    {
        $faker = Faker::create();

        $supplierData = [
            'name' => '',
            'address' => $faker->address,
            'telephone' => $faker->phoneNumber
        ];

        $response = $this->postJson('/supplier/add', $supplierData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function testAddSupplierValidData()
    {
        $faker = Faker::create();

        $supplierData = [
            'name' => $faker->company,
            'address' => $faker->address,
            'telephone' => $faker->phoneNumber
        ];

        $response = $this->postJson('/supplier/add', $supplierData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('suppliers', [
            'name' => $supplierData['name'],
            'address' => $supplierData['address'],
            'telephone' => $supplierData['telephone']
        ]);
    }

    public function testLastSupplierIdIsRetrieved()
    {
        // Menambahkan beberapa data supplier
        Supplier::factory()->count(3)->create();

        // Mengambil nilai ID terakhir dari tabel supplier
        $lastId = Supplier::latest()->value('id');

        // Memastikan nilai ID terakhir tidak null
        $this->assertNotNull($lastId);        

        // Memastikan nilai ID terakhir sesuai dengan yang diharapkan
        $this->assertEquals(3, $lastId);
    }
}
