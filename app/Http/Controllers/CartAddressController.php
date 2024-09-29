<?php

namespace App\Http\Controllers;

use App\Models\CartAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartAddressController extends Controller
{
    public function index()
    {
        return CartAddress::with('city', 'district', 'cart')->get();
    }

    public function store(Request $request)
    {
        // Doğrulama kuralları ve özel hata mesajları
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'low_description' => 'nullable|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'cart_id' => 'required|exists:carts,id',
        ], [
            'description.required' => 'Açıklama alanı gereklidir.',
            'description.string' => 'Açıklama geçerli bir dize olmalıdır.',
            'low_description.string' => 'Düşük açıklama geçerli bir dize olmalıdır.',
            'city_id.required' => 'Şehir ID’si gereklidir.',
            'city_id.exists' => 'Geçersiz şehir ID.',
            'district_id.required' => 'Semt ID’si gereklidir.',
            'district_id.exists' => 'Geçersiz semt ID.',
            'cart_id.required' => 'Sepet ID’si gereklidir.',
            'cart_id.exists' => 'Geçersiz sepet ID.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        return CartAddress::create($request->all());
    }

    public function show($id)
    {
        return CartAddress::with('city', 'district', 'cart')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // Doğrulama kuralları ve özel hata mesajları
        $validator = Validator::make($request->all(), [
            'description' => 'string',
            'low_description' => 'string',
            'city_id' => 'exists:cities,id',
            'district_id' => 'exists:districts,id',
            'cart_id' => 'exists:carts,id',
        ], [
            'description.string' => 'Açıklama geçerli bir dize olmalıdır.',
            'low_description.string' => 'Düşük açıklama geçerli bir dize olmalıdır.',
            'city_id.exists' => 'Geçersiz şehir ID.',
            'district_id.exists' => 'Geçersiz semt ID.',
            'cart_id.exists' => 'Geçersiz sepet ID.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
