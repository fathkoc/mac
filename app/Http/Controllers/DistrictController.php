<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use App\Models\City;

class DistrictController extends Controller
{
    public function index()
    {
        return District::all();
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
        // Verilen id'ye sahip şehri bul
        $city = City::find($id);

        // Şehir bulunamazsa hata döndür
        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        // Şehre ait ilçeleri al
        $districts = $city->districts;

     
        return response()->json([
            'status' => true,
            'Districts' => $districts
        ], 200);
    }
}
