<?php
namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        // Tüm hesapları getir ve ilişkileri dahil et
        return Account::with(['city', 'district'])->get();
    }

    public function store(Request $request)
    {
        // Verileri doğrula
        $request->validate([
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
        ]);

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
        $request->validate([
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
        ]);

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
