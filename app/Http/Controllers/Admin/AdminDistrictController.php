<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminDistrictController extends Controller
{
    public function index()
    {
        $districts = District::with('city')->get();
        return view('admin.districts.index', compact('districts'));
    }

    public function create()
    {
        $cities = City::all();
        return view('admin.districts.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'postCode' => 'required|string|max:10',
            'city_id' => 'required|exists:cities,id',
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'postCode.required' => 'The post code field is required.',
            'postCode.string' => 'The post code must be a string.',
            'postCode.max' => 'The post code may not be greater than 10 characters.',
            'city_id.required' => 'The city field is required.',
            'city_id.exists' => 'The selected city does not exist.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        District::create($request->all());

        return redirect()->route('districts.index')->with('success', 'District created successfully.');
    }

    public function edit($id)
    {
        $district = District::findOrFail($id);
        $cities = City::all();
        return view('admin.districts.edit', compact('district', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'postCode' => 'required|string|max:10',
            'city_id' => 'required|exists:cities,id',
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'postCode.required' => 'The post code field is required.',
            'postCode.string' => 'The post code must be a string.',
            'postCode.max' => 'The post code may not be greater than 10 characters.',
            'city_id.required' => 'The city field is required.',
            'city_id.exists' => 'The selected city does not exist.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $district = District::findOrFail($id);
        $district->update($request->all());

        return redirect()->route('districts.index')->with('success', 'District updated successfully.');
    }

    public function destroy($id)
    {
        District::findOrFail($id)->delete();

        return redirect()->to('admin/districts')->with('success', 'District deleted successfully.');
    }
}
