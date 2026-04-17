<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function books(): View
    {
        $books = Book::with('kategori', 'loans')->withTrashed()->get();
        return view('reports.books', compact('books'));
    }

    public function loans(): View
    {
        $loans = Loan::with(['book', 'user'])->latest()->paginate(50);
        return view('reports.loans', compact('loans'));
    }

    public function members(Request $request): View
    {
        $query = User::withCount('loans')
            ->leftJoin('loans', 'users.id', '=', 'loans.user_id')
            ->select('users.*', \DB::raw('SUM(loans.fine) as total_fine'), \DB::raw('COUNT(loans.id) as loans_count'))
            ->groupBy('users.id', 'users.name', 'users.email', 'users.role');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(20);

        return view('reports.members', compact('members'));
    }

}

