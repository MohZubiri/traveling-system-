<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Notifications\BookingStatusUpdated;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingService
{
    public function createBooking(array $data, $customerId)
    {
        try {
            \DB::beginTransaction();

            // Calculate cost
            $baseCost = $data['service_type'] === 'bus' ? 50 : 150;
            $totalCost = $baseCost * $data['passengers'];
            
            // Add return trip cost if applicable
            if (isset($data['return_trip']) && $data['return_trip']) {
                $totalCost *= 2;
            }

            // Create booking
            $booking = Booking::create([
                'customer_id' => $customerId,
                'service_type' => $data['service_type'],
                'date' => $data['date'],
                'time' => $data['time'],
                'location' => $data['location'],
                'cost' => $totalCost,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'return_trip' => $data['return_trip'] ?? false,
                'return_date' => $data['return_date'] ?? null,
                'return_time' => $data['return_time'] ?? null
            ]);

            // Create tickets
            $this->generateTickets($booking, $data['passengers']);

            // Create transaction
            Transaction::create([
                'customer_id' => $customerId,
                'service_type' => 'booking',
                'amount' => $totalCost,
                'status' => 'pending',
                'reference_id' => 'BKG-' . Str::random(10),
                'description' => 'حجز ' . ($data['service_type'] === 'bus' ? 'حافلة' : 'سيارة'),
                'transactionable_type' => Booking::class,
                'transactionable_id' => $booking->id
            ]);

            \DB::commit();
            return $booking;

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    protected function generateTickets($booking, $passengers)
    {
        for ($i = 0; $i < $passengers; $i++) {
            $ticketNumber = 'TKT-' . strtoupper(Str::random(8));
            
            $ticket = Ticket::create([
                'booking_id' => $booking->id,
                'ticket_number' => $ticketNumber,
                'passenger_number' => $i + 1,
                'qr_code' => $this->generateQRCode($ticketNumber),
                'status' => 'active'
            ]);

            // Generate return ticket if it's a return trip
            if ($booking->return_trip) {
                $returnTicketNumber = 'TKT-R-' . strtoupper(Str::random(8));
                
                Ticket::create([
                    'booking_id' => $booking->id,
                    'ticket_number' => $returnTicketNumber,
                    'passenger_number' => $i + 1,
                    'qr_code' => $this->generateQRCode($returnTicketNumber),
                    'status' => 'active',
                    'is_return' => true
                ]);
            }
        }
    }

    protected function generateQRCode($ticketNumber)
    {
        return QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate($ticketNumber);
    }

    public function cancelBooking(Booking $booking)
    {
        try {
            \DB::beginTransaction();

            if (!$booking->canCancel()) {
                throw new \Exception('لا يمكن إلغاء الحجز قبل 48 ساعة من موعده');
            }

            $booking->update(['status' => 'cancelled']);
            $booking->tickets()->update(['status' => 'cancelled']);

            if ($booking->transaction) {
                $booking->transaction->update(['status' => 'cancelled']);
            }

            // Send notification
            $booking->customer->notify(new BookingStatusUpdated($booking));

            \DB::commit();
            return $booking;

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function updateBooking(Booking $booking, array $data)
    {
        try {
            \DB::beginTransaction();

            if (!$booking->canModify()) {
                throw new \Exception('لا يمكن تعديل الحجز قبل 48 ساعة من موعده');
            }

            $booking->update([
                'date' => $data['date'],
                'time' => $data['time'],
                'location' => $data['location'],
                'notes' => $data['notes'] ?? null,
                'return_date' => $data['return_date'] ?? null,
                'return_time' => $data['return_time'] ?? null
            ]);

            // Send notification
            $booking->customer->notify(new BookingStatusUpdated($booking));

            \DB::commit();
            return $booking;

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }
}
