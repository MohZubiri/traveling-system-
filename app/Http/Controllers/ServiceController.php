<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $services = Service::active()
            ->with('destinations')
            ->latest()
            ->paginate(12);

        return view('services.index', compact('services'));
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        $service->load('destinations', 'inclusions', 'exclusions');
        $relatedServices = Service::active()
            ->where('id', '!=', $service->id)
            ->where('category_id', $service->category_id)
            ->take(3)
            ->get();

        return view('services.show', compact('service', 'relatedServices'));
    }

    /**
     * Book a service
     */
    public function book(Request $request, Service $service)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after:today',
            'number_of_people' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500'
        ]);

        $booking = new Booking([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'start_date' => $validated['start_date'],
            'number_of_people' => $validated['number_of_people'],
            'special_requests' => $validated['special_requests'],
            'total_price' => $service->price * $validated['number_of_people'],
            'status' => 'pending'
        ]);

        $booking->save();

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Your booking has been submitted successfully!');
    }
}
