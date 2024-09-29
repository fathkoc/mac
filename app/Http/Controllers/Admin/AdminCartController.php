<?php

// app/Http/Controllers/CartController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminCartController extends Controller
{
    public function index()
    {
        $carts = Cart::all();
        return view('admin.carts.index', compact('carts'));
    }

    public function create()
    {
        return view('admin.carts.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ], [
            'user_id.required' => 'User ID is required.',
            'user_id.integer' => 'User ID must be an integer.',
            'product_id.required' => 'Product ID is required.',
            'product_id.integer' => 'Product ID must be an integer.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity must be at least 1.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Cart::create($request->all());

        return redirect()->route('carts.index')->with('success', 'Cart created successfully.');
    }

    public function edit($id)
    {
        $cart = Cart::findOrFail($id);
        return view('admin.carts.edit', compact('cart'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ], [
            'user_id.required' => 'User ID is required.',
            'user_id.integer' => 'User ID must be an integer.',
            'product_id.required' => 'Product ID is required.',
            'product_id.integer' => 'Product ID must be an integer.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity must be at least 1.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cart = Cart::findOrFail($id);
        $cart->update($request->all());

        return redirect()->route('carts.index')->with('success', 'Cart updated successfully.');
    }

    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();

        return redirect()->to('admin/carts')->with('success', 'Cart deleted successfully.');
    }
}
