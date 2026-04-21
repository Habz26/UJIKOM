<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku dengan pencarian multi-kolom dan relasi kategori.
     *
     * @param Request $request Parameter pencarian 'search' untuk filter title, author, kategori, year, publisher
     * @return View Halaman index buku dengan data paginasi dan daftar kategori
     */
    public function index(Request $request): View
    {
        $query = Book::with('kategori');

        if ($search = $request->get('search')) {
            $query
                ->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%")
                ->orWhereHas('kategori', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('year', 'like', "%{$search}%")
                ->orWhere('publisher', 'like', "%{$search}%");
        }

        $search = $request->get('search');
        $books = $query->latest()->paginate(10);
        $categories = \App\Models\Category::all();

        return view('books.index', compact('books', 'search', 'categories'));
    }

    /**
     * Show book data for AJAX modal (JSON).
     */
    /**
     * Mengembalikan data buku dalam format JSON untuk AJAX modal.
     *
     * @param Book $book Model Book yang akan ditampilkan
     * @return \Illuminate\Http\Response JSON response berisi data buku lengkap
     */
    public function show(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Menampilkan form untuk menambahkan buku baru beserta daftar kategori.
     *
     * @return View Halaman form create buku dengan data kategori
     */
    public function create(): View
    {
        $categories = \App\Models\Category::all();

        return view('books.create', compact('categories'));
    }

    /**
     * Menyimpan buku baru ke database setelah validasi.
     *
     * @param StoreBookRequest $request Data validasi untuk buku baru (title, author, year, stock, publisher, id_kategori)
     * @return RedirectResponse Redirect ke index dengan pesan sukses
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        Book::create($request->validated());

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit untuk buku tertentu.
     *
     * @param Book $book Model Book yang akan diedit
     * @return View Halaman form edit dengan data buku
     */
    public function edit(Book $book): View
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Memperbarui data buku yang ada di database.
     *
     * @param UpdateBookRequest $request Data validasi untuk update buku
     * @param Book $book Model Book yang akan diupdate
     * @return RedirectResponse Redirect ke index dengan pesan sukses
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $book->update($request->validated());

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate!');
    }

    /**
     * Menghapus buku dari database (soft delete).
     *
     * @param Book $book Model Book yang akan dihapus
     * @return RedirectResponse Redirect ke index dengan pesan sukses
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
