<!-- @extends('layouts.app')

@section('title', 'Promotions - GSC Cinemas')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Promotions</h1>
            <p class="lead text-muted">Discover the latest GSC promo offers and ticket deals.</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <img src="https://www.gsc.com.my/media/promotion/student-promo.jpg" class="card-img-top" alt="Student Promotion">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Student Wednesday</h5>
                    <p class="card-text">Enjoy RM10 tickets every Wednesday with valid student ID.</p>
                    <a href="{{ route('movies.index', ['type' => 'now-showing']) }}" class="btn btn-danger mt-auto">Book Now</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <img src="https://www.gsc.com.my/media/promotion/monday-md.jpg" class="card-img-top" alt="Monday Promotion">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Magic Monday</h5>
                    <p class="card-text">Buy one ticket and get the second free every Monday.</p>
                    <a href="{{ route('movies.index', ['type' => 'now-showing']) }}" class="btn btn-danger mt-auto">Book Now</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <img src="https://www.gsc.com.my/media/promotion/family-sunday.jpg" class="card-img-top" alt="Family Promotion">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Family Sunday</h5>
                    <p class="card-text">Family bundles include popcorn and soft drinks at a special price.</p>
                    <a href="{{ route('movies.index', ['type' => 'now-showing']) }}" class="btn btn-danger mt-auto">Book Now</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Seasonal Deals</h5>
                    <p class="card-text">Keep checking this page for limited-time offers and charity screenings.</p>
                    <a href="{{ route('movies.index', ['type' => 'now-showing']) }}" class="btn btn-danger mt-auto">Browse Movies</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection -->
