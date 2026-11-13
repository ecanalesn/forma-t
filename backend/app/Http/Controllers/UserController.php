<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users', 'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,student',
            'status' => 'required|integer',
            'phone' => 'nullable|string|regex:/^[0-9]{9}$/'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $requester = $request->user();

        if ($requester->user_id !== $user->user_id && $requester->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => [
                'sometimes', 'string', 'email', 'max:100',
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('users')->ignore($user->user_id, 'user_id'),
            ],
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:admin,student',
            'status' => 'sometimes|integer',
            'phone' => 'nullable|string|regex:/^[0-9]{9}$/',
            'profile_image' => 'nullable|string' // We'll accept base64 or a path
        ]);

        $data = $request->all();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Solo un admin puede cambiar el role o el status de un usuario
        if ($requester->role !== 'admin') {
            unset($data['role'], $data['status']);
        }

        // Keep the existing role if not provided in the request
        if (!isset($data['role'])) {
            $data['role'] = $user->role;
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
