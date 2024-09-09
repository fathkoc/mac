<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string',
            'plateCode' => 'required|string',
        ]);

        return City::create($request->all());
    }

    public function show($id)
    {
        return City::findOrFail($id);
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
        City::destroy($id);
        return response()->noContent();
    }
}
