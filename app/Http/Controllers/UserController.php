<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; // We'll create this later

class UserController extends Controller
{
    // UserController.php


    public function showProfile(Request $request)
{
    try {
        $token = $request->cookie('token'); // get token from cookie
        if (!$token) {
            return redirect('/login')->with('error', 'You must log in first.');
        }

        $user = JWTAuth::setToken($token)->authenticate();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid or expired session.');
        }

        return view('profile', ['user' => $user]);
    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Session expired, please log in again.');
    }
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all(),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Mail::to($user->email)->send(new WelcomeEmail($user));

        // return redirect('/')->with('success', 'Registration successful! Check your email.');

        return response()->json($user, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user){
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user){
            return response()->json(['mmessage'=> 'User not found'],404);
        }
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response()->json(['message' => 'Updated Successfully','data'=>$user],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user){
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message'=>'User deleted successfully']);
    }

}