<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    private array $commonPasswords = [
        '12345678',
        'password',
        'admin123',
        'qwerty123',
        'password123',
        '123456789',
        'admin',
        'root',
        'letmein',
        'welcome',
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check length and composition
        if (strlen($value) < 8) {
            return false;
        }

        if (!preg_match('/[a-z]/', $value) ||
            !preg_match('/[A-Z]/', $value) ||
            !preg_match('/[0-9]/', $value)) {
            return false;
        }

        // Check common passwords
        foreach ($this->commonPasswords as $common) {
            if (strtolower($value) === strtolower($common)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil dan angka. Tidak boleh menggunakan password umum seperti 12345678, password, admin123, dll.';
    }
}

