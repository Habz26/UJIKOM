<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Menentukan apakah user diizinkan update buku (selalu true).
     *
     * @return bool Selalu true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk update buku (sama seperti store).
     *
     * @return array<string, string> Rules lengkap untuk buku
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.date('Y'),
            'stock' => 'required|integer|min:0',
            'publisher' => 'required|string|max:255',
            'id_kategori' => 'required|exists:categories,id',
        ];
    }

    /**
     * Pesan error custom untuk validasi update buku.
     *
     * @return array<string, string> Pesan detail
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'publisher.required' => 'Penerbit wajib diisi.',
            'id_kategori.required' => 'Kategori wajib diisi.',
            'id_kategori.exists' => 'Kategori tidak valid.',
        ];
    }
}

