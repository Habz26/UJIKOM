<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_books' => Book::count(),
            'books_loaned' => Book::where('stock', '<', 10)->count(), // Mock: low stock
            'total_loans' => Loan::count(),
            'active_loans' => Loan::where('status', 'dipinjam')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}

