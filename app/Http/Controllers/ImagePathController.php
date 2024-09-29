<?php

namespace App\Http\Controllers;

use App\Models\ImagePath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImagePathController extends Controller
{
    public function index()
    {
        return ImagePath::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'cart_id' => 'required|exists:carts,id',
        ], [
            'path.required' => 'The path field is required.',
            'path.string' => 'The path field must be a string.',
            'cart_id.required' => 'The cart ID field is required.',
            'cart_id.exists' => 'The selected cart ID is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        return ImagePath::create($request->all());
    }

    public function show($id)
    {
        return ImagePath::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'string',
            'cart_id' => 'exists:carts,id',
        ], [
            'path.string' => 'The path field must be a string.',
            'cart_id.exists' => 'The selected cart ID is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $imagePath = ImagePath::findOrFail($id);
        $imagePath->update($request->all());

        return $imagePath;
    }

    public function destroy($id)
    {
        ImagePath::destroy($id);
        return response()->noContent();
    }
}
