<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    // REGISTER
public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|max:50|unique:users,username',
        'email' => 'required|string|email|max:100|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => $validator->errors()
        ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    try {

        $role = ucfirst(strtolower($request->role ?? 'User'));



        if (!in_array($role, ['Admin', 'User'])) {
            $role = 'User';
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        $token = $user->createToken('register_token')->plainTextToken;

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], HttpResponse::HTTP_OK);

    } catch (Exception $e) {
        return response()->json([
            'status' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $e->getMessage()
        ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}


    // LOGIN
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => $validator->errors()
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => HttpResponse::HTTP_UNAUTHORIZED,
                'message' => 'Email atau password salah'
            ], HttpResponse::HTTP_UNAUTHORIZED);
        }


        $user->tokens()->delete();

        $token = $user->createToken('login_token')->plainTextToken;

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'message' => 'Login berhasil',
            'user' => $user,
            'role' => $user->role,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], HttpResponse::HTTP_OK);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'message' => 'Logout berhasil'
        ], HttpResponse::HTTP_OK);
    }
}
