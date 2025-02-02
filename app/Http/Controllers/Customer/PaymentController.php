<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = auth('customer')->user()
            ->payments()
            ->with(['invoice'])
            ->latest()
            ->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        $payment->load(['invoice.items', 'transaction']);
        return view('customer.payments.show', compact('payment'));
    }

    public function process(Transaction $transaction, Request $request)
    {
        $this->authorize('pay', $transaction);

        $request->validate([
            'payment_method' => 'required|in:credit_card,bank_transfer,cash',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $payment = $this->paymentService->processPayment($transaction, $request->all());
            return redirect()->route('customer.payments.show', $payment)
                ->with('success', 'تم إتمام عملية الدفع بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشلت عملية الدفع: ' . $e->getMessage());
        }
    }

    public function requestRefund(Payment $payment, Request $request)
    {
        $this->authorize('refund', $payment);

        if (!$payment->canRefund()) {
            return back()->with('error', 'لا يمكن استرداد هذا المبلغ');
        }

        try {
            $this->paymentService->processRefund($payment, $request->all());
            return redirect()->route('customer.payments.show', $payment)
                ->with('success', 'تم طلب استرداد المبلغ بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل طلب الاسترداد: ' . $e->getMessage());
        }
    }
}
