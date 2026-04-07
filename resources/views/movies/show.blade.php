@extends('layouts.app')

@section('title', $movie->title . ' - GSC Cinemas')

@section('content')
<!-- Movie Hero Section -->
<div class="container-fluid bg-dark text-white py-5 mb-4">
    <div class="container">
        <div class="row g-4">
            <!-- Movie Poster -->
            <div class="col-12 col-md-4 col-lg-3">
                @if($movie->poster)
                    <img src="{{ $movie->poster }}" class="img-fluid w-100 shadow-lg" alt="{{ $movie->title }}" style="border-radius: 10px;">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px; border-radius: 10px;">
                        <i class="bi bi-film text-white" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
            
            <!-- Movie Details -->
            <div class="col-12 col-md-8 col-lg-9">
                <h1 class="display-5 fw-bold mb-3">{{ $movie->title }}</h1>
                
                <!-- Movie Meta -->
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <span class="badge bg-danger px-3 py-2">{{ $movie->duration }} min</span>
                    <span class="badge bg-secondary px-3 py-2">{{ $movie->release_date->format('d M Y') }}</span>
                    <span class="badge bg-warning text-dark px-3 py-2">
                        <i class="bi bi-star-fill me-1"></i>4.5/5.0
                    </span>
                </div>
                
                <!-- Movie Description -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Synopsis</h5>
                    <p class="text-white-50">{{ $movie->description }}</p>
                </div>
                
                <!-- Movie Info Grid -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <small class="text-white-50 d-block">Director</small>
                        <span class="text-white">Denis Villeneuve</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-white-50 d-block">Cast</small>
                        <span class="text-white">Timothée Chalamet</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-white-50 d-block">Language</small>
                        <span class="text-white">English</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-white-50 d-block">Subtitle</small>
                        <span class="text-white">Chinese, Malay</span>
                    </div>
                </div>
                
                <!-- Trailer Button -->
                <a href="#" class="btn btn-outline-light rounded-0 px-4 py-2" data-bs-toggle="modal" data-bs-target="#trailerModal">
                    <i class="bi bi-play-circle me-2"></i>Watch Trailer
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Showtimes Section -->
<div class="container mb-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-0">Showtimes</h2>
            <div class="border-bottom border-danger border-3" style="width: 80px; padding-top: 10px;"></div>
        </div>
    </div>
    
    <!-- Date Selector (Mobile Responsive) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex overflow-auto pb-2" style="scrollbar-width: thin;">
                @for($i = 0; $i < 7; $i++)
                    @php
                        $date = now()->addDays($i);
                        $isToday = $i == 0;
                    @endphp
                    <div class="me-2 {{ $i == 0 ? 'active' : '' }}">
                        <a href="#date-{{ $date->format('Y-m-d') }}" 
                           class="btn {{ $i == 0 ? 'btn-danger' : 'btn-outline-secondary' }} rounded-0 px-4 py-3" 
                           style="min-width: 100px;">
                            <small class="d-block">{{ $isToday ? 'Today' : $date->format('D') }}</small>
                            <strong class="d-block">{{ $date->format('d M') }}</strong>
                        </a>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    
    <!-- Showtimes List -->
    @forelse($dates as $dateInfo)
    <div class="card mb-4 border-0 shadow-sm" id="date-{{ \Carbon\Carbon::parse($dateInfo['date'])->format('Y-m-d') }}">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">{{ $dateInfo['formatted_date'] }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($dateInfo['showtimes'] as $showtime)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="border rounded p-3 text-center h-100">
                        <div class="h4 fw-bold text-danger mb-2">{{ $showtime->start_time->format('h:i A') }}</div>
                        <div class="text-muted small mb-2">{{ $showtime->hall->name }}</div>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-light text-dark">2D</span>
                            <span class="badge bg-light text-dark">English</span>
                        </div>
                        <div class="h5 text-primary mb-3">RM {{ number_format($showtime->price, 2) }}</div>
                        @auth
                            <a href="{{ route('bookings.select-seats', $showtime->id) }}" class="btn btn-danger w-100 rounded-0">
                                Book Now
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger w-100 rounded-0">
                                Login to Book
                            </a>
                        @endauth
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted text-center py-3">No showtimes available for this date.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-light text-center py-5">
        <i class="bi bi-calendar-x fs-1 d-block mb-3 text-muted"></i>
        <p class="text-muted mb-0">No showtimes available for this movie.</p>
    </div>
    @endforelse
</div>

<!-- Cinema Info Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="text-center">
                    <i class="bi bi-camera-reels fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold mb-2">Dolby Atmos</h5>
                    <p class="text-muted small">Immersive sound experience</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="text-center">
                    <i class="bi bi-brightness-alt-high fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold mb-2">IMAX</h5>
                    <p class="text-muted small">Largest screen format</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="text-center">
                    <i class="bi bi-cup-straw fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold mb-2">Gold Class</h5>
                    <p class="text-muted small">Premium seating experience</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Trailer Modal -->
<div class="modal fade" id="trailerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">{{ $movie->title }} - Trailer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/dummyvideo" title="Trailer" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection