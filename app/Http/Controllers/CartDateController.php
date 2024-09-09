<?php

namespace App\Http\Controllers;

use App\Models\CartDate;
use Illuminate\Http\Request;

class CartDateController extends Controller
{
    public function index()
    {
        return CartDate::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|date',
            'start_hour' => 'required|date_format:H:i:s',
            'end_hour' => 'required|date_format:H:i:s',
            'cart_id' => 'required|exists:carts,id',
        ]);

        return CartDate::create($request->all());
    }

    public function show($id)
    {
        return CartDate::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'day' => 'date',
            'start_hour' => 'date_format:H:i:s',
            'end_hour' => 'date_format:H:i:s',
            'cart_id' => 'exists:carts,id',
        ]);

        $cartDate = CartDate::findOrFail($id);
        $cartDate->update($request->all());

        return $cartDate;
    }

    public function destroy($id)
    {
        CartDate::destroy($id);
        return response()->noContent();
    }
}
