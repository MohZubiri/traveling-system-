<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get featured travel services
        $featuredServices = Service::where('is_featured', true)
            ->take(4)
            ->get();

        // Get active testimonials
        $testimonials = Testimonial::active()
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredServices', 'testimonials'));
    }

    /**
     * Handle contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string'
        ]);

        // Here you would typically send an email or save to database
        // For now, we'll just redirect with success message

        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255'
        ]);

        // Here you would typically add to mailing list
        // For now, we'll just redirect with success message

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
