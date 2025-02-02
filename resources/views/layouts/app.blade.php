<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', __('messages.app_name')) }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @if(App::getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
    
    <style>
        body {
            font-family: {{ App::getLocale() == 'ar' ? "'Cairo'" : "'Inter'" }}, sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
        }
        .nav-link {
            font-weight: 500;
            color: #4B5563;
            padding: 0.5rem 1rem !important;
        }
        .nav-link:hover {
            color: #1A56DB;
        }
        .nav-link.active {
            color: #1A56DB;
        }
        .navbar-nav .btn {
            padding: 0.5rem 1.5rem;
        }
        .footer-link {
            color: #4B5563;
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
        .footer-link:hover {
            color: #1A56DB;
        }
        .social-icon {
            width: 36px;
            height: 36px;
            background: rgba(26, 86, 219, 0.1);
            color: #1A56DB;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 0.25rem;
            text-decoration: none;
        }
        .social-icon:hover {
            background: #1A56DB;
            color: white;
        }

        /* RTL specific styles */
        @if(App::getLocale() == 'ar')
        .me-2, .me-3 {
            margin-right: 0 !important;
            margin-left: 0.5rem !important;
        }
        .ms-2, .ms-3 {
            margin-left: 0 !important;
            margin-right: 0.5rem !important;
        }
        .text-md-start {
            text-align: right !important;
        }
        .text-md-end {
            text-align: left !important;
        }
        @endif
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                @if(App::getLocale() == 'ar')
                    <span class="text-primary">رح</span>لات
                @else
                    <span class="text-primary">Rih</span>lat
                @endif
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">{{ __('messages.services') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destinations">{{ __('messages.destinations') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">{{ __('messages.about') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">{{ __('messages.contact') }}</a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">{{ __('messages.register') }}</a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('messages.profile') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('messages.logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                    <!-- Language Switcher -->
                    <div class="dropdown">
                        <button class="btn btn-link text-dark px-0 text-decoration-none" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(App::getLocale() == 'ar')
                                <img src="{{ asset('images/flags/sa.svg') }}" alt="العربية" width="24" height="24" class="rounded-circle">
                            @else
                                <img src="{{ asset('images/flags/us.svg') }}" alt="English" width="24" height="24" class="rounded-circle">
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('language.switch', 'ar') }}">
                                    <img src="{{ asset('images/flags/sa.svg') }}" alt="العربية" width="24" height="24" class="rounded-circle me-2">
                                    <span>العربية</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('language.switch', 'en') }}">
                                    <img src="{{ asset('images/flags/us.svg') }}" alt="English" width="24" height="24" class="rounded-circle me-2">
                                    <span>English</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white pt-5">
        <div class="container">
            <div class="row g-4 pb-4">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <a class="navbar-brand mb-3 d-block" href="{{ url('/') }}">
                        @if(App::getLocale() == 'ar')
                            <span class="text-primary">رح</span>لات
                        @else
                            <span class="text-primary">Rih</span>lat
                        @endif
                    </a>
                    <p class="text-muted mb-4">{{ __('messages.footer_description') }}</p>
                    <div class="social-links">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="mb-3">{{ __('messages.quick_links') }}</h5>
                    <div class="d-flex flex-column">
                        <a href="#" class="footer-link">{{ __('messages.about') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.services') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.destinations') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.latest_news') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.contact') }}</a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="mb-3">{{ __('messages.our_services') }}</h5>
                    <div class="d-flex flex-column">
                        <a href="#" class="footer-link">{{ __('messages.book_flight') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.book_hotel') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.tour_programs') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.car_rental') }}</a>
                        <a href="#" class="footer-link">{{ __('messages.visa_services') }}</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4">
                    <h5 class="mb-3">{{ __('messages.contact_info') }}</h5>
                    <div class="d-flex flex-column">
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary ms-2"></i>
                            {{ __('messages.address') }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-phone text-primary ms-2"></i>
                            +966 123 456 789
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-envelope text-primary ms-2"></i>
                            info@rihlat.com
                        </p>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="row py-3">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} <span class="text-primary">Rihlat</span>. {{ __('messages.all_rights_reserved') }}</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-muted text-decoration-none ms-3">{{ __('messages.privacy_policy') }}</a>
                    <a href="#" class="text-muted text-decoration-none">{{ __('messages.terms_of_use') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>