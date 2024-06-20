<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\SupplierController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;

class SupplierTest extends TestCase
{

    /**
     * A basic unit test example.
     *
     * @return void
     */

    use RefreshDatabase;

     public function test_getAllSupplier_success()
    {

        Supplier::factory()->count(3)->create();

        // $controller = new SupplierController();

        // $response = $controller->getSuppliers();

        // $this->assertEquals(200, $response->getStatusCode());
    }
}
