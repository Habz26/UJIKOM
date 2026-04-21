<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Menentukan apakah request ini diizinkan (selalu true untuk update member).
     *
     * @return bool Selalu true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk update member (email unique ignore current ID, password nullable).
     *
     * @return array<string, array|string> Rules untuk name, email (unique ignore), role, password nullable
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->member->id), 'max:255'],
            'role' => 'required|in:admin,siswa',
            'password' => 'nullable|string|min:8|confirmed',
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

