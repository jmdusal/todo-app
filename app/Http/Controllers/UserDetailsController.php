<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
    public function index()
    {
        return response()->json(UserDetail::with('user')->get(), 200);
    }

    public function store(Request $request)
    {
        $user = UserDetail::create($request->all());
        return response()->json($user, 201);
    }

    public function show($id)
    {
        return UserDetail::with('users')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = UserDetail::findOrFail($id);
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        UserDetail::destroy($id);
        return response()->json(null, 204);
    }
}
