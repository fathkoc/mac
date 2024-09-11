<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::all();

        // Her bir city'nin 'created_at' ve 'updated_at' alanlarını gizle
        $cities->each(function ($city) {
            $city->makeHidden(['created_at', 'updated_at']);
        });


        return response()->json([
            'status' => true,
            'cities' => $cities
        ], 200);
    }

    public function store(Request $request)
    {
        // Verileri doğrula
        $validatedData = $request->validate([
            'name' => 'required|string',
            'plateCode' => 'required|string',
        ]);

        // Yeni City kaydını oluştur
        $city = City::create($validatedData);

        // Gizlenmesi gereken alanları ayarla
        $city->makeHidden(['created_at', 'updated_at']);

        // Verileri camelCase formatına dönüştür
        $camelCasedCity = $this->convertKeysToCamelCase($city->toArray());

        // Başarıyla oluşturulduğunu belirten yanıt döndür
        return response()->json([
            'status' => true,
            'message' => 'City created successfully',
            'city' => $camelCasedCity,
            'id' => $city->id
        ], 201);
    }

    public function show($id)
    {
        // Belirtilen ID'ye sahip City'yi bul
        $city = City::findOrFail($id);

        // Gizlenmesi gereken alanları ayarla
        $city->makeHidden(['created_at', 'updated_at']);

        // Verileri camelCase formatına dönüştür
        $camelCasedCity = $this->convertKeysToCamelCase($city->toArray());

        // Yanıtı döndür
        return response()->json([
            'status' => true,
            'city' => $camelCasedCity
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
            'plateCode' => 'string',
        ]);

        $city = City::findOrFail($id);
        $city->update($request->all());

        return $city;
    }

    public function destroy($id)
    {
        // Belirtilen ID'ye sahip City'yi bul
        $city = City::find($id);

        if (!$city) {
            // Eğer şehir bulunamazsa, hata yanıtı döndür
            return response()->json([
                'status' => false,
                'message' => 'City not found'
            ], 404);
        }

        // Şehri sil
        $city->delete();

        // Başarıyla silindiğini belirten yanıt döndür
        return response()->json([
            'status' => true,
            'message' => 'City deleted successfully'
        ], 200);
    }


    public function convertKeysToCamelCase($array)
    {
        $camelCasedArray = [];
        foreach ($array as $key => $value) {
            // Anahtarı camelCase formatına dönüştür
            $camelKey = Str::camel($key);

            // Nesting arrayleri recursive olarak dönüştür
            if (is_array($value) || is_object($value)) {
                $value = $this->convertKeysToCamelCase((array) $value);
            }

            $camelCasedArray[$camelKey] = $value;
        }
        return $camelCasedArray;
    }
}
