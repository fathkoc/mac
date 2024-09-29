<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function index()
    {
        return Like::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'cart_id' => 'required|exists:carts,id',
            'user_id' => 'required|exists:accounts,id',
        ], [
            'type.required' => 'The type field is required.',
            'type.string' => 'The type field must be a string.',
            'cart_id.required' => 'The cart ID field is required.',
            'cart_id.exists' => 'The selected cart ID is invalid.',
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user ID is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return Like::create($request->all());
    }

    public function show($id)
    {
        return Like::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'string',
            'cart_id' => 'exists:carts,id',
            'user_id' => 'exists:accounts,id',
        ], [
            'type.string' => 'The type field must be a string.',
            'cart_id.exists' => 'The selected cart ID is invalid.',
            'user_id.exists' => 'The selected user ID is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $like = Like::findOrFail($id);
        $like->update($request->all());

        return $like;
    }

    public function destroy($id)
    {
        Like::destroy($id);
        return response()->noContent();
    }
}
