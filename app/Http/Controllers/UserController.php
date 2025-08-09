<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:10'
        ]);

        $validate['password'] = Hash::make($validate['password']);

        $user = User::create($validate);
        return response()->json($user, 201);
    }

    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User Not found'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User Not found'], 404);
        }

        $validate = $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,',
            'password' => 'nullable|string|min:10'
        ]);

        if (!empty($validate['password'])) {
            $validate['password'] = Hash::make($validate['password']);
        } else {
            unset($validate['password']);
        }

        $user->update($validate);

        return response()->json($user);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User Deleted']);
    }
}
