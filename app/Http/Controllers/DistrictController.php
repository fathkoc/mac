<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Str;

class DistrictController extends Controller
{
    public function index()
    {
        // Tüm District kayıtlarını al
        $districts = District::all();

        // Gizlenmesi gereken alanları ayarla
        $districts->makeHidden(['created_at', 'updated_at']);

        // Verileri camelCase formatına dönüştür
        $camelCasedDistricts = $this->convertKeysToCamelCase($districts->toArray());

        // Yanıtı döndür
        return response()->json([
            'status' => true,
            'districts' => $camelCasedDistricts
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'postCode' => 'required|string',
            'city_id' => 'required|exists:cities,id',
        ]);

        return District::create($request->all());
    }

    public function show($id)
    {
        return District::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
            'postCode' => 'string',
            'city_id' => 'exists:cities,id',
        ]);

        $district = District::findOrFail($id);
        $district->update($request->all());

        return $district;
    }

    public function destroy($id)
    {
        District::destroy($id);
        return response()->noContent();
    }

    public function getDistrict($id)
    {
        // Verilen ID'ye sahip şehri bul
        $city = City::find($id);

        // Şehir bulunamazsa hata döndür
        if (!$city) {
            return response()->json([
                'status' => false,
                'message' => 'City not found'
            ], 404);
        }

        // Şehre ait ilçeleri al
        $districts = $city->districts;

        // Gizlenmesi gereken alanları ayarla
        $districts->makeHidden(['created_at', 'updated_at']);

        // Verileri camelCase formatına dönüştür
        $camelCasedDistricts = $this->convertKeysToCamelCase($districts->toArray());

        // Yanıtı döndür
        return response()->json([
            'status' => true,
            'districts' => $camelCasedDistricts
        ], 200);
    }


    private function convertKeysToCamelCase($array)
    {
        $camelCasedArray = [];
        foreach ($array as $key => $value) {
            // Convert the key to camel case
            $camelKey = Str::camel($key);

            // Recursively convert nested arrays
            if (is_array($value) || is_object($value)) {
                $value = $this->convertKeysToCamelCase((array) $value);
            }

            $camelCasedArray[$camelKey] = $value;
        }
        return $camelCasedArray;
    }

}
