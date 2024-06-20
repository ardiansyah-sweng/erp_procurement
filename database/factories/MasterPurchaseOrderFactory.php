<?php

namespace Database\Factories;

use App\Http\Controllers\SupplierController;
use App\Models\MasterPurchaseOrder;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MasterPurchaseOrderFactory extends Factory
{
    protected $model = MasterPurchaseOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = Faker::create();

        $suppliers = Supplier::all();
        $controller = new SupplierController();

        $idx = $faker->numberBetween(0, $suppliers->count() - 1);
        $supplier_id = ($suppliers[$idx]->supplier_id);

        $poNumber = $controller->getNextPONumber($supplier_id);

        return [
            'supplier_id' => $supplier_id,
            'po_number' => $poNumber,
            'total' => 0,
        ];
    }
}
