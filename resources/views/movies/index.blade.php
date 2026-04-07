@extends('layouts.app')

@section('title', 'Movies - GSC Cinemas')

@section('content')
<!-- Page Header with Banner -->
<div class="container-fluid bg-danger py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h1 class="text-white fw-bold mb-2">Movies</h1>
                <p class="text-white-50 mb-0">Discover the latest blockbusters and exclusive screenings</p>
            </div>
            <div class="col-12 col-md-6 mt-3 mt-md-0">
                <form action="{{ route('movies.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control rounded-0 border-0" placeholder="Search movies..." value="{{ request('search') }}" style="height: 50px;">
                    <button type="submit" class="btn btn-dark rounded-0 border-0 px-4" style="height: 50px;">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Sort Section -->
<div class="container mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-6 col-md-8">
            <div class="btn-group" role="group">
                <a href="{{ route('movies.index') }}" class="btn btn-outline-danger rounded-0 {{ !request('type') ? 'active' : '' }}">
                    All
                </a>
                <a href="{{ route('movies.index', ['type' => 'now-showing']) }}" class="btn btn-outline-danger rounded-0 {{ request('type') == 'now-showing' ? 'active' : '' }}">
                    Now Showing
                </a>
                <a href="{{ route('movies.index', ['type' => 'coming-soon']) }}" class="btn btn-outline-danger rounded-0 {{ request('type') == 'coming-soon' ? 'active' : '' }}">
                    Coming Soon
                </a>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="d-flex justify-content-end">
                <select class="form-select rounded-0" onchange="window.location.href = this.value" style="max-width: 200px;">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_desc']) }}" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Newest First</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_asc']) }}" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'title_asc']) }}" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title A-Z</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'title_desc']) }}" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title Z-A</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Movies Grid -->
<div class="container mb-5">
    <div class="row g-4">
        @forelse($movies as $movie)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <!-- Movie Poster -->
                <div class="position-relative">
                    @if($movie->poster)
                        <img src="{{ $movie->poster }}" class="card-img-top" alt="{{ $movie->title }}" style="aspect-ratio: 2/3; object-fit: cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="aspect-ratio: 2/3;">
                            <i class="bi bi-film text-white" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                    
                    <!-- Badges -->
                    <span class="position-absolute top-0 start-0 bg-danger text-white px-3 py-2 m-2 small fw-bold">
                        {{ $movie->duration }} min
                    </span>
                    
                    @if($movie->release_date > now())
                        <span class="position-absolute top-0 end-0 bg-dark text-white px-3 py-2 m-2 small fw-bold">
                            COMING SOON
                        </span>
                    @endif
                </div>
                
                <!-- Movie Info -->
                <div class="card-body">
                    <h6 class="card-title fw-bold mb-2 text-truncate">{{ $movie->title }}</h6>
                    
                    <!-- Rating and Date -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <small class="text-muted">4.5</small>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $movie->release_date->format('d M Y') }}
                        </small>
                    </div>
                    
                    <!-- Action Button -->
                    @if($movie->release_date <= now())
                        <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-danger w-100 rounded-0">
                            <i class="bi bi-ticket-perforated me-2"></i>Book Now
                        </a>
                    @else
                        <button class="btn btn-outline-secondary w-100 rounded-0" disabled>
                            <i class="bi bi-bell me-2"></i>Notify Me
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-film fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No movies found</h5>
                <p class="text-muted">Try adjusting your search or filter</p>
                <a href="{{ route('movies.index') }}" class="btn btn-outline-danger mt-3">Clear Filters</a>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $movies->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Promotional Banner -->
<div class="container-fluid bg-dark text-white py-5 mt-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <h3 class="fw-bold mb-2">GSC Movie Club</h3>
                <p class="text-white-50 mb-3 mb-md-0">Join now and earn points with every ticket purchase</p>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="#" class="btn btn-danger rounded-0 px-4 py-2">Learn More</a>
            </div>
        </div>
    </div>
</div>
@endsection