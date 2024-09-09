<?php

namespace App\Http\Controllers;

use App\Models\CartAddress;
use Illuminate\Http\Request;

class CartAddressController extends Controller
{
    public function index()
    {
        return CartAddress::with('city', 'district', 'cart')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'low_description' => 'nullable|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'cart_id' => 'required|exists:carts,id',
        ]);

        return CartAddress::create($request->all());
    }

    public function show($id)
    {
        return CartAddress::with('city', 'district', 'cart')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'string',
            'low_description' => 'string',
            'city_id' => 'exists:cities,id',
            'district_id' => 'exists:districts,id',
            'cart_id' => 'exists:carts,id',
        ]);

        $cartAddress = CartAddress::findOrFail($id);
        $cartAddress->update($request->all());

        return $cartAddress;
    }

    public function destroy($id)
    {
        CartAddress::destroy($id);
        return response()->noContent();
    }
}
