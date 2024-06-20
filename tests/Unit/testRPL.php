<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\rplController;
use Faker\Factory as Faker;
use Illuminate\Http\Request;

class testRPL extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_rpl_invalid_name()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => 6,
            'telephone' => $faker->phoneNumber,
            'email' => $faker->email
        ];
        $controller = new rplController();

        $request = new Request($requestData);

        $response = $controller->rpl($request);

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function test_rpl_invalid_name_and_phone()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => 'ud',
            'telephone' => 4533453,
            'email' => $faker->email
        ];
        $controller = new rplController();

        $request = new Request($requestData);

        $response = $controller->rpl($request);

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function test_rpl_valid_success()
    {
        $faker = Faker::create();

        $requestData = [
            'name' => $faker->name,
            'telephone' => $faker->phoneNumber,
            'email' => $faker->email
        ];
        $controller = new rplController();

        $request = new Request($requestData);

        $response = $controller->rpl($request);

        $this->assertEquals(201, $response->getStatusCode());
    }    

}