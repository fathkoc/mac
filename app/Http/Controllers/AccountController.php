<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CustomToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function findByToken(Request $request)
    {
        
        $token = $request->bearerToken();

        $tokenRecord = CustomToken::where('token', $token)->first();

        if (!$tokenRecord) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Token ile ilişkili Account'ı bul
        $account = $tokenRecord->account()->with(['city', 'district'])->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }
        $account->makeHidden(['city_id', 'district_id','photoURL','created_at','updated_at']);
        $account->city->makeHidden(['created_at','updated_at']);
        $account->district->makeHidden(['created_at','updated_at']);
        $camelCasedAccount = $this->convertKeysToCamelCase($account->toArray());
        return response()->json(['account' => $camelCasedAccount], 200);
    }

    private function convertKeysToCamelCase($array)
    {
        $camelCasedArray = [];
        foreach ($array as $key => $value) {
            // Anahtarı camel case formatına çevir
            $camelKey = Str::camel($key);

            // Değer dizi veya nesne ise, recursive olarak camel case çevir
            if (is_array($value) || is_object($value)) {
                $value = $this->convertKeysToCamelCase((array) $value);
            }

            $camelCasedArray[$camelKey] = $value;
        }

        return $camelCasedArray;
    }
}
