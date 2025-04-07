<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        response()->json([
            'message' => 'User created successfully',
            'user' => $request->all()
        ], 201);
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'name' => 'nullable|string',
            'avatar' => 'nullable|url'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'avatar' => $request->avatar,
        ]);

        return response()->json($user, 201);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'nullable|string',
            'avatar' => 'nullable|url'
        ]);

        $user->update($request->all());
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
