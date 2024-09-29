<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        return Cart::with('dates', 'address', 'categories', 'imagePaths')->get();
    }

    public function store(Request $request)
    {
        // Doğrulama kuralları ve özel hata mesajları
        $validator = Validator::make($request->all(), [
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
        ], [
            'position.required' => 'Pozisyon alanı gereklidir.',
            'position.integer' => 'Pozisyon geçerli bir tamsayı olmalıdır.',
            'title.required' => 'Başlık alanı gereklidir.',
            'title.string' => 'Başlık geçerli bir dize olmalıdır.',
            'description.required' => 'Açıklama alanı gereklidir.',
            'description.string' => 'Açıklama geçerli bir dize olmalıdır.',
            'phone.required' => 'Telefon numarası alanı gereklidir.',
            'phone.string' => 'Telefon numarası geçerli bir dize olmalıdır.',
            'map_lat.required' => 'Harita enlem alanı gereklidir.',
            'map_lat.string' => 'Harita enlem geçerli bir dize olmalıdır.',
            'map_long.required' => 'Harita boylam alanı gereklidir.',
            'map_long.string' => 'Harita boylam geçerli bir dize olmalıdır.',
            'discount.string' => 'İndirim geçerli bir dize olmalıdır.',
            'menu.string' => 'Menü geçerli bir dize olmalıdır.',
            'brand_like.string' => 'Marka beğenisi geçerli bir dize olmalıdır.',
            'like.string' => 'Beğeni geçerli bir dize olmalıdır.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return Cart::create($request->all());
    }

    public function show($id)
    {
        return Cart::with('dates', 'address', 'categories', 'imagePaths')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // Doğrulama kuralları ve özel hata mesajları
        $validator = Validator::make($request->all(), [
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
        ], [
            'position.integer' => 'Pozisyon geçerli bir tamsayı olmalıdır.',
            'title.string' => 'Başlık geçerli bir dize olmalıdır.',
            'description.string' => 'Açıklama geçerli bir dize olmalıdır.',
            'phone.string' => 'Telefon numarası geçerli bir dize olmalıdır.',
            'map_lat.string' => 'Harita enlem geçerli bir dize olmalıdır.',
            'map_long.string' => 'Harita boylam geçerli bir dize olmalıdır.',
            'discount.string' => 'İndirim geçerli bir dize olmalıdır.',
            'menu.string' => 'Menü geçerli bir dize olmalıdır.',
            'brand_like.string' => 'Marka beğenisi geçerli bir dize olmalıdır.',
            'like.string' => 'Beğeni geçerli bir dize olmalıdır.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
