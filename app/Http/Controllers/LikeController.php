<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function index()
    {
        return Like::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'cart_id' => 'required|exists:carts,id',
            'user_id' => 'required|exists:accounts,id',
        ]);

        return Like::create($request->all());
    }

    public function show($id)
    {
        return Like::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'string',
            'cart_id' => 'exists:carts,id',
            'user_id' => 'exists:accounts,id',
        ]);

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
