<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use Illuminate\Validation\ValidationException; 

class AuthenticatedSessionController extends Controller
{
    public function create() 
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();
        $userEmail = strtolower($user->email); 

        $adminDomain = 'it.admin.pens.ac.id';

        if (Str::endsWith($userEmail, '@' . $adminDomain)) {
            if (!$user->hasRole('admin')) { 
                 $user->assignRole('admin');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard')); 
        } else {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akses ditolak. Anda tidak memiliki izin untuk login melalui halaman ini.',
            ]);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
