<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'role', 'photo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
use HasFactory, Notifiable, SoftDeletes;

    /**
     * Mendefinisikan casting attributes untuk model User.
     *
     * @return array<string, string> Array konfigurasi casting (datetime, hashed, string)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    /**
     * Accessor untuk URL foto profil, fallback ke default jika tidak ada.
     *
     * @return string URL foto profil lengkap
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : asset('images/default-avatar.png');
    }

    /**
     * Relasi one-to-many dengan model Loan (peminjaman milik user ini).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Relasi hasMany Loan
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}

