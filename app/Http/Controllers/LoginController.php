<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /**
     * Mengarahkan pengguna setelah login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return route('admin.dashboard');
            case 'dosen':
                return route('dosen.dashboard');
            case 'mahasiswa':
                return route('dashboard.mahasiswa');
            default:
                return route('dashboard');
        }
    }

    /**
     * Melakukan logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
