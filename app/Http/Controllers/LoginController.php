<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman Masuk.
     */
    public function showLogin()
    {
        return view('login.login');
    }

    /**
     * Proses form Masuk.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $kredensial = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba login. Auth::attempt otomatis mencocokkan password yang di-hash.
        if (! Auth::attempt($kredensial, $request->boolean('ingat'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');   // email tetap terisi, password dikosongkan
        }

        // 3. Ganti session ID supaya aman dari session fixation
        $request->session()->regenerate();

        // 4. Admin ke dashboard, pelanggan ke beranda
        return Auth::user()->isAdmin()
            ? redirect()->intended('/admin')
            : redirect()->intended('/');
    }

    /**
     * Tampilkan halaman Daftar Akun.
     */
    public function showRegister()
    {
        return view('login.register');
    }

    /**
     * Proses form Daftar Akun.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'no_hp'    => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'setuju'   => ['accepted'],
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar.',
            'no_hp.required'     => 'Nomor HP wajib diisi.',
            'no_hp.regex'        => 'Nomor HP hanya boleh berisi angka.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min'       => 'Password minimal 8 karakter.',
            'setuju.accepted'    => 'Kamu harus menyetujui syarat dan ketentuan.',
        ]);

        // Simpan user baru. Password otomatis di-hash lewat casts() di model User.
        $user = User::create([
            'email'    => $data['email'],
            'no_hp'    => $data['no_hp'],
            'password' => $data['password'],
            'role'     => 'pelanggan',
        ]);

        // Langsung login setelah daftar
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('sukses', 'Akun berhasil dibuat. Selamat datang di Culture Bike.');
    }

    /**
     * Keluar dari akun.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}