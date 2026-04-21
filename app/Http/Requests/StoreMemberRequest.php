<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Menentukan apakah request ini diizinkan (selalu true untuk store member).
     *
     * @return bool Selalu true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk pembuatan member baru.
     *
     * @return array<string, string> Rules untuk name, email (unique), password (confirmed), role
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,siswa',
        ];
    }

    /**
     * Pesan error custom dalam bahasa Indonesia.
     *
     * @return array<string, string> Pesan untuk email.unique dan password.confirmed
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}

