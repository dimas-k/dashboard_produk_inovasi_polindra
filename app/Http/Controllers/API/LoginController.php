<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class LoginController extends BaseController
{
    /**
     * Retrieve all users
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => UserResource::collection($users),
        ]);
    }

    /**
     * Login API (Authenticate by username and password)
     */
    public function authenticate(Request $request): JsonResponse
    {
        // Validasi input username dan password
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Attempt login menggunakan username dan password
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            // Buat token akses
            $token = $user->createToken('UserLoginToken')->plainTextToken;

            // Tentukan URL pengalihan berdasarkan role pengguna
            $redirectUrl = $user->role === 'admin'
                ? '/admin/dashboard'
                : ($user->role === 'ketua_kbk' ? '/k-kbk/dashboard' : '/home');

            return response()->json([
                'success' => true,
                'message' => 'User login successfully.',
                'data' => [
                    'token' => $token,
                    'user' => new UserResource($user),
                    'redirect_url' => $redirectUrl, // URL redirect berdasarkan role
                ],
            ], 200);
        }

        // Jika login gagal
        return response()->json([
            'success' => false,
            'message' => 'Unauthorised.',
            'errors' => ['error' => 'Invalid username or password.'],
        ], 401);
    }

    /**
     * Logout API
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            // Hapus semua token yang terkait dengan pengguna yang sedang login
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No user is currently logged in.',
        ], 401);
    }
}
