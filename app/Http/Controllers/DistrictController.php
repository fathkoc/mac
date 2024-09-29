<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function index()
    {
        return District::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'postCode' => 'required|string',
            'city_id' => 'required|exists:cities,id',
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'postCode.required' => 'The postal code field is required.',
            'postCode.string' => 'The postal code field must be a string.',
            'city_id.required' => 'The city ID field is required.',
            'city_id.exists' => 'The selected city ID is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        return District::create($request->all());
    }

    public function show($id)
    {
        return District::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'postCode' => 'string',
            'city_id' => 'exists:cities,id',
        ], [
            'name.string' => 'The name field must be a string.',
            'postCode.string' => 'The postal code field must be a string.',
            'city_id.exists' => 'The selected city ID is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


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
