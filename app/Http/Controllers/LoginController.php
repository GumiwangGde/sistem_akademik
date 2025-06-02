<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers; 

class LoginController extends Controller
{
    protected function redirectTo()
    {
        return route('admin.dashboard');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login') 
                ->withErrors(['email' => 'Akses ditolak. Hanya admin yang diizinkan login melalui halaman ini.']);
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
