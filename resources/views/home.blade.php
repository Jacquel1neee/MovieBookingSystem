@extends('layouts.app')

@section('title', 'GSC Cinemas - Movie Booking System')

@section('content')
<div class="container mt-3" id="welcomeAlert">
    <div class="alert alert-danger alert-dismissible fade show rounded-0 border-0" role="alert" style="background-color: #dc3545; color: white;">
        <div class="row align-items-center">
            <div class="col">
                <i class="bi bi-megaphone-fill me-2"></i>
                <strong>Welcome to GSC Cinemas!</strong> Get RM5 off your first booking. Use code: <span class="badge bg-light text-dark">GSCFIRST5</span>
            </div>
            <div class="col-auto">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Filter Section -->
<div class="container mt-4">
    <div class="row g-2 justify-content-center">
        <div class="col-6 col-md-3 col-lg-2">
            <a href="{{ route('movies.index') }}" class="btn btn-outline-danger w-100 py-3">
                <i class="bi bi-camera-reels-fill d-block fs-4 mb-2"></i>
                <span class="d-none d-sm-inline">Now Showing</span>
            </a>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <a href="{{ route('movies.index', ['coming-soon' => 1]) }}" class="btn btn-outline-secondary w-100 py-3">
                <i class="bi bi-calendar-event-fill d-block fs-4 mb-2"></i>
                <span class="d-none d-sm-inline">Coming Soon</span>
            </a>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <a href="#" class="btn btn-outline-secondary w-100 py-3">
                <i class="bi bi-ticket-perforated-fill d-block fs-4 mb-2"></i>
                <span class="d-none d-sm-inline">Promotions</span>
            </a>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <a href="#" class="btn btn-outline-secondary w-100 py-3">
                <i class="bi bi-cup-straw d-block fs-4 mb-2"></i>
                <span class="d-none d-sm-inline">GSC Snacks</span>
            </a>
        </div>
    </div>
</div>

<!-- Now Showing Section -->
<div class="container mt-5" id="now-showing">
    <div class="row mb-4">
        <div class="col-8 col-md-6">
            <h2 class="fw-bold mb-0">Now Showing</h2>
            <div class="border-bottom border-danger border-3" style="width: 80px; padding-top: 10px;"></div>
        </div>
        <div class="col-4 col-md-6 text-end">
            <a href="{{ route('movies.index') }}" class="text-danger text-decoration-none">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="row g-3 g-md-4">
        @forelse(($nowShowing ?? []) as $movie)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="position-relative">
                    @if($movie->poster)
                        <img src="{{ $movie->poster }}" class="card-img-top" alt="{{ $movie->title }}" style="aspect-ratio: 2/3; object-fit: cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="aspect-ratio: 2/3;">
                            <i class="bi bi-film text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold">
                        {{ $movie->duration }} min
                    </span>
                </div>
                <div class="card-body px-0 pb-0">
                    <h6 class="card-title fw-bold mb-2 text-truncate">{{ $movie->title }}</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">
                            <i class="bi bi-star-fill text-warning me-1"></i> 4.5
                        </small>
                        <small class="text-muted">{{ $movie->release_date->format('d M') }}</small>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-sm btn-outline-danger rounded-0">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light text-center py-5">
                <i class="bi bi-film fs-1 d-block mb-3 text-muted"></i>
                <p class="text-muted mb-0">No movies showing at the moment.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Coming Soon Section -->
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-8 col-md-6">
            <h2 class="fw-bold mb-0">Coming Soon</h2>
            <div class="border-bottom border-danger border-3" style="width: 80px; padding-top: 10px;"></div>
        </div>
        <div class="col-4 col-md-6 text-end">
            <a href="{{ route('movies.index', ['coming-soon' => 1]) }}" class="text-danger text-decoration-none">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="row g-3 g-md-4">
        @forelse(($comingSoon ?? []) as $movie)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="position-relative">
                    @if($movie->poster)
                        <img src="{{ $movie->poster }}" class="card-img-top" alt="{{ $movie->title }}" style="aspect-ratio: 2/3; object-fit: cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="aspect-ratio: 2/3;">
                            <i class="bi bi-film text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <span class="position-absolute top-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded small fw-bold">
                        Coming
                    </span>
                </div>
                <div class="card-body px-0 pb-0">
                    <h6 class="card-title fw-bold mb-2 text-truncate">{{ $movie->title }}</h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i> {{ $movie->release_date->format('d M Y') }}
                        </small>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-sm btn-outline-secondary rounded-0" disabled>Notify Me</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light text-center py-5">
                <i class="bi bi-calendar-event fs-1 d-block mb-3 text-muted"></i>
                <p class="text-muted mb-0">No upcoming movies.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- GSC Experience Banner -->
