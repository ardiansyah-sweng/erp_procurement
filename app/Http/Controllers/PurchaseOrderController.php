<?php

namespace App\Http\Controllers;

use App\Models\MasterPurchaseOrder;
use App\Models\purchase_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class PurchaseOrderController extends Controller
{

    public function purchaseOrders()
    {
        $purchaseOrders = DB::table('master_purchase_order')
            ->leftJoin('purchase_order', 'master_purchase_order.po_number', '=', 'purchase_order.po_number')
            ->select('master_purchase_order.id', 'master_purchase_order.supplier_id', 'master_purchase_order.po_number', 'master_purchase_order.total', 'master_purchase_order.created_at', DB::raw('COUNT(purchase_order.po_number) as total_po'))
            ->groupBy('master_purchase_order.id', 'master_purchase_order.po_number')
            ->get();

        return view('purchase_orders', [
            'purchase_orders' => $purchaseOrders
        ]);
    }

    public function createPurchaseOrder(Request $request, $responseType = 'web')
    {
        $validator = Validator::make($request->all(), [
            'po_number' => [
                'string',
                'required',
                'min:14',
                'max:15',
            ],
            'supplier_id' => [
                'required',
                'string',
                'min:8',
                'max:8'
            ],
            'item' => [
                'required',
                'array'
            ],
            'total' => [
                'required',
                'integer'
            ]
        ]);

        if ($validator->fails()) {
            if ($responseType === 'json') {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        MasterPurchaseOrder::create([
            'supplier_id' => $request->input('supplier_id'),
            'po_number' => $request->input('po_number'),
            'total' => $request->input('total')
        ]);

        $poData = $request->input('item');
        $total = 0;

        foreach ($poData as $po) {

            purchase_order::create([
                'po_number' => $request->input('po_number'),
                'item_id' => $po[0],
                'item_name' => $po[1],
                'category' => $po[2],
                'uom' => $po[3],
                'price' => intval($po[4]),
                'quantity' => intval($po[5]),
            ]);

            $uncleanedTotal = str_replace(array('.', ','), '', $po[6]);
            $subTotal = intval($uncleanedTotal);
            $total += intval($subTotal);
        }

        MasterPurchaseOrder::where('po_number', $request->input('po_number'))
            ->update(['total' => $total]);

        if ($responseType === 'json') {
            return response()->json(['success' => true, 'message' => 'Purchase Order berhasil ditambahkan!'], 201);
        }

        return redirect('/purchase_orders')->with('success', 'Purchase Order berhasil ditambahkan!');
    }

    public function getPurchaseOrderDetailByPONumber($poNumber, $responseType = 'web')
    {

        $masterPO = MasterPurchaseOrder::where('po_number', $poNumber)->first();
        $purchaseOrders = purchase_order::where('po_number', $poNumber)->get();

        return view('purchase_order_detail', ['purchase_orders' => $purchaseOrders, 'master_po' => $masterPO]);

    }

    public function getPurchaseOrderDetailByPONumberReceipt(Request $request)
    {
        $poNumber = $request->query('po_number');
        $masterPO = MasterPurchaseOrder::where('po_number', $poNumber)->first();
        $purchaseOrders = purchase_order::where('po_number', $poNumber)->get();
        return view('purchase_order_receipt', ['purchase_orders' => $purchaseOrders, 'master_po' => $masterPO, 'uoms' => config('item')['unit_of_measurement']]);
    }

    public function addIncompleteItem(Request $request)
    {

        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $paths = [];

        if ($request->file('images')){
            dd($request->file('images'));
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $paths[] = $path;
            }
        }

    }

}
