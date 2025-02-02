<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth('customer')->user();
        
        $transactions = Transaction::where('customer_id', $customer->id)
            ->when($request->type, function ($query, $type) {
                return $query->where('service_type', $type);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function ($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalAmount = Transaction::where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingAmount = Transaction::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->sum('amount');

        return view('customer.transactions.index', compact(
            'transactions',
            'totalAmount',
            'pendingAmount'
        ));
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('customer.transactions.show', compact('transaction'));
    }

    public function receipt(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('customer.transactions.receipt', compact('transaction'));
    }
}
