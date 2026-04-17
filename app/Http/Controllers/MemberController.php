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
    public function index(Request $request): View
    {
        $members = User::withTrashed()
            ->when($request->filled('role'), fn($q) => $request->role === 'all' ? $q : $q->where('role', $request->role))
            ->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create(): View
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('members.index')
            ->with('success', 'Anggota baru berhasil ditambahkan!');
    }

    public function edit(User $member): View
    {
        return view('members.edit', compact('member'));
    }

    public function update(UpdateMemberRequest $request, User $member): RedirectResponse
    {
        $member->update($request->validated());

        return redirect()->route('members.index')
            ->with('success', 'Data anggota berhasil diupdate!');
    }

    public function destroy(User $member): RedirectResponse
    {
        $member->delete();

        return back()->with('success', 'Anggota berhasil dihapus!');
    }

    public function restore(User $member): RedirectResponse
    {
        $member->restore();

        return back()->with('success', 'Anggota berhasil dipulihkan!');
    }
}

