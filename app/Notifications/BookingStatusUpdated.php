<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = $this->booking->status === 'confirmed' ? 'تم تأكيد' : 'تم إلغاء';
        $color = $this->booking->status === 'confirmed' ? 'success' : 'danger';

        return (new MailMessage)
            ->subject('تحديث حالة الحجز')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line($status . ' الحجز الخاص بك.')
            ->line('تفاصيل الحجز:')
            ->line('- نوع الخدمة: ' . $this->getServiceType())
            ->line('- التاريخ: ' . $this->booking->date->format('Y-m-d'))
            ->line('- الوقت: ' . $this->booking->time)
            ->line('- الموقع: ' . $this->booking->location)
            ->lineIf($this->booking->notes, 'ملاحظات: ' . $this->booking->notes)
            ->action('عرض تفاصيل الحجز', route('customer.bookings.show', $this->booking))
            ->salutation('شكراً لك');
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'service_type' => $this->booking->service_type,
            'status' => $this->booking->status,
            'message' => $this->booking->status === 'confirmed' 
                ? 'تم تأكيد حجزك بنجاح'
                : 'تم إلغاء حجزك',
            'notes' => $this->booking->notes
        ];
    }

    protected function getServiceType()
    {
        return $this->booking->service_type === 'bus' ? 'حافلة' : 'سيارة';
    }
}
