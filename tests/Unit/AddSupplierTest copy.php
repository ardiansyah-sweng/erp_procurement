<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class AddSupplierTest extends TestCase
{
    use RefreshDatabase;

    public function testAddSupplierInvalidData()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => 'adsfadfasdf3', // Invalid name (less than 3 characters and contains numbers)
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber,
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testAddSupplierInvalidAddress()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->company,
            'address' => '123', // Invalid address (less than 5 characters)
            'telephone' => $faker->phoneNumber
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        // Assert that there are validation errors
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testAddSupplierInvalidTelephone()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => '123' // Invalid telephone (less than 5 characters)
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        // Assert that there are validation errors
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testAddSupplierPicInvalid()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber,
            'supplier_pic' => '$faker->name',
            'supplier_telephone' => $faker->phoneNumber
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        // Assert that there are validation errors
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testAddSupplierPicValidData()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber,
            
            'pic_name' => $faker->name,
            'pic_telephone' => $faker->phoneNumber,
            'pic_email' => $faker->email,
            'pic_since' => $faker->date
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        $this->assertDatabaseHas('supplier', [
            'name' => $requestData['name'],
            'address' => $requestData['address'],
            'telephone' => $requestData['telephone']
        ]);

        $this->assertDatabaseHas('supplier_pic', [
            'pic_name' => $requestData['pic_name'],
            'pic_telephone' => $requestData['pic_telephone'],
            'pic_email' => $requestData['pic_email'],
            'pic_since' => $requestData['pic_since']
        ]);

        $this->assertTrue($response->getData()->success);
    }

    public function testAddSupplierValidData()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        $this->assertDatabaseHas('supplier', [
            'name' => $requestData['name'],
            'address' => $requestData['address'],
            'telephone' => $requestData['telephone']
        ]);

        $this->assertTrue($response->getData()->success);
    }
    
}
