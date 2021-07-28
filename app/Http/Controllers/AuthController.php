<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{

    public function index() {
        $users = User::with('roles')->paginate(10);

        return response()->json($users);
    }

    public function user($user_id) {
        $user = User::where('id', $user_id)->with('roles')->firstOrFail();

        return response()->json($user);
    }

    public function update(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        $user->update(['name' => $request->name]);
        $user->roles()->sync($role->id);

        return response()->json(['message' => 'Role attached to user']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $role = Role::findOrFail($request->role_id);

        $user->roles()->sync($role->id);

        return response()->json(['message' => 'User created successfully']);

    }


    public function profile()
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'user_name' => auth()->user()->name,
            'user_email' => auth()->user()->email,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (auth()->factory()->getTTL() * 60 )
        ]);
    }

    protected function guard() {
        return Auth::guard();
    }
}
