<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $payments = Payment::with(['customer', 'invoice'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->payment_method, function ($query, $method) {
                return $query->where('payment_method', $method);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['customer', 'invoice.items', 'transaction']);
        return view('admin.payments.show', compact('payment'));
    }

    public function processRefund(Payment $payment, Request $request)
    {
        try {
            $this->paymentService->processRefund($payment, $request->all());
            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'تم استرداد المبلغ بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل استرداد المبلغ: ' . $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $query = Payment::with(['customer'])
            ->when($request->start_date, function ($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function ($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            });

        $totalPayments = $query->count();
        $totalAmount = $query->sum('amount');
        $completedPayments = $query->where('status', 'completed')->count();
        $completedAmount = $query->where('status', 'completed')->sum('amount');
        $pendingPayments = $query->where('status', 'pending')->count();
        $pendingAmount = $query->where('status', 'pending')->sum('amount');
        $refundedPayments = $query->where('status', 'refunded')->count();
        $refundedAmount = $query->where('status', 'refunded')->sum('amount');

        $paymentsByMethod = $query->groupBy('payment_method')
            ->selectRaw('payment_method, count(*) as count, sum(amount) as total')
            ->get();

        $dailyPayments = $query->groupBy('date')
            ->selectRaw('DATE(created_at) as date, count(*) as count, sum(amount) as total')
            ->orderBy('date')
            ->get();

        return view('admin.payments.report', compact(
            'totalPayments',
            'totalAmount',
            'completedPayments',
            'completedAmount',
            'pendingPayments',
            'pendingAmount',
            'refundedPayments',
            'refundedAmount',
            'paymentsByMethod',
            'dailyPayments'
        ));
    }
}
