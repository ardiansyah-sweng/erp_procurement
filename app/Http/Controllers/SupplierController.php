<?php

namespace App\Http\Controllers;

use App\Models\MasterPurchaseOrder;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierPic;
use App\Models\purchase_order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class SupplierController extends Controller
{

    public function addSupplierPic(Request $request, $responseType = 'web')
    {
        try {

            $validator = Validator::make($request->all(), [

                'pic_name' => [
                    'string',
                    'min:3',
                    'max:255',
                    'regex:/^[a-zA-Z\s]*$/'
                ],
                'pic_telephone' => [
                    #'required',
                    'string',
                    'min:5',
                    'max:25'
                ],
                'pic_email' => [
                    'email',
                    'max:255'
                ],
                'pic_assignment_date' => [
                    #'required',
                    'date_format:Y-m-d'
                ]
            ]);

            if ($validator->fails()) {

                return response()->json(['errors' => $validator->errors()], 422);
            }

            SupplierPic::create([
                'supplier_id' => $request->supplier_id,
                'pic_name' => $request->pic_name,
                'pic_telephone' => $request->pic_telephone,
                'pic_email' => $request->pic_email,
                'pic_assignment_date' => $request->pic_assignment_date
            ]);

            return redirect('/suppliers')->with('success', 'Contact person berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangkap pengecualian di sini dan lakukan penanganan yang sesuai
            Log::error('Error occurred while adding supplier: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    # TODO gabungkan saja dengan getSuppliers
    public function getSupplierDataByID($supplierID, $responseType = 'web')
    {
        # TODO memisahkan pesan error user friendly dan log error buat developer
        try {
            $supplier = Supplier::find($supplierID);

            if (!$supplier) {
                return response()->json(['message' => 'Supplier not found'], 404);
            }

            if ($responseType === 'json') {
                return response()->json($supplier, 200);
            }

            return view('suppliers.show', ['supplier' => $supplier]);
        } catch (\Exception $e) {
            // Tangkap pengecualian di sini dan lakukan penanganan yang sesuai
            Log::error('Error occurred while adding supplier: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getNextPONumber($supplierID, $responseType = 'web'){

        $latestPO = MasterPurchaseOrder::where('supplier_id', $supplierID)
            ->latest('created_at') // Mengurutkan berdasarkan tanggal terbaru
            ->first();

        if ($latestPO){
            $last_four_digit = substr($latestPO->po_number, -4);
            return 'PO'.$supplierID.sprintf("%04d", $last_four_digit + 1);
        }
        
        return 'PO'.$supplierID.'0001';
    }

    public function getSuppliers($responseType = 'web')
    {
        # TODO mengganti query builder ke eloquent

        $suppliers = DB::table('supplier')
            ->leftJoin('supplier_pic', 'supplier.supplier_id', '=', 'supplier_pic.supplier_id')
            ->select('supplier.id', 'supplier.supplier_id', 'supplier.name', 'supplier.address', 'supplier.telephone', DB::raw('COUNT(supplier_pic.supplier_id) as total_pic'))
            ->groupBy('supplier.id', 'supplier.name')
            ->get();

        if ($responseType === 'json') {
            return response()->json($suppliers, 200);
        }

        $encryptedSupplierIds = [];
        $encryptedNames = [];
        $nextPONumbers = [];

        if ($suppliers->isNotEmpty()) {
            foreach ($suppliers as $supplier) {
                $encryptedSupplierIds[] = Crypt::encryptString($supplier->supplier_id);
                $encryptedNames[] = Crypt::encryptString($supplier->name);
                $nextPONumber = $this->getNextPONumber($supplier->supplier_id);
                $nextPONumbers[] = $nextPONumber;
            }
        }
        
        return view('suppliers', [
            'suppliers' => $suppliers,
            'encryptedSupplierIds' => $encryptedSupplierIds,
            'encryptedNames' => $encryptedNames,
            'nextPONumbers' => $nextPONumbers
        ]);
    }

    function getSupplierIDValue()
    {

        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $lastId = $lastSupplier ? $lastSupplier->id : 0;

        return $lastId;
    }

    public function addSupplier(Request $request, $responseType = 'web')
    {

        try {

            $supplierIDPrefix = 'SUPP';
            $maxSupplierID = config('supplier.max_supplier_id');

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'min:3',
                    'max:255',
                ],
                'address' => [
                    'required',
                    'min:5',
                    'max:255'
                ],
                'telephone' => [
                    'required',
                    'min:5',
                    'max:25'
                ],
            ]);

            if ($validator->fails()) {

                return response()->json(['errors' => $validator->errors()], 422);
            }

            $supplierID = $request->input('supplier_id');

            if (!$supplierID) {

                $supplierIDValue = $this->getSupplierIDValue();

                if ($supplierIDValue == 0) {
                    $supplierID = $supplierIDPrefix . sprintf("%04d", 1);
                }

                if ($supplierIDValue != $maxSupplierID) {
                    $supplierID = $supplierIDPrefix . sprintf("%04d", $supplierIDValue + 1);
                }

                if ($supplierIDValue === $maxSupplierID) {
                    return $maxSupplierID;
                }
            }

            $supplierData = Supplier::create([
                'supplier_id' => $supplierID,
                'name' => $request->name,
                'address' => $request->address,
                'telephone' => $request->telephone,
            ]);

            # supplier person in charge area
            if ($request->pic_name) {

                $validator = Validator::make($request->all(), [

                    'pic_name' => [
                        'string',
                        'min:3',
                        'max:255',
                        'regex:/^[a-zA-Z\s]*$/'
                    ],
                    'pic_telephone' => [
                        #'required',
                        'string',
                        'min:5',
                        'max:25'
                    ],
                    'pic_email' => [
                        'email',
                        'max:255'
                    ],
                    'pic_assignment_date' => [
                        #'required',
                        'date_format:Y-m-d'
                    ]
                ]);

                if ($validator->fails()) {

                    return response()->json(['errors' => $validator->errors()], 422);
                }

                SupplierPic::create([
                    'supplier_id' => $supplierID,
                    'pic_name' => $request->pic_name,
                    'pic_telephone' => $request->pic_telephone,
                    'pic_email' => $request->pic_email,
                    'pic_assignment_date' => $request->pic_assignment_date
                ]);
            }

            if ($responseType === 'json') {
                return response()->json($supplierData, 201);
            }

            return redirect('/supplier/form')->with('success', 'Supplier berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangkap pengecualian di sini dan lakukan penanganan yang sesuai
            Log::error('Error occurred while adding supplier: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
