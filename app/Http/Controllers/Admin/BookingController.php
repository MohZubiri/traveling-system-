<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingStatusUpdated;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['customer', 'tickets'])
            ->when($request->service_type, function ($query, $type) {
                return $query->where('service_type', $type);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('date', $date);
            })
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'tickets', 'transaction']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $booking->status;
        
        $booking->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        // Update tickets status
        $ticketStatus = $request->status === 'confirmed' ? 'active' : 'cancelled';
        $booking->tickets()->update(['status' => $ticketStatus]);

        // Update transaction status
        if ($booking->transaction) {
            $booking->transaction->update([
                'status' => $request->status === 'confirmed' ? 'completed' : 'failed'
            ]);
        }

        // Send notification to customer
        if ($oldStatus !== $request->status) {
            $booking->customer->notify(new BookingStatusUpdated($booking));
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الحجز بنجاح');
    }

    public function report(Request $request)
    {
        $bookings = Booking::with(['customer', 'tickets'])
            ->when($request->start_date, function ($query, $date) {
                return $query->whereDate('date', '>=', $date);
            })
            ->when($request->end_date, function ($query, $date) {
                return $query->whereDate('date', '<=', $date);
            })
            ->when($request->service_type, function ($query, $type) {
                return $query->where('service_type', $type);
            })
            ->get();

        $totalRevenue = $bookings->where('status', 'confirmed')->sum('cost');
        $totalBookings = $bookings->count();
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();
        $cancelledBookings = $bookings->where('status', 'cancelled')->count();

        return view('admin.bookings.report', compact(
            'bookings',
            'totalRevenue',
            'totalBookings',
            'confirmedBookings',
            'cancelledBookings'
        ));
    }
}
