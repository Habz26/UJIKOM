<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar anggota (members) dengan filter role opsional.
     *
     * @param Request $request Objek request HTTP yang berisi parameter filter 'role'
     * @return View Objek view dengan data paginasi anggota
     */
    public function index(Request $request): View
    {
        $members = User::query()
            ->when($request->filled('role'), fn($q) => $request->role === 'all' ? $q : $q->where('role', $request->role))
            ->paginate(10);

        return view('members.index', compact('members'));
    }

    /**
     * Menampilkan form untuk membuat anggota baru.
     *
     * @return View Objek view form create anggota
     */
    public function create(): View
    {
        return view('members.create');
    }

    /**
     * Menyimpan data anggota baru ke database.
     *
     * @param StoreMemberRequest $request Data validasi untuk pembuatan anggota baru
     * @return RedirectResponse Redirect ke index dengan pesan sukses
     */
    public function store(StoreMemberRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('members.index')
            ->with('success', 'Anggota baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit untuk anggota tertentu.
     *
     * @param User $member Model User anggota yang akan diedit
     * @return View Objek view form edit dengan data anggota
     */
    public function edit(User $member): View
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Memperbarui data anggota yang ada di database.
     * Password hanya diupdate jika diisi, kosong akan diabaikan.
     *
     * @param UpdateMemberRequest $request Data validasi untuk update anggota
     * @param User $member Model User anggota yang akan diupdate
     * @return RedirectResponse Redirect ke index dengan pesan sukses
     */
    public function update(UpdateMemberRequest $request, User $member): RedirectResponse
    {
        $data = $request->validated();
        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        $member->update($data);

        return redirect()->route('members.index')
            ->with('success', 'Data anggota berhasil diupdate!');
    }

    /**
     * Menghapus anggota dari database (soft delete).
     *
     * @param User $member Model User anggota yang akan dihapus
     * @return RedirectResponse Kembali ke halaman sebelumnya dengan pesan sukses
     */
    public function destroy(User $member): RedirectResponse
    {
        $member->delete();

        return back()->with('success', 'Anggota berhasil dihapus!');
    }

    /**
     * Memulihkan anggota yang dihapus (soft delete restore).
     *
     * @param User $member Model User anggota yang akan dipulihkan
     * @return RedirectResponse Kembali ke halaman sebelumnya dengan pesan sukses
     */
    public function restore(User $member): RedirectResponse
    {
        $member->restore();

        return back()->with('success', 'Anggota berhasil dipulihkan!');
    }
}

