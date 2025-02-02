<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth('customer')->user()
            ->bookings()
            ->with('tickets')
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('customer.bookings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|in:bus,car',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'passengers' => 'required|integer|min:1|max:10'
        ]);

        // Calculate cost based on service type and passengers
        $baseCost = $request->service_type === 'bus' ? 50 : 150;
        $totalCost = $baseCost * $request->passengers;

        $booking = Booking::create([
            'customer_id' => auth('customer')->id(),
            'service_type' => $request->service_type,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'cost' => $totalCost,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        // Create tickets for each passenger
        for ($i = 0; $i < $request->passengers; $i++) {
            Ticket::create([
                'booking_id' => $booking->id,
                'ticket_number' => Ticket::generateTicketNumber(),
                'issue_date' => now(),
                'status' => 'active'
            ]);
        }

        // Create transaction
        Transaction::create([
            'customer_id' => auth('customer')->id(),
            'service_type' => 'booking',
            'status' => 'pending',
            'amount' => $totalCost,
            'reference_id' => 'BKG-' . Str::random(10),
            'description' => "حجز {$request->service_type} - {$request->passengers} ركاب",
            'transactionable_type' => Booking::class,
            'transactionable_id' => $booking->id
        ]);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'تم إنشاء الحجز بنجاح');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load(['tickets', 'transaction']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        if (!$booking->canModify()) {
            return back()->with('error', 'لا يمكن تعديل الحجز قبل 48 ساعة من موعده');
        }

        return view('customer.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        if (!$booking->canModify()) {
            return back()->with('error', 'لا يمكن تعديل الحجز قبل 48 ساعة من موعده');
        }

        $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required',
            'location' => 'required|string|max:255'
        ]);

        $booking->update([
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'notes' => $request->notes
        ]);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'تم تحديث الحجز بنجاح');
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);

        if (!$booking->canCancel()) {
            return back()->with('error', 'لا يمكن إلغاء الحجز قبل 24 ساعة من موعده');
        }

        $booking->update(['status' => 'cancelled']);
        $booking->tickets()->update(['status' => 'cancelled']);

        if ($booking->transaction) {
            $booking->transaction->update(['status' => 'failed']);
        }

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'تم إلغاء الحجز بنجاح');
    }

    public function downloadTicket(Ticket $ticket)
    {
        $this->authorize('view', $ticket->booking);

        // Generate PDF ticket
        $pdf = PDF::loadView('customer.bookings.ticket', compact('ticket'));

        return $pdf->download("ticket-{$ticket->ticket_number}.pdf");
    }
}
