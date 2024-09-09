<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return Cart::with('dates', 'address', 'categories', 'imagePaths')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'phone' => 'required|string',
            'map_lat' => 'required|string',
            'map_long' => 'required|string',
            'discount' => 'nullable|string',
            'menu' => 'nullable|string',
            'brand_like' => 'nullable|string',
            'like' => 'nullable|string',
        ]);

        return Cart::create($request->all());
    }

    public function show($id)
    {
        return Cart::with('dates', 'address', 'categories', 'imagePaths')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'position' => 'integer',
            'title' => 'string',
            'description' => 'string',
            'phone' => 'string',
            'map_lat' => 'string',
            'map_long' => 'string',
            'discount' => 'string',
            'menu' => 'string',
            'brand_like' => 'string',
            'like' => 'string',
        ]);

        $cart = Cart::findOrFail($id);
        $cart->update($request->all());

        return $cart;
    }

    public function destroy($id)
    {
        Cart::destroy($id);
        return response()->noContent();
    }
}
