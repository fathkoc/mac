<?php
// app/Http/Controllers/Admin/AccountController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAccountController extends Controller
{
    // Display list of accounts
    public function index()
    {
        $accounts = Account::with(['city', 'district'])->get();
        return view('admin.accounts.index', compact('accounts'));
    }

    // Show the form for creating a new account
    public function create()
    {
        $cities = City::all();
        $districts = District::all();
        return view('admin.accounts.create', compact('cities', 'districts'));
    }

    // Store a new account in the database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'address' => 'nullable|string|max:255',
            'photoURL' => 'nullable|url',
            'activated' => 'nullable|boolean',
            'role' => 'nullable|string',
            'referenceCode' => 'nullable|string',
        ], [
            'phoneNumber.required' => 'Phone number is required.',
            'phoneNumber.string' => 'Phone number must be a valid string.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name cannot be longer than 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'city_id.required' => 'City is required.',
            'city_id.exists' => 'The selected city does not exist.',
            'district_id.required' => 'District is required.',
            'district_id.exists' => 'The selected district does not exist.',
            'address.string' => 'Address must be a valid string.',
            'address.max' => 'Address cannot be longer than 255 characters.',
            'photoURL.url' => 'Photo URL must be a valid URL.',
            'activated.boolean' => 'Activated must be true or false.',
            'role.string' => 'Role must be a valid string.',
            'referenceCode.string' => 'Reference code must be a valid string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['activated'] = $request->has('activated') ? $request->input('activated') : false;

        Account::create($data);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }


    // Show the form for editing an existing account
    public function edit(Account $account)
    {
        $cities = City::all();
        $districts = District::all();
        return view('admin.accounts.edit', compact('account', 'cities', 'districts'));
    }

    // Update an existing account in the database
    public function update(Request $request, Account $account)
    {
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email,' . $account->id,
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'address' => 'nullable|string|max:255',
            'photoURL' => 'nullable|url',
            'activated' => 'nullable|boolean',
            'role' => 'nullable|string',
            'referenceCode' => 'nullable|string',
        ], [
            'phoneNumber.required' => 'Phone number is required.',
            'phoneNumber.string' => 'Phone number must be a valid string.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name cannot be longer than 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'city_id.required' => 'City is required.',
            'city_id.exists' => 'The selected city does not exist.',
            'district_id.required' => 'District is required.',
            'district_id.exists' => 'The selected district does not exist.',
            'address.string' => 'Address must be a valid string.',
            'address.max' => 'Address cannot be longer than 255 characters.',
            'photoURL.url' => 'Photo URL must be a valid URL.',
            'activated.boolean' => 'Activated must be true or false.',
            'role.string' => 'Role must be a valid string.',
            'referenceCode.string' => 'Reference code must be a valid string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['activated'] = $request->has('activated') ? $request->input('activated') : $account->activated;

        $account->update($data);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }


    // Delete an account from the database
    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->to('admin/accounts')->with('success', 'Account deleted successfully.');
    }
}
