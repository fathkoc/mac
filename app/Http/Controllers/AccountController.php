<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        // Tüm hesapları getir ve ilişkileri dahil et
        return Account::with(['city', 'district'])->get();
    }

    public function store(Request $request)
    {
        // Genel doğrulama kuralları ve özel hata mesajları
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'address' => 'required|string',
            'photoURL' => 'nullable|string',
            'activated' => 'required|boolean',
            'role' => 'required|string',
            'referenceCode' => 'nullable|string',
        ], [
            'phoneNumber.required' => 'Telefon numarası gereklidir.',
            'name.required' => 'İsim gereklidir.',
            'email.required' => 'E-posta adresi gereklidir.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'city_id.required' => 'Şehir seçilmelidir.',
            'city_id.exists' => 'Geçersiz şehir ID.',
            'district_id.required' => 'Semt seçilmelidir.',
            'district_id.exists' => 'Geçersiz semt ID.',
            'address.required' => 'Adres gereklidir.',
            'activated.required' => 'Aktivasyon durumu gereklidir.',
            'activated.boolean' => 'Aktivasyon durumu doğru/yanlış olmalıdır.',
            'role.required' => 'Rol gereklidir.',
            'referenceCode.string' => 'Referans kodu geçerli bir dize olmalıdır.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Telefon numarası ve e-posta adresi kontrolü
        $existingAccountByPhone = Account::where('phoneNumber', $request->phoneNumber)->first();
        if ($existingAccountByPhone) {
            return response()->json([
                'status' => false,
                'message' => 'An account with this phone number already exists.',
            ], 400);
        }

        $existingAccountByEmail = Account::where('email', $request->email)->first();
        if ($existingAccountByEmail) {
            return response()->json([
                'status' => false,
                'message' => 'An account with this email already exists.',
            ], 400);
        }

        // Yeni hesap oluştur ve ilişkileri yükle
        $account = Account::create($request->all())->load('city', 'district');

        // Başarıyla oluşturulduğunu belirten yanıt döndür
        return response()->json([
            'status' => true,
            'message' => 'Account created successfully',
            'account' => $account
        ], 201);
    }

    public function show($id)
    {

        return Account::with(['city', 'district'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'string',
            'name' => 'string',
            'email' => 'email',
            'city_id' => 'exists:cities,id',
            'district_id' => 'exists:districts,id',
            'address' => 'string',
            'photoURL' => 'string',
            'activated' => 'boolean',
            'role' => 'string',
            'referenceCode' => 'string',
        ], [
            'phoneNumber.string' => 'Telefon numarası geçerli bir dize olmalıdır.',
            'name.string' => 'İsim geçerli bir dize olmalıdır.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'city_id.exists' => 'Geçersiz şehir ID.',
            'district_id.exists' => 'Geçersiz semt ID.',
            'address.string' => 'Adres geçerli bir dize olmalıdır.',
            'photoURL.string' => 'Fotoğraf URL geçerli bir dize olmalıdır.',
            'activated.boolean' => 'Aktivasyon durumu doğru/yanlış olmalıdır.',
            'role.string' => 'Rol geçerli bir dize olmalıdır.',
            'referenceCode.string' => 'Referans kodu geçerli bir dize olmalıdır.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $account = Account::findOrFail($id);
        $account->update($request->all());

        // Güncellenmiş hesabı ve ilişkileri getir
        return $account->load('city', 'district');
    }

    public function destroy($id)
    {
        Account::destroy($id);
        return response()->noContent();
    }
}
