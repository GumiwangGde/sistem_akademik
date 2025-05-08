<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi pengguna
        $request->authenticate();

        // Regenerasi sesi setelah login
        $request->session()->regenerate();

        // Tentukan role berdasarkan domain email
        $user = Auth::user();
        
        if (strpos($user->email, '@it.admin.pens.ac.id') !== false) {
            $user->assignRole('admin');
        } elseif (strpos($user->email, '@it.lecturer.pens.ac.id') !== false) {
            $user->assignRole('dosen');
        } elseif (strpos($user->email, '@it.student.pens.ac.id') !== false) {
            $user->assignRole('mahasiswa');
        }

        // Setelah login, arahkan ke dashboard sesuai dengan role
        if (Auth::user()->hasRole('admin')) {
            return redirect()->to('admin/dashboard'); // Arahkan ke dashboard admin
        }

        if (Auth::user()->hasRole('dosen')) {
            return redirect()->to('dosen'); // Arahkan ke dashboard dosen
        }

        if (Auth::user()->hasRole('mahasiswa')) {
            return redirect()->to('dashboard'); // Arahkan ke dashboard mahasiswa
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
