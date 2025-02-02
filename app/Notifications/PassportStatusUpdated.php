<?php

namespace App\Notifications;

use App\Models\Passport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PassportStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $passport;

    public function __construct(Passport $passport)
    {
        $this->passport = $passport;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('تحديث حالة طلب تجديد الجواز')
            ->greeting('مرحباً ' . $notifiable->name);

        switch ($this->passport->status) {
            case 'processing':
                $message->line('تم استلام طلب تجديد الجواز الخاص بك وهو قيد المعالجة.');
                break;
            
            case 'ready':
                $message->line('جواز السفر الخاص بك جاهز للاستلام.')
                    ->line('موعد الاستلام: ' . $this->passport->pickup_date->format('Y-m-d'));
                break;
            
            case 'delivered':
                $message->line('تم تسليم جواز السفر الخاص بك بنجاح.');
                break;
            
            case 'rejected':
                $message->line('عذراً، تم رفض طلب تجديد الجواز الخاص بك.')
                    ->lineIf($this->passport->notes, 'السبب: ' . $this->passport->notes);
                break;
        }

        return $message
            ->line('رقم الطلب: #' . $this->passport->id)
            ->action('عرض التفاصيل', route('customer.passports.show', $this->passport))
            ->salutation('شكراً لك');
    }

    public function toArray($notifiable)
    {
        return [
            'passport_id' => $this->passport->id,
            'status' => $this->passport->status,
            'message' => $this->getStatusMessage(),
            'notes' => $this->passport->notes
        ];
    }

    protected function getStatusMessage()
    {
        switch ($this->passport->status) {
            case 'processing':
                return 'طلب تجديد الجواز قيد المعالجة';
            case 'ready':
                return 'جواز السفر جاهز للاستلام';
            case 'delivered':
                return 'تم تسليم جواز السفر';
            case 'rejected':
                return 'تم رفض طلب تجديد الجواز';
            default:
                return 'تم تحديث حالة طلب تجديد الجواز';
        }
    }
}
