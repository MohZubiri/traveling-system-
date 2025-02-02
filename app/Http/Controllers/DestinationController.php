<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a listing of the destinations.
     */
    public function index()
    {
        $destinations = Destination::active()
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('destinations.index', compact('destinations'));
    }

    /**
     * Display the specified destination.
     */
    public function show(Destination $destination)
    {
        $destination->load(['images', 'reviews']);
        
        $relatedDestinations = Destination::active()
            ->where('id', '!=', $destination->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('destinations.show', compact('destination', 'relatedDestinations'));
    }
}
