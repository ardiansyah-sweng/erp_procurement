<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ItemController extends Controller
{

    public function searchItemsById($itemId)
    {
        // Lakukan query untuk mendapatkan data item berdasarkan item_id
        $item = Item::where('item_id', $itemId)->first();
        // Kembalikan data dalam format JSON
        $responseData = [
            'item' => $item,
            'categories' => config('item.category'),
            'unit_of_measurements' => config('item.unit_of_measurement')
        ];

        return response()->json($responseData);
        // return response()->json($item);
    }

    #TODO belum ada unit test
    public function searchItems(Request $request)
    {
        $keyword = $request->get('query');
        $items = Item::where('item_id', 'LIKE', "%{$keyword}%")
            ->orWhere('name', 'LIKE', "%{$keyword}%")
            ->get();

        $responseData = [
            'item' => $items,
            'categories' => config('item.category'),
            'unit_of_measurements' => config('item.unit_of_measurement')
        ];
        return response()->json($responseData);        
    }

    public function getItems($responseType = 'web')
    {
        $items = Item::all();

        $categories = config('item.category');
        $unit_of_measurement = config('item.unit_of_measurement');

        if ($responseType === 'json') {
            return response()->json($items, 200);
        }

        return view('items', [
            'items' => $items,
            'categories' => $categories,
            'unit_of_measurement' => $unit_of_measurement
        ]);
    }

    public function addItem(Request $request, $responseType = 'web')
    {
        #TODO check if new item is exist to prevent, double item

        try {

            $validator = Validator::make($request->all(), [

                'item_id' => [
                    'required',
                    'string',
                    'min:8',
                    'max:8'
                ],
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100'
                ],
                'description' => [
                    'required',
                    'max:255'
                ]
            ]);

            if ($validator->fails()) {

                return response()->json(['errors' => $validator->errors()], 422);
            }

            $itemData = Item::create([
                'item_id' => $request->item_id,
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'unit_of_measurement' => $request->unit_of_measurement
            ]);

            if ($responseType === 'json') {

                return response()->json($itemData, 201);
            }

            return redirect('/items')->with('success', 'Item berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangkap pengecualian di sini dan lakukan penanganan yang sesuai
            Log::error('Error occurred while adding supplier: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
