<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route untuk menambahkan supplier
Route::post('/supplier/add', [SupplierController::class, 'addSupplier'])->name('add_supplier');

// Route untuk menampilkan form tambah supplier
Route::get('/supplier/form', function () {
    return view('add_supplier');
})->name('supplier.form');

// Route untuk menambahkan supplier melalui form
Route::post('/supplier/add', [SupplierController::class, 'addSupplier'])->name('supplier.add');
Route::post('/supplier/pic/add', [SupplierController::class, 'addSupplierPic'])->name('supplier.pic.add');
Route::post('/items/add', [ItemController::class, 'addItem'])->name('item.add');

// Route::get('/supplier', function () {
//     return view('supplier');
// })->name('supplier.index');

# Suppliers
Route::get('/supplier', [SupplierController::class, 'getSuppliers'])->name('supplier.index');
Route::get('/suppliers', [SupplierController::class, 'getSuppliers'])->name('suppliers.index');
Route::get('/supplier/{supplierID}', [SupplierController::class, 'getSupplierDataByID'])
    ->name('supplier.show');

# Items
Route::get('/items', [ItemController::class, 'getItems'])->name('items.index');
Route::get('/search-items', [ItemController::class, 'searchItems'])->name('search.items');
Route::get('/items/form', function () {
    return view('add_supplier');
})->name('item.form');
Route::get('/items/{item_id}', [ItemController::class, 'searchItemsById'])
    ->name('item.show');

# Purchase Order
// Route::get('/purchase_order/form', function (Illuminate\Http\Request $request) {
//     $supplier_id = Crypt::decryptString($request->query('supplier_id'));
//     $name = Crypt::decryptString($request->query('name'));
//     return view('purchase_order', compact('supplier_id', 'name'));
// })->name('purchase_order.form');

Route::get('/purchase_order/form', function (Illuminate\Http\Request $request) {
    // Check if the query parameters 'supplier_id' and 'name' are present
    if (!$request->has(['supplier_id', 'name'])) {
        // Redirect to /suppliers with an error message
        return Redirect::to('/suppliers')->with('error', 'Halaman ini harus lewat halaman /suppliers dulu');
    }

    // Decrypt the query parameters
    $supplier_id = Crypt::decryptString($request->query('supplier_id'));
    $name = Crypt::decryptString($request->query('name'));
    $po_number = $request->query('po_number');

    // Return the view with the necessary data
    return view('datatable', compact('po_number', 'supplier_id', 'name'));
})->name('purchase_order.form');

Route::post('/purchase_order/save', [PurchaseOrderController::class, 'createPurchaseOrder'])->name('purchase_order.save');
Route::get('/purchase_orders', [PurchaseOrderController::class, 'purchaseOrders'])->name('purchase_orders.index');
Route::get('/purchase_order/detail/{po_number}', [PurchaseOrderController::class, 'getPurchaseOrderDetailByPONumber'])
    ->name('purchase_order_detail');
Route::get('/purchase_order/receipt', [PurchaseOrderController::class, 'getPurchaseOrderDetailByPONumberReceipt'])
    ->name('purchase_order_receipt');
Route::post('/purchase_order/receipt', [PurchaseOrderController::class, 'addIncompleteItem'])
    ->name('purchase_order_receipt_incomplete_item');

use Illuminate\Support\Facades\DB;

Route::get('/test-database-connection', function () {
    try {
        DB::connection()->getPdo();
        return "Connection to database established successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
