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
            'cityId' => 'required|exists:cities,id',
            'districtId' => 'required|exists:districts,id',
            'address' => 'required|string',
            'role' => 'required|string',
            'referenceCode' => 'nullable|string',
            // activated alanını ekleyin
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


        $data = $request->merge([
            'city_id' => $request->input('cityId'),
            'district_id' => $request->input('districtId'),
            'activated' => true // Bu satırı ekledik
        ])->except(['cityId', 'districtId']);

        // Yeni hesap oluştur ve ilişkileri yükle
        $account = Account::create($data)->load('city', 'district');

        $account->makeHidden(['city_id', 'district_id', 'photoURL', 'created_at', 'updated_at']);
        $account->city->makeHidden(['created_at', 'updated_at']);
        $account->district->makeHidden(['created_at', 'updated_at']);
        $camelCasedAccount = $this->convertKeysToCamelCase($account->toArray());

        // Başarıyla oluşturulduğunu belirten yanıt döndür
        return response()->json([
            'status' => true,
            'message' => 'Account created successfully',
            'account' => $camelCasedAccount
        ], 201);
    }


    public function show($id)
    {
        $account = Account::with(['city', 'district'])->findOrFail($id);

        $account->makeHidden(['city_id', 'district_id', 'photoURL', 'created_at', 'updated_at']);
        $account->city->makeHidden(['created_at', 'updated_at']);
        $account->district->makeHidden(['created_at', 'updated_at']);
        $accountArray = $account->toArray();

        $camelCasedAccount = $this->convertKeysToCamelCase($accountArray);

        return response()->json(['account' => $camelCasedAccount], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'phoneNumber' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'cityId' => 'required|exists:cities,id',
            'districtId' => 'required|exists:districts,id',
            'address' => 'required|string',
            'role' => 'required|string',
            'referenceCode' => 'nullable|string',
        ]);

        $account = Account::findOrFail($id);

        // Verileri dönüştür
        $data = $request->merge([
            'city_id' => $request->input('cityId'),
            'district_id' => $request->input('districtId'),
        ])->except(['cityId', 'districtId']);

        // Hesabı güncelle
        $account->update($data);

        // Güncellenmiş hesabı ve ilişkileri getir
        return $account->load('city', 'district');
    }

    public function destroy($id)
    {

        $account = Account::find($id);

        if (!$account) {

            return response()->json(['status' => false, 'message' => 'Account not found'], 404);
        }

        // Hesabı sil
        if ($account->delete()) {
            // Silme işlemi başarılıysa başarı yanıtı döndür
            return response()->json(['status' => true], 200);
        } else {
            // Silme işlemi başarısızsa hata yanıtı döndür
            return response()->json(['status' => false, 'message' => 'Failed to delete account'], 500);
        }
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
        $account->makeHidden(['city_id', 'district_id', 'photoURL', 'created_at', 'updated_at']);
        $account->city->makeHidden(['created_at', 'updated_at']);
        $account->district->makeHidden(['created_at', 'updated_at']);
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