<div class="container-fluid bg-dark text-white mt-5">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-12 col-md-6">
                <h2 class="fw-bold mb-3">The GSC Experience</h2>
                <p class="text-white-50 mb-4">Experience movies like never before with our premium cinema concepts</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border border-secondary rounded p-3 text-center">
                            <i class="bi bi-soundwave fs-2 text-danger"></i>
                            <h6 class="mt-2 mb-0">Dolby Atmos</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border border-secondary rounded p-3 text-center">
                            <i class="bi bi-brightness-alt-high fs-2 text-danger"></i>
                            <h6 class="mt-2 mb-0">IMAX</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border border-secondary rounded p-3 text-center">
                            <i class="bi bi-cup-straw fs-2 text-danger"></i>
                            <h6 class="mt-2 mb-0">Gold Class</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border border-secondary rounded p-3 text-center">
                            <i class="bi bi-joystick fs-2 text-danger"></i>
                            <h6 class="mt-2 mb-0">On Stage</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row g-2">
                    <div class="col-6">
                        <img src="https://www.gsc.com.my/media/images/imax-seats.jpg" class="img-fluid rounded" alt="IMAX">
                    </div>
                    <div class="col-6">
                        <img src="https://www.gsc.com.my/media/images/gold-class.jpg" class="img-fluid rounded" alt="Gold Class">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Promotions Section -->
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-0">Latest Promotions</h2>
            <div class="border-bottom border-danger border-3" style="width: 80px; padding-top: 10px;"></div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="https://www.gsc.com.my/media/promotion/student-promo.jpg" class="card-img-top" alt="Student Promotion" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Student Wednesday</h5>
                    <p class="card-text text-muted small">RM10 tickets every Wednesday with valid student ID</p>
                    <a href="#" class="btn btn-link text-danger p-0">Learn More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="https://www.gsc.com.my/media/promotion/monday-md.jpg" class="card-img-top" alt="Monday Promotion" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Magic Monday</h5>
                    <p class="card-text text-muted small">Buy 1 Free 1 tickets every Monday</p>
                    <a href="#" class="btn btn-link text-danger p-0">Learn More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="https://www.gsc.com.my/media/promotion/family-sunday.jpg" class="card-img-top" alt="Family Promotion" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Family Sunday</h5>
                    <p class="card-text text-muted small">Special family packages with free kids meal</p>
                    <a href="#" class="btn btn-link text-danger p-0">Learn More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Download App Section -->
<div class="container mt-5">
    <div class="row align-items-center bg-light p-4 p-md-5 rounded">
        <div class="col-12 col-md-8">
            <h3 class="fw-bold mb-2">Download GSC App</h3>
            <p class="text-muted mb-3 mb-md-0">Get exclusive deals, faster booking, and earn GSC Points</p>
        </div>
        <div class="col-12 col-md-4">
            <div class="row g-2">
                <div class="col-6">
                    <a href="#" class="btn btn-dark w-100">
                        <i class="bi bi-google-play me-2"></i> Google Play
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" class="btn btn-dark w-100">
                        <i class="bi bi-apple me-2"></i> App Store
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Links -->
<div class="container-fluid bg-dark text-white-50 mt-5">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-6 col-md-3">
                <h6 class="text-white mb-3">About GSC</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Careers</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Terms of Use</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-3">
                <h6 class="text-white mb-3">Movies</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Now Showing</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Coming Soon</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Promotions</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-3">
                <h6 class="text-white mb-3">Support</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Contact Us</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Feedback</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-3">
                <h6 class="text-white mb-3">Follow Us</h6>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="row">
            <div class="col-12 text-center small">
                © {{ date('Y') }} GSC Cinemas. All rights reserved.
            </div>
        </div>
    </div>
</div>
<div class="container fixed-bottom mb-3" id="cookieBanner" style="z-index: 1050;">
    <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-6">
            <div class="alert alert-dark alert-dismissible fade show rounded-3 shadow-lg mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill text-danger me-3 fs-4"></i>
                    <div class="flex-grow-1">
                        <small>This website uses cookies to ensure you get the best experience.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle dismiss -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var welcomeAlert = document.getElementById('welcomeAlert');
        if (welcomeAlert) {
            var alert = new bootstrap.Alert(welcomeAlert.querySelector('.alert'));
        }
    });
</script>
@endpush

@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s ease;
}
.card:hover {
    transform: translateY(-5px);
}
.btn-outline-danger:hover, .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}
.position-absolute {
    z-index: 2;
}
.container-fluid > .container {
    padding-left: 0;
    padding-right: 0;
}
.fixed-bottom {
    pointer-events: none;
}
.fixed-bottom .alert {
    pointer-events: auto;
}
</style>
@endpush