<?php

namespace App\Notifications;

use App\Models\Visa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisaStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visa;

    public function __construct(Visa $visa)
    {
        $this->visa = $visa;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = $this->visa->status === 'completed' ? 'تمت الموافقة على' : 'تم رفض';
        $color = $this->visa->status === 'completed' ? 'success' : 'danger';

        return (new MailMessage)
            ->subject('تحديث حالة طلب التأشيرة')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line($status . ' طلب التأشيرة الخاص بك.')
            ->line('نوع التأشيرة: ' . $this->getVisaType())
            ->line('رقم الطلب: #' . $this->visa->id)
            ->lineIf($this->visa->notes, 'ملاحظات: ' . $this->visa->notes)
            ->action('عرض التفاصيل', route('customer.visas.show', $this->visa))
            ->salutation('شكراً لك');
    }

    public function toArray($notifiable)
    {
        return [
            'visa_id' => $this->visa->id,
            'type' => $this->visa->type,
            'status' => $this->visa->status,
            'message' => $this->visa->status === 'completed' 
                ? 'تمت الموافقة على طلب التأشيرة الخاص بك'
                : 'تم رفض طلب التأشيرة الخاص بك',
            'notes' => $this->visa->notes
        ];
    }

    protected function getVisaType()
    {
        switch ($this->visa->type) {
            case 'hajj':
                return 'تأشيرة حج';
            case 'umrah':
                return 'تأشيرة عمرة';
            case 'work':
                return 'تأشيرة عمل';
            default:
                return $this->visa->type;
        }
    }
}
