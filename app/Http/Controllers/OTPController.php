<?php

namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class OTPController extends Controller
{
    public function index()
    {
        return Otp::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'sms_code' => 'required|string',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a string.',
            'sms_code.required' => 'SMS code is required.',
            'sms_code.string' => 'SMS code must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        return Otp::create($request->all());
    }

    public function show($id)
    {
        return Otp::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'string',
            'sms_code' => 'string',
        ], [
            'phone_number.string' => 'Phone number must be a valid string.',
            'sms_code.string' => 'SMS code must be a valid string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


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

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a valid string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $phoneNumber = $request->input('phone_number');

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

        return response()->json(['message' => 'Otp code sent', 'status' => true], 200);
    }

    // Otp doğrulama ve giriş işlemi
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'sms_code' => 'required|string',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a valid string.',
            'sms_code.required' => 'SMS code is required.',
            'sms_code.string' => 'SMS code must be a valid string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


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
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a valid string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


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
            'phone_number' => 'required|string',
            'sms_code'     => 'required|string',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'sms_code.required'     => 'SMS code is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Telefon numarasına sahip ve SMS kodu doğru olan bir Otp olup olmadığını kontrol et
        $otp = Otp::where('phone_number', $request->phone_number)
            ->where('sms_code', $request->sms_code)
            ->first();
        if ($otp) {
            // Otp var ise, ilişkili Account'ı döndür
            $account = Account::with(['city', 'district'])
            ->where('phoneNumber', $request->phone_number)
            ->first();

            if ($account) {
                Auth::login($account);
                return response()->json([
                    'status' => true,
                    'success' => true,
                    'account' => $account
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
