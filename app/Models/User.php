<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'no_hp', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah user ini admin. Dipakai di middleware & navbar.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Nama yang ditampilkan di navbar.
     * Kalau user belum mengisi nama, pakai bagian depan emailnya.
     */
    public function getNamaTampilAttribute(): string
    {
        return $this->name ?: explode('@', $this->email)[0];
    }
}