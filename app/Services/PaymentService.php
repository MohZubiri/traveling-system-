<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use App\Notifications\PaymentStatusUpdated;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function processPayment(Transaction $transaction, array $paymentData)
    {
        try {
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'customer_id' => $transaction->customer_id,
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'payment_method' => $paymentData['payment_method'],
                'status' => 'processing',
                'reference_number' => Payment::generateReference(),
                'notes' => $paymentData['notes'] ?? null
            ]);

            // Create invoice
            $invoice = Invoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $transaction->amount,
                'tax' => $transaction->amount * 0.15, // 15% VAT
                'total' => $transaction->amount * 1.15,
                'notes' => $paymentData['notes'] ?? null
            ]);

            // Create invoice item
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $transaction->description,
                'quantity' => 1,
                'unit_price' => $transaction->amount,
                'total' => $transaction->amount
            ]);

            // Process payment with payment gateway
            $paymentResult = $this->processWithGateway($payment, $paymentData);

            if ($paymentResult['success']) {
                $payment->update([
                    'status' => 'completed',
                    'payment_date' => now()
                ]);

                $transaction->update(['status' => 'completed']);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'notes' => $paymentResult['message']
                ]);

                $transaction->update(['status' => 'failed']);
            }

            // Send notification
            $payment->customer->notify(new PaymentStatusUpdated($payment));

            DB::commit();
            return $payment;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processRefund(Payment $payment, array $refundData)
    {
        try {
            DB::beginTransaction();

            if (!$payment->canRefund()) {
                throw new \Exception('This payment cannot be refunded.');
            }

            // Process refund with payment gateway
            $refundResult = $this->processRefundWithGateway($payment, $refundData);

            if ($refundResult['success']) {
                $payment->markAsRefunded();
                
                if ($payment->transaction) {
                    $payment->transaction->update(['status' => 'refunded']);
                }

                // Send notification
                $payment->customer->notify(new PaymentStatusUpdated($payment));
            } else {
                throw new \Exception($refundResult['message']);
            }

            DB::commit();
            return $payment;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function processWithGateway(Payment $payment, array $paymentData)
    {
        // Implement payment gateway integration here
        // This is a dummy implementation
        return [
            'success' => true,
            'message' => 'Payment processed successfully',
            'transaction_id' => 'GATE_' . uniqid()
        ];
    }

    protected function processRefundWithGateway(Payment $payment, array $refundData)
    {
        // Implement refund gateway integration here
        // This is a dummy implementation
        return [
            'success' => true,
            'message' => 'Refund processed successfully',
            'refund_id' => 'REF_' . uniqid()
        ];
    }
}
