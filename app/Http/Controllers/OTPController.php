<?php

namespace App\Http\Controllers;
use App\Models\Account; 
use App\Models\Otp;
use App\Models\CustomToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class OTPController extends Controller
{
    public function index()
    {
        return Otp::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'sms_code' => 'required|string',
        ]);

        return Otp::create($request->all());
    }

    public function show($id)
    {
        return Otp::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'phone_number' => 'string',
            'sms_code' => 'string',
        ]);

        $otp = Otp::findOrFail($id);
        $otp->update($request->all());

        return $otp;
    }

    public function destroy($id)
    {
        Otp::destroy($id);
        return response()->noContent();
    }

    // Kod üretme ve gönderme işlemi
    public function generate(Request $request)
    {

        $request->validate([
            'phoneNumber' => 'required|string',
        ]);

        $phoneNumber = $request->input('phoneNumber');

        // Telefon numarasını Account modelinde kontrol et
        $accountExists = Account::where('phoneNumber', $phoneNumber)->exists();

        if (!$accountExists) {

            return response()->json(['message' => 'Phone number not found in account records', 'status' => false], 404);
        }


        $otpCode = rand(100000, 999999);

        // OTP kaydını oluştur veya güncelle
        Otp::updateOrCreate(
            ['phone_number' => $phoneNumber],
            ['sms_code' => $otpCode]
        );

        // OTP kodunu SMS ile gönderme işlemi yapılmalı

        return response()->json(['message' => 'Otp code sent', 'status' => true,'smsCode'=>$otpCode], 200);
    }

    // Otp doğrulama ve giriş işlemi
    public function verify(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'sms_code' => 'required|string',
        ]);

        $phoneNumber = $request->input('phone_number');
        $smsCode = $request->input('sms_code');

        // Otp kaydını kontrol et
        $otp = Otp::where('phone_number', $phoneNumber)
            ->where('sms_code', $smsCode)
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid Otp code'], 400);
        }

        // Otp kodu geçerli, ilgili hesap var mı kontrol et
        $account = Account::where('phoneNumber', $phoneNumber)->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        // Giriş işlemi (örneğin, JWT token döndürme veya oturum açma işlemi)
        // Burada basit bir başarı yanıtı döndürüyoruz
        return response()->json(['message' => 'Login successful', 'account' => $account]);
    }

    public function authPhone(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        $phoneNumber = $request->input('phone_number');

        // Hesap var mı kontrol et
        $account = Account::where('phoneNumber', $phoneNumber)->first();

        if ($account) {
            // SMS kodunu oluştur
            $smsCode = rand(100000, 999999); // Basit bir örnek, gerçek uygulamada daha güvenli bir yöntem kullanmalısınız

            // Kodunuzu veritabanında saklayın veya SMS ile gönderin
            // OtpModel::create(['phoneNumber' => $phoneNumber, 'code' => $smsCode]);

            // Kodunuzu sakladığınız model ve alan adlarını değiştirin
            return response()->json(['success' => true, 'message' => 'SMS code generated.', 'sms_code' => $smsCode], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Phone number not registered.'], 404);
        }
    }

    /**
     * Doğrulama için Otp ve telefon numarasını kontrol et.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
    // Validasyon işlemi
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'required|string',
            'smsCode'     => 'required|string',
        ], [
            'phoneNumber.required' => 'Phone number is required.',
            'smsCode.required'     => 'SMS code is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Telefon numarasına sahip ve SMS kodu doğru olan bir Otp olup olmadığını kontrol et
        $otp = Otp::where('phone_number', $request->phoneNumber)
            ->where('sms_code', $request->smsCode)
            ->first(); 
        if ($otp) {
            // Otp var ise, ilişkili Account'ı döndür
            $account = Account::with(['city', 'district'])
            ->where('phoneNumber', $request->phoneNumber)
            ->first();

            if ($account) {
                Auth::login($account);
                $token = CustomToken::create([
                    'account_id' => $account->id,
                    'name' => 'auth_token',
                    'token' => bin2hex(random_bytes(40)), 
                    'abilities' => json_encode(['*']),
                ]);
                $account->makeHidden(['city_id', 'district_id','photoURL','created_at','updated_at']);
                $account->city->makeHidden(['created_at','updated_at']);
                $account->district->makeHidden(['created_at','updated_at']);
                $camelCasedAccount = $this->convertKeysToCamelCase($account->toArray());
                return response()->json([
                    'status' => true,
                    'success' => true,
                    'account' => $camelCasedAccount,
                    'token' => $token->token,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found for the given phone number.',
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Otp code.',
            ], 400);
        }
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

    public function checkSession()
{
    if (Auth::check()) {
        // Kullanıcı oturum açmış
        return response()->json(['message' => 'User is logged in.'], 200);
    } else {
        // Kullanıcı oturum açmamış
        return response()->json(['message' => 'User is not logged in.'], 401);
    }
}

    public function getUser()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json(['user' => $user], 200);
        } else {
            return response()->json(['message' => 'User is not logged in.'], 401);
        }
    }
}
