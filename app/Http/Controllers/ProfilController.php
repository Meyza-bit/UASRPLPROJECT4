<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Halaman Profil.
     */
    public function index()
    {
        $user = auth()->user();

        // Jumlah pesanan yang pernah dibuat (yang dibatalkan nggak dihitung)
        $jumlahBooking = Penyewaan::where('user_id', $user->id)
            ->where('status', '!=', 'batal')
            ->count();

        // Total uang yang sudah dikeluarkan
        $totalPengeluaran = Penyewaan::where('user_id', $user->id)
            ->whereIn('status', ['aktif', 'selesai'])
            ->sum('total');

        return view('profil.index', compact('user', 'jumlahBooking', 'totalPengeluaran'));
    }

    /**
     * Simpan perubahan data diri.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email ini sudah dipakai akun lain.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex'    => 'Nomor HP hanya boleh berisi angka.',
        ]);

        $user->update($data);

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password dari halaman Profil.
     */
    public function gantiPassword(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'password_lama' => ['required'],
            'password'      => ['required', 'confirmed', Password::min(8)],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password.confirmed'     => 'Konfirmasi password tidak sama.',
            'password.min'           => 'Password minimal 8 karakter.',
        ]);

        // Pastikan yang mengganti memang pemilik akun
        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        $user->update(['password' => $data['password']]);

        return back()->with('sukses', 'Password berhasil diubah.');
    }
}