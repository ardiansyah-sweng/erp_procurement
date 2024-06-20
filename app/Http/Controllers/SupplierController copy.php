<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function addSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]*$/'
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

            'supplier_pic' => [
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]*$/'
            ],
            'supplier_telephone' => [
                'min:5',
                'max:25'
            ],
            'supplier_email' => [
                'email',
                'max:255'
            ],
            'supplier_since' => [
                'date_format:Y-m-d'
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->get('supplier_pic')){
            //new SupplierPic
            //save
            return redirect()->back();
        }

        $supplier = new Supplier([
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'telephone' => $request->get('telephone')
        ]);

        $supplier->save();

        #Return success response or boolean
        return response()->json(['success' => true]);    
    }
}
