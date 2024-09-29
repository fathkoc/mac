<?php

namespace App\Http\Controllers;

use App\Models\CartDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartDateController extends Controller
{
    public function index()
    {
        return CartDate::all();
    }

    public function store(Request $request)
    {
        // Doğrulama kuralları ve özel hata mesajları
        $validator = Validator::make($request->all(), [
            'day' => 'required|date',
            'start_hour' => 'required|date_format:H:i:s',
            'end_hour' => 'required|date_format:H:i:s',
            'cart_id' => 'required|exists:carts,id',
        ], [
            'day.required' => 'Gün bilgisi gereklidir.',
            'day.date' => 'Gün bilgisi geçerli bir tarih formatında olmalıdır.',
            'start_hour.required' => 'Başlangıç saati gereklidir.',
            'start_hour.date_format' => 'Başlangıç saati saat:dakika:saniye formatında olmalıdır.',
            'end_hour.required' => 'Bitiş saati gereklidir.',
            'end_hour.date_format' => 'Bitiş saati saat:dakika:saniye formatında olmalıdır.',
            'cart_id.required' => 'Sepet ID bilgisi gereklidir.',
            'cart_id.exists' => 'Geçersiz sepet ID.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return CartDate::create($request->all());
    }

    public function show($id)
    {
        return CartDate::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // Doğrulama kuralları ve özel hata mesajları
        $validator = Validator::make($request->all(), [
            'day' => 'date',
            'start_hour' => 'date_format:H:i:s',
            'end_hour' => 'date_format:H:i:s',
            'cart_id' => 'exists:carts,id',
        ], [
            'day.date' => 'Geçerli bir tarih formatı gereklidir.',
            'start_hour.date_format' => 'Başlangıç saati saat:dakika:saniye formatında olmalıdır.',
            'end_hour.date_format' => 'Bitiş saati saat:dakika:saniye formatında olmalıdır.',
            'cart_id.exists' => 'Geçersiz sepet ID.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
