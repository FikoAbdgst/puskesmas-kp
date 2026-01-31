<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Pastikan menggunakan Illuminate\Http\Request
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Tentukan apakah input adalah email atau NIK
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';

        // 3. Coba melakukan login
        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password])) {
            return $this->authenticated($request, Auth::user());
        }

        // Jika gagal
        return back()->withErrors(['login' => 'Kredensial tidak cocok dengan data kami.']);
    }

    protected function authenticated(Request $request, $user)
    {
        // Pembedaan redirect berdasarkan role setelah login
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
