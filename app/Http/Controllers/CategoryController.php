<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ], [
            'name.required' => 'İsim alanı gereklidir.',
            'name.string' => 'İsim alanı bir metin olmalıdır.',
        ]);

        // Doğrulama hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return Category::create($request->all());
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
        ], [
            'name.string' => 'The name field must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return $category;
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return response()->noContent();
    }
}
