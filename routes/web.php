<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartAddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDateController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ImagePathController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OTPController;

Route::get('/', function () {
    return view('welcome');
});

// OTP
Route::get('/otp', [OTPController::class, 'index'])->name('otp.index');
Route::post('/otp', [OTPController::class, 'store']);
Route::get('/otp/{id}', [OTPController::class, 'show']);
Route::put('/otp/{id}', [OTPController::class, 'update']);
Route::delete('/otp/{id}', [OTPController::class, 'destroy']);

// OTP kodu oluştur
Route::post('/auth/phone', [OTPController::class, 'generate']);
Route::post('/auth/otp', [OTPController::class, 'authenticate']);

// OTP kodunu doğrula ve giriş yap
Route::post('/otp/verify', [OTPController::class, 'verify']);

// Auth
//Route::post('/auth/phone', [OTPController::class, 'authPhone']);


Route::get('/check-session', [OTPController::class, 'checkSession']);



// City
Route::get('/county/city', [CityController::class, 'index'])->name('cities.index');
Route::post('/cities', [CityController::class, 'store']);
Route::get('/cities/{id}', [CityController::class, 'show']);
Route::put('/cities/{id}', [CityController::class, 'update']);
Route::delete('/cities/{id}', [CityController::class, 'destroy']);

// District
Route::get('/districts', [DistrictController::class, 'index'])->name('districts.index');
Route::post('/districts', [DistrictController::class, 'store']);
Route::get('/districts/{id}', [DistrictController::class, 'show']);
Route::put('/districts/{id}', [DistrictController::class, 'update']);
Route::delete('/districts/{id}', [DistrictController::class, 'destroy']);
Route::get('/county/district/{id}', [DistrictController::class, 'getDistrict']);

// Account
Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
Route::post('/account/create_account', [AccountController::class, 'store']);
Route::get('/accounts/{id}', [AccountController::class, 'show']);
Route::put('/accounts/{id}', [AccountController::class, 'update']);
Route::delete('/accounts/{id}', [AccountController::class, 'destroy']);
Route::get('/account/getAccount', [AccountController::class, 'findByToken']);

// Cart
Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
Route::post('/carts', [CartController::class, 'store']);
Route::get('/carts/{id}', [CartController::class, 'show']);
Route::put('/carts/{id}', [CartController::class, 'update']);
Route::delete('/carts/{id}', [CartController::class, 'destroy']);

// CartAddress
Route::get('/cart-addresses', [CartAddressController::class, 'index'])->name('cart-addresses.index');
Route::post('/cart-addresses', [CartAddressController::class, 'store']);
Route::get('/cart-addresses/{id}', [CartAddressController::class, 'show']);
Route::put('/cart-addresses/{id}', [CartAddressController::class, 'update']);
Route::delete('/cart-addresses/{id}', [CartAddressController::class, 'destroy']);

// CartDate
Route::get('/cart-dates', [CartDateController::class, 'index'])->name('cart-dates.index');
Route::post('/cart-dates', [CartDateController::class, 'store']);
Route::get('/cart-dates/{id}', [CartDateController::class, 'show']);
Route::put('/cart-dates/{id}', [CartDateController::class, 'update']);
Route::delete('/cart-dates/{id}', [CartDateController::class, 'destroy']);

// Caregories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

// ImagePath
Route::get('/image-paths', [ImagePathController::class, 'index'])->name('image-paths.index');
Route::post('/image-paths', [ImagePathController::class, 'store']);
Route::get('/image-paths/{id}', [ImagePathController::class, 'show']);
Route::put('/image-paths/{id}', [ImagePathController::class, 'update']);
Route::delete('/image-paths/{id}', [ImagePathController::class, 'destroy']);

// Like
Route::get('/likes', [LikeController::class, 'index'])->name('likes.index');
Route::post('/likes', [LikeController::class, 'store']);
Route::get('/likes/{id}', [LikeController::class, 'show']);
Route::put('/likes/{id}', [LikeController::class, 'update']);
Route::delete('/likes/{id}', [LikeController::class, 'destroy']);

