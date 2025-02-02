<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('تحديث حالة الدفع')
            ->greeting('مرحباً ' . $notifiable->name);

        switch ($this->payment->status) {
            case 'completed':
                $message->line('تم اكتمال عملية الدفع بنجاح.')
                    ->line('رقم المرجع: ' . $this->payment->reference_number)
                    ->line('المبلغ: ' . number_format($this->payment->amount, 2) . ' ريال')
                    ->action('عرض الفاتورة', route('customer.invoices.show', $this->payment->invoice));
                break;
            
            case 'failed':
                $message->line('عذراً، فشلت عملية الدفع.')
                    ->lineIf($this->payment->notes, 'السبب: ' . $this->payment->notes)
                    ->line('يرجى المحاولة مرة أخرى أو الاتصال بالدعم الفني.');
                break;
            
            case 'refunded':
                $message->line('تم إتمام عملية استرداد المبلغ بنجاح.')
                    ->line('رقم المرجع: ' . $this->payment->reference_number)
                    ->line('المبلغ المسترد: ' . number_format($this->payment->amount, 2) . ' ريال');
                break;
        }

        return $message->salutation('شكراً لك');
    }

    public function toArray($notifiable)
    {
        return [
            'payment_id' => $this->payment->id,
            'status' => $this->payment->status,
            'amount' => $this->payment->amount,
            'reference_number' => $this->payment->reference_number,
            'message' => $this->getStatusMessage()
        ];
    }

    protected function getStatusMessage()
    {
        switch ($this->payment->status) {
            case 'completed':
                return 'تم اكتمال عملية الدفع بنجاح';
            case 'failed':
                return 'فشلت عملية الدفع';
            case 'refunded':
                return 'تم استرداد المبلغ بنجاح';
            default:
                return 'تم تحديث حالة الدفع';
        }
    }
}
