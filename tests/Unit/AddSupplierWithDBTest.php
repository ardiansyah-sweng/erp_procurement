<?php

namespace Tests\Unit;

use App\Http\Controllers\SupplierController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use App\Models\SupplierPic;
use Faker\Factory as Faker;
use Illuminate\Http\Request;


class AddSupplierWithDBTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_getZeroSupplierID_success()
    {
        $controller = new SupplierController();

        $response = $controller->getSupplierIDValue();

        $this->assertEquals(0, $response);
    }

    public function test_addSupplier_getLastID_success()
    {

        $maxSupplierID = config('supplier.max_supplier_id');
        $maxEntries = 10;

        Supplier::factory()->count($maxEntries)->create();

        $controller = new SupplierController();

        if ($maxEntries != $maxSupplierID) {

            $response = $controller->getSupplierIDValue();

            $this->assertEquals($maxEntries, $response);
        }

        // supplier ID value reached max limit supplier id digit in database
        if ($maxEntries == $maxSupplierID) {

            $faker = Faker::create();

            $requestData = [
                'name' => $faker->company,
                'address' => $faker->streetAddress,
                'telephone' => $faker->phoneNumber
            ];

            $request = new Request($requestData);

            $response = $controller->addSupplier($request);

            $this->assertEquals($maxEntries, $response);
        }
    }

    public function test_AddSupplier_data_valid_table_empty()
    {
        $this->refreshDatabase();

        $faker = Faker::create();

        $controller = new SupplierController();

        $requestSupplierData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber
        ];

        $request = new Request($requestSupplierData);

        $response = $controller->addSupplier($request);

        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $lastId = $lastSupplier ? $lastSupplier->supplier_id : 0;

        $this->assertDatabaseHas('supplier', [
            'supplier_id' => $lastId,
            'name' => $requestSupplierData['name'],
            'address' => $requestSupplierData['address'],
            'telephone' => $requestSupplierData['telephone']
        ]);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        // $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_AddSupplier_data_valid_table_dataIsExist()
    {
        $faker = Faker::create();

        Supplier::factory()->count(3)->create();

        $controller = new SupplierController();

        $requestSupplierData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber
        ];

        $request = new Request($requestSupplierData);

        $response = $controller->addSupplier($request);

        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $lastId = $lastSupplier ? $lastSupplier->supplier_id : 0;

        $this->assertDatabaseHas('supplier', [
            'supplier_id' => $lastId,
            'name' => $requestSupplierData['name'],
            'address' => $requestSupplierData['address'],
            'telephone' => $requestSupplierData['telephone']
        ]);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        //$this->assertEquals(201, $response->getStatusCode());
    }

    public function test_AddSupplierWithPic_data_isEmpty_valid_data()
    {
        $this->refreshDatabase();

        $faker = Faker::create();

        $controller = new SupplierController();

        $requestSupplierPicData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber,

            'pic_name' => $faker->name,
            'pic_telephone' => $faker->phoneNumber,
            'pic_email' => $faker->email,
            'pic_assignment_date' => $faker->date
        ];

        $request = new Request($requestSupplierPicData);

        $response = $controller->addSupplier($request, $responseType = 'web');

        $this->assertDatabaseHas('supplier_pic', [
            'supplier_id' => 'SUPP0001',
            'pic_name' => $requestSupplierPicData['pic_name'],
            'pic_telephone' => $requestSupplierPicData['pic_telephone'],
            'pic_email' => $requestSupplierPicData['pic_email'],
            'pic_assignment_date' => $requestSupplierPicData['pic_assignment_date']
        ]);
        
        if ($responseType === 'json') {

            $this->assertEquals(201, $response->getStatusCode());
        
        } else {

            $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        }
    }

    public function test_AddSupplierWithPic_Data_isExist_valid_data()
    {
        $this->refreshDatabase();

        $faker = Faker::create();

        Supplier::factory()->count(5)->create()->each(function ($supplier) {
            SupplierPic::factory()->create(['supplier_id' => $supplier->supplier_id]);
        });

        $controller = new SupplierController();

        $requestSupplierPicData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => $faker->phoneNumber,

            'pic_name' => $faker->name,
            'pic_telephone' => $faker->phoneNumber,
            'pic_email' => $faker->email,
            'pic_assignment_date' => $faker->date
        ];

        $request = new Request($requestSupplierPicData);

        $response = $controller->addSupplier($request);

        $lastSupplierPic = SupplierPic::orderBy('id', 'desc')->first();
        $lastSupplierPicId = $lastSupplierPic ? $lastSupplierPic->supplier_id : 1;

        $this->assertDatabaseHas('supplier_pic', [
            'supplier_id' => $lastSupplierPicId,
            'pic_name' => $requestSupplierPicData['pic_name'],
            'pic_telephone' => $requestSupplierPicData['pic_telephone'],
            'pic_email' => $requestSupplierPicData['pic_email'],
            'pic_assignment_date' => $requestSupplierPicData['pic_assignment_date']
        ]);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(url('/supplier/form'), $response->getTargetUrl());
        //$this->assertEquals(201, $response->getStatusCode());
    }


    public function test_AddSupplierInvalidTelephone()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'telephone' => '123'
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        // Assert that there are validation errors
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function test_AddSupplierPic_invalidEmail()
    {
        $faker = Faker::create();

        $requestData = [
            'pic_name' => $faker->company,
            'pic_telephone' => $faker->streetAddress,
            'pic_email' => '123@xxy.!',
            'pic_assignment_date' => $faker->date
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplier($request);

        // Assert that there are validation errors
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function test_addSupplierPic_invalid_name()
    {
        $faker = Faker::create();

        Supplier::factory()->count(10)->create();

        $suppliers = Supplier::all();
        $firstSupplier = $suppliers[8];

        // $controller = new SupplierController();

        $requestData = [
            'supplier_id' => $firstSupplier->supplier_id,
            'pic_name' => '!',
            'pic_telephone' => $faker->streetAddress,
            'pic_email' => $faker->email,
            'pic_assignment_date' => $faker->date
        ];

        $controller = new SupplierController();

        $request = new Request($requestData);

        $response = $controller->addSupplierPic($request);

        $this->assertEquals(422, $response->getStatusCode());
    }


    public function test_getAllSupplier_success()
    {

        Supplier::factory()->count(3)->create();

        $controller = new SupplierController();

        $response = $controller->getSuppliers($responseType = 'json');

        if ($responseType === 'json') {

            $this->assertEquals(200, $response->getStatusCode());

        } else {

            $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        }

    }

    public function test_getSupplierData_by_id()
    {
        Supplier::factory()->count(10)->create();

        $suppliers = Supplier::all();
        $firstSupplier = $suppliers[8];

        $controller = new SupplierController();

        $response = $controller->getSupplierDataByID($firstSupplier->id, $responseType = 'json');

        if ($responseType === 'json') {

            $this->assertEquals(200, $response->getStatusCode());
        } else {

            $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        }
    }


}
