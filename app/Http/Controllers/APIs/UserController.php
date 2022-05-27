<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User detail.',
            'data' => new UserResource($user)
        ], 200);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create($input);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => new UserResource($user)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $user->update($input);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => new UserResource($user)
        ], 200);
    }
}
