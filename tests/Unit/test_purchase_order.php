<?php

namespace Tests\Unit;

use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Models\Item;
use App\Models\MasterPurchaseOrder;
use App\Models\purchase_order;
use App\Models\Supplier;
use App\Models\SupplierPic;
use Database\Factories\ItemFactory;
use Database\Factories\SupplierFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class test_purchase_order extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    // use RefreshDatabase;

    // public function test_getNextPO_emptyPO()
    // {
    //     $supplier_id = 'SUPP0002';

    //     $controller = new SupplierController();
    //     $response = $controller->getNextPONumber($supplier_id);

    //     $this->assertEquals('POSUPP00020001', $response);
    // }

    // public function test_create_po_success_DB()
    // {

    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    //     Supplier::query()->delete();
    //     Item::query()->delete();
    //     MasterPurchaseOrder::query()->delete();
    //     purchase_order::query()->delete();
    //     SupplierPic::query()->delete();

    //     DB::statement('ALTER TABLE supplier AUTO_INCREMENT = 1;');
    //     DB::statement('ALTER TABLE item AUTO_INCREMENT = 1;');
    //     DB::statement('ALTER TABLE master_purchase_order AUTO_INCREMENT = 1;');
    //     DB::statement('ALTER TABLE purchase_order AUTO_INCREMENT = 1;');
    //     DB::statement('ALTER TABLE supplier_pic AUTO_INCREMENT = 1;');

    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    //     $supplierCount = 10;
    //     $itemCount = 15;
    //     Supplier::factory()->count($supplierCount)->create();
    //     Item::factory()->count($itemCount)->create();

    //     $masterPO = MasterPurchaseOrder::factory()->create();

    //     $faker = Faker::create();
    //     $count = $faker->numberBetween(1, $itemCount);


    //     $purchaseOrders = purchase_order::factory()->count($count)->withPONumber($masterPO->po_number)->create();

    //     $totalAmount = $purchaseOrders->sum(function ($purchaseOrder) {
    //         return $purchaseOrder->quantity * $purchaseOrder->price;
    //     });

    //     $masterPO->update(['total' => $totalAmount]);

    //     $pos = purchase_order::all();
    //     foreach($pos as $po){
    //         print_r($po.'-');
    //     }
    //     exit();

    // }

    public function test_create_po_success()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Supplier::query()->delete();
        Item::query()->delete();
        MasterPurchaseOrder::query()->delete();
        purchase_order::query()->delete();
        SupplierPic::query()->delete();

        DB::statement('ALTER TABLE supplier AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE master_purchase_order AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE purchase_order AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE supplier_pic AUTO_INCREMENT = 1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $supplierCount = 10;
        $itemCount = 15;
        Supplier::factory()->count($supplierCount)->create();
        $items = Item::factory()->count($itemCount)->create();

        $faker = Faker::create();

        $suppliers = Supplier::all();
        $supplierIdx = $faker->numberBetween(0, $supplierCount - 1);
        $supplierID = $suppliers[$supplierIdx]->supplier_id;

        $instanceSupplier = new SupplierController();
        $poNumber = $instanceSupplier->getNextPONumber($supplierID);

        $count = $faker->numberBetween(1, $itemCount);

        $purchase_orders = [];
        for ($i = 0; $i < $count; $i++) {

            $poData = [];

            $itemID = $items[$i]->item_id;

            if (!in_array($itemID, $poData)) {
                $price = $faker->numberBetween(5000, 100000);
                $quantity = $faker->numberBetween(10, 1000);
                $subTotal = $quantity * $price;
                $poData[] = $itemID;
                $poData[] = $items[$i]->name;
                $poData[] = $items[$i]->category;
                $poData[] = $items[$i]->unit_of_measurement;
                $poData[] = number_format($price, 2, ',', '.');
                $poData[] = $quantity;
                $poData[] = number_format($subTotal, 2, ',', '.');
                $purchase_orders[] = $poData;
            }
        }

        $requestData = [
            'po_number' => $poNumber,
            'supplier_id' => $supplierID,
            'item' => $purchase_orders,
            'total' => 0
        ];


        $controller = new PurchaseOrderController();
        $request = new Request($requestData);
        $response = $controller->createPurchaseOrder($request);

        // Test with web response type
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    public function test_getPurchaseOrderDetailByID(){
        
        //get random po number
        $purchaseOrder = purchase_order::all();
        $faker = Faker::create();
        $randomIdx = $faker->numberBetween(0, $purchaseOrder->count()-1);
        $poNumber = $purchaseOrder[$randomIdx]->po_number;

        $controller = new PurchaseOrderController();
        $response = $controller->getPurchaseOrderDetailByPONumber($poNumber);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
    }

    public function test_grn_addIncompleteItem()
    {
        //get random po number
        $purchaseOrder = purchase_order::all();
        $faker = Faker::create();
        $randomIdx = $faker->numberBetween(0, $purchaseOrder->count() - 1);
        $poNumber = $purchaseOrder[$randomIdx]->po_number;

        //get random item
        $purchaseOrders = purchase_order::where('po_number', $poNumber)->get();
        $randomIdx = $faker->numberBetween(0, $purchaseOrders->count() - 1);
        $selectedItemRecord = $purchaseOrders[$randomIdx];
        
        $incompleteQuantity = $faker->numberBetween(1, 10);

        //divide between two condition: defect and unknown. Can be zero one of them.
        $defectQuantity = round(($faker->numberBetween(1, 100) / 100) * $incompleteQuantity);
        $unknownQuantity = $incompleteQuantity - $defectQuantity;

        $incomplateImgPath = $faker->filePath();
        print_r($incompleteQuantity.'-'.$defectQuantity.'-'. $unknownQuantity);

    }

    // public function test_getNextPO_existPO_success()
    // {
    //     $count = 10;
    //     purchase_order::factory()->count($count)->create();

    //     $po = purchase_order::all();

    //     $faker = Faker::create();
    //     $idx = $faker->numberBetween(0, $count - 1);

    //     $supplier_id = ($po[$idx]->supplier_id);

    //     $controller = new SupplierController();
    //     $response = $controller->getNextPONumber($supplier_id);

    //     $this->assertIsInt(intval($response) + 1);
    // }    

}
