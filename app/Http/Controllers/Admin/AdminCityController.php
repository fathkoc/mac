<?php

// app/Http/Controllers/CityController.php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminCityController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'plateCode' => 'required|string|max:10', // Ensure plateCode is required
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'plateCode.required' => 'Plate code is required.',
            'plateCode.string' => 'Plate code must be a string.',
            'plateCode.max' => 'Plate code may not be greater than 10 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        City::create($request->all());

        return redirect()->route('cities.index')->with('success', 'City created successfully.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'plateCode' => 'required|string|max:10', // Ensure plateCode is required
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'plateCode.required' => 'Plate code is required.',
            'plateCode.string' => 'Plate code must be a string.',
            'plateCode.max' => 'Plate code may not be greater than 10 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $city = City::findOrFail($id);
        $city->update($request->all());

        return redirect()->route('cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy($id)
    {
        City::findOrFail($id)->delete();

        return redirect()->to('admin/cities')->with('success', 'City deleted successfully.');
    }
}
