<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the user's transactions.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $query = Transaction::where('user_id', $user->id)->orderBy('id', 'desc');

        // Filter by Transaction Type (credit, debit, refund)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Global Keyword Search (Description, Ref, Metadata, Amount)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                  ->orWhere('transaction_ref', 'like', "%$search%")
                  ->orWhere('metadata', 'like', "%$search%");
                
                // Numeric search for amount
                $numericSearch = str_replace([',', '₦'], '', $search);
                if (is_numeric($numericSearch)) {
                    $q->orWhere('amount', 'like', "%$numericSearch%");
                }
            });
        }

        // Filter by Date Range (Backup logic)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(10);

        return view('transactions', compact('transactions'));
    }
}
