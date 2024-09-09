<?php

namespace App\Http\Controllers;

use App\Models\ImagePath;
use Illuminate\Http\Request;

class ImagePathController extends Controller
{
    public function index()
    {
        return ImagePath::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'cart_id' => 'required|exists:carts,id',
        ]);

        return ImagePath::create($request->all());
    }

    public function show($id)
    {
        return ImagePath::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'path' => 'string',
            'cart_id' => 'exists:carts,id',
        ]);

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
