<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class rplController extends Controller
{
    public function rpl(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]*$/'
            ],
            'telephone' => [
                'string',
                'min:5',
                'max:25'
            ],
            'email' => [
                'email',
                'max:255'
            ]

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);

        }

        return response()->json($request, 201);
        
    }

}
