<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminCartAddressController extends Controller
{
    public function index()
    {
        $addresses = CartAddress::all();
        return view('admin.cart_addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('admin.cart_addresses.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ], [
            'cart_id.required' => 'Cart ID is required.',
            'cart_id.exists' => 'Cart ID must exist in the carts table.',
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
            'address.max' => 'Address may not be greater than 255 characters.',
            'city.required' => 'City is required.',
            'city.string' => 'City must be a string.',
            'city.max' => 'City may not be greater than 255 characters.',
            'postal_code.required' => 'Postal code is required.',
            'postal_code.string' => 'Postal code must be a string.',
            'postal_code.max' => 'Postal code may not be greater than 20 characters.',
            'notes.string' => 'Notes must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        CartAddress::create($request->all());

        return redirect()->route('cart_addresses.index')->with('success', 'Cart Address created successfully.');
    }

    public function edit(CartAddress $cartAddress)
    {
        return view('admin.cart_addresses.edit', compact('cartAddress'));
    }

    public function update(Request $request, CartAddress $cartAddress)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ], [
            'cart_id.required' => 'Cart ID is required.',
            'cart_id.exists' => 'The selected cart ID is invalid.',
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
            'address.max' => 'Address may not be greater than 255 characters.',
            'city.required' => 'City is required.',
            'city.string' => 'City must be a string.',
            'city.max' => 'City may not be greater than 255 characters.',
            'postal_code.required' => 'Postal code is required.',
            'postal_code.string' => 'Postal code must be a string.',
            'postal_code.max' => 'Postal code may not be greater than 20 characters.',
            'notes.string' => 'Notes must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $cartAddress->update($request->all());

        return redirect()->route('cart_addresses.index')->with('success', 'Cart Address updated successfully.');
    }

    public function destroy(CartAddress $cartAddress)
    {
        $cartAddress->delete();
        return redirect()->route('cart_addresses.index')->with('success', 'Cart Address deleted successfully.');
    }
}
