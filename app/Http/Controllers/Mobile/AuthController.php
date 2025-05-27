<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends \App\Http\Controllers\Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password tidak valid'
            ], 401);
        }

        $domain = substr(strrchr($request->email, '@'), 1);
        
        $allowed_domains = [
            'dosen' => ['it.lecturer.pens.ac.id'],
            'mahasiswa' => ['it.student.pens.ac.id']
        ];

        $role = null;
        foreach ($allowed_domains as $role_name => $domains) {
            if (in_array($domain, $domains)) {
                $role = $role_name;
                break;
            }
        }

        if (!$role) {
            return response()->json([
                'message' => 'Domain email tidak diizinkan'
            ], 403);
        }

        if (!$user->hasRole($role)) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk role ini'
            ], 403);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'role' => $role,
            'message' => 'Login berhasil'
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
    public function user(Request $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();

        return response()->json([
            'user' => $user,
            'roles' => $roles
        ]);
    }
}
