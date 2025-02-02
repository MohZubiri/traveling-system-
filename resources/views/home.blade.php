
@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4">
                        Find & Book Your<br>
                        <span class="text-primary">Dream</span> Vacation
                    </h1>
                    <p class="lead mb-5">Discover amazing places and experiences with our expert travel guides</p>
                    
                    <div class="search-box bg-white p-3 rounded-4 shadow mb-5">
                        <form class="row g-3">
                            <div class="col-md-5">
                                <select class="form-select" aria-label="Select destination">
                                    <option selected>Select Destination</option>
                                    <option value="1">Paris, France</option>
                                    <option value="2">Dubai, UAE</option>
                                    <option value="3">Tokyo, Japan</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="date" class="form-control" placeholder="Select Date">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="stats d-flex gap-5">
                        <div class="stat-item">
                            <h3 class="fw-bold mb-0">500+</h3>
                            <p class="text-muted mb-0">Destinations</p>
                        </div>
                        <div class="stat-item">
                            <h3 class="fw-bold mb-0">100k+</h3>
                            <p class="text-muted mb-0">Customers</p>
                        </div>
                        <div class="stat-item">
                            <h3 class="fw-bold mb-0">24/7</h3>
                            <p class="text-muted mb-0">Support</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/travel-hero.jpg') }}" alt="Travel Experience" class="img-fluid rounded-4">
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section class="services-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Our Travel Services</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="service-card bg-white p-4 rounded-4 text-center h-100">
                        <div class="icon-box mb-3">
                            <i class="fas fa-plane text-primary fa-2x"></i>
                        </div>
                        <h5>Flight Booking</h5>
                        <p class="text-muted mb-0">Best deals on flights worldwide</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card bg-primary p-4 rounded-4 text-center text-white h-100">
                        <div class="icon-box mb-3">
                            <i class="fas fa-hotel fa-2x"></i>
                        </div>
                        <h5>Hotel Booking</h5>
                        <p class="mb-0">Luxury and comfort guaranteed</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card bg-white p-4 rounded-4 text-center h-100">
                        <div class="icon-box mb-3">
                            <i class="fas fa-map-marked-alt text-primary fa-2x"></i>
                        </div>
                        <h5>Tour Packages</h5>
                        <p class="text-muted mb-0">Customized vacation packages</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card bg-white p-4 rounded-4 text-center h-100">
                        <div class="icon-box mb-3">
                            <i class="fas fa-car text-primary fa-2x"></i>
                        </div>
                        <h5>Car Rentals</h5>
                        <p class="text-muted mb-0">Explore with freedom</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us Section --}}
    <section class="why-us-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('images/travel-experience.jpg') }}" alt="Travel Experience" class="img-fluid rounded-4">
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title mb-4">Why Choose Us?</h2>
                    <div class="features">
                        <div class="feature d-flex align-items-center mb-3">
                            <div class="icon-box me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <p class="mb-0">Best Price Guarantee</p>
                        </div>
                        <div class="feature d-flex align-items-center mb-3">
                            <div class="icon-box me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <p class="mb-0">Expert Travel Guides</p>
                        </div>
                        <div class="feature d-flex align-items-center mb-3">
                            <div class="icon-box me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <p class="mb-0">Customized Travel Plans</p>
                        </div>
                        <div class="feature d-flex align-items-center">
                            <div class="icon-box me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <p class="mb-0">24/7 Customer Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="testimonials-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h2 class="section-title mb-4">What Our Travelers<br>Say About Us</h2>
                    <div class="d-flex gap-2 mb-4">
                        <img src="{{ asset('images/traveler1.jpg') }}" alt="Traveler" class="rounded-circle" width="40">
                        <img src="{{ asset('images/traveler2.jpg') }}" alt="Traveler" class="rounded-circle" width="40">
                        <img src="{{ asset('images/traveler3.jpg') }}" alt="Traveler" class="rounded-circle" width="40">
                        <img src="{{ asset('images/traveler4.jpg') }}" alt="Traveler" class="rounded-circle" width="40">
                        <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">99+</span>
                    </div>
                    <p class="text-muted">More than 100k+ travelers trust us</p>
                </div>
                <div class="col-lg-8">
                    <div class="testimonial-card bg-white p-4 rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/testimonial-avatar.jpg') }}" alt="Traveler" class="rounded-circle me-3" width="60">
                            <div>
                                <h5 class="mb-1">Sarah Johnson</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">"Amazing experience! The trip was perfectly organized, and the destinations were breathtaking. Will definitely book again!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Popular Destinations Section --}}
    <section class="destinations-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title mb-4">Explore Our<br><span class="text-primary">Popular</span> Destinations</h2>
                    <p class="mb-4">Discover the world's most amazing places. From pristine beaches to historic cities, we have the perfect destination for every traveler.</p>
                    <a href="{{ route('services.index') }}" class="btn btn-primary">View All Destinations</a>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/destinations.jpg') }}" alt="Popular Destinations" class="img-fluid rounded-4">
                </div>
            </div>
        </div>
    </section>

    {{-- Newsletter Section --}}
    <section class="newsletter-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h2 class="mb-4">Subscribe To Our Newsletter</h2>
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email">
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .hero-section {
        background-color: #f8f9ff;
    }
    .search-box {
        border: 1px solid #e0e0e0;
    }
    .section-title {
        font-weight: 700;
    }
    .service-card {
        transition: transform 0.3s ease;
    }
    .service-card:hover {
        transform: translateY(-5px);
    }
    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        background-color: rgba(26, 86, 219, 0.1);
    }
    .newsletter-form .form-control {
        height: 50px;
        border-radius: 25px 0 0 25px;
    }
    .newsletter-form .btn {
        border-radius: 0 25px 25px 0;
        padding: 0 30px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Add smooth scrolling
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 1000);
        }
    });
});
</script>
@endpush
