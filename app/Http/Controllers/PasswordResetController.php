<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as AturanPassword;

class PasswordResetController extends Controller
{
    /**
     * Halaman "Lupa Password" — user memasukkan emailnya.
     */
    public function showLupaPassword()
    {
        return view('login.lupa-password');
    }

    /**
     * Kirim link reset ke email user.
     */
    public function kirimLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        // Laravel yang bikin token & kirim emailnya.
        $status = Password::sendResetLink($request->only('email'));

        // Pesannya sengaja sama walaupun email nggak terdaftar,
        // supaya orang nggak bisa nebak-nebak email mana yang punya akun.
        return back()->with('sukses', 'Kalau email tersebut terdaftar, link reset password sudah dikirim.');
    }

    /**
     * Halaman ganti password, dibuka dari link di email.
     */
    public function showResetPassword(Request $request, string $token)
    {
        return view('login.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Simpan password baru.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', AturanPassword::min(8)],
        ], [
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => $password,      // otomatis di-hash lewat casts() di model User
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PasswordReset) {
            return redirect()->route('login')->with('sukses', 'Password berhasil diubah. Silakan masuk.');
        }

        // Token salah / kedaluwarsa (link cuma berlaku 60 menit)
        return back()->withErrors(['email' => 'Link reset tidak valid atau sudah kedaluwarsa.']);
    }
}