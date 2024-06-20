<?php

namespace Database\Factories;

use App\Http\Controllers\SupplierController;
use App\Models\Item;
use App\Models\MasterPurchaseOrder;
use App\Models\purchase_order;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\purchase_order>
 */
class purchase_orderFactory extends Factory
{
    protected $model = purchase_order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        $faker = Faker::create();
        
        // $suppliers = Supplier::all();
        // $controller = new SupplierController();

        // $idx = $faker->numberBetween(0, $suppliers->count() - 1);
        // $supplier_id = ($suppliers[$idx]->supplier_id);

        // $poNumber = $controller->getNextPONumber($supplier_id);

        $items = Item::all();
        $itemIndex = $faker->numberBetween(0, $items->count()-1);

        $itemID = $items->get($itemIndex)->item_id;
        $itemName = $items->get($itemIndex)->name;
        $category = $items->get($itemIndex)->category;
        $uom = $items->get($itemIndex)->unit_of_measurement;
        $description = $items->get($itemIndex)->description;
        $quantity = $faker->numberBetween(2, 1000);
        $price = $faker->numberBetween(1000, 100000);

        return [
            'po_number' =>'',
            'item_id' => $itemID,
            'item_name' => $itemName,
            'category' => $category,
            'uom' => $uom,
            'description' => $description,
            'quantity' => $quantity,
            'price' => $price
        ];
    }

    public function withPONumber($poNumber)
    {
        return $this->state(function (array $attributes) use ($poNumber) {
            return [
                'po_number' => $poNumber,
            ];
        });
    }    
}
