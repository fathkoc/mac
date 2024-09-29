<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        $city = City::all();

        return response()->json([
            'status' => true,
            'success' => true,
            'City' => $city
        ], 200);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'plateCode' => 'required|string',
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'plateCode.required' => 'The plate code field is required.',
            'plateCode.string' => 'The plate code field must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return City::create($request->all());
    }

    public function show($id)
    {
        return City::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'plateCode' => 'string',
        ], [
            'name.string' => 'The name field must be a string.',
            'plateCode.string' => 'The plate code field must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $city = City::findOrFail($id);
        $city->update($request->all());

        return $city;
    }

    public function destroy($id)
    {
        City::destroy($id);
        return response()->noContent();
    }
}
