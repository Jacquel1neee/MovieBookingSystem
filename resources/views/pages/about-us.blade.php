@extends('layouts.app')

@section('title', 'About Us - GSC Cinemas')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>About GSC Cinemas</h1>
            <p class="lead text-muted">GSC Cinemas brings premium cinema experiences to audiences across Malaysia.</p>
        </div>
    </div>
    <div class="row gy-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <h3>Our Story</h3>
                <p>GSC has provided world-class cinema entertainment for decades. We offer the latest blockbusters, immersive formats like IMAX and Gold Class, and premium experiences for every movie lover.</p>
                <h3>Our Vision</h3>
                <p>We want every visit to feel special, with comfortable seats, memorable sound, and seamless booking through our platform.</p>
                <h3>Why Choose Us?</h3>
                <ul>
                    <li>Latest movies and premium formats</li>
                    <li>Easy online booking</li>
                    <li>Secure ticketing and QR check-in</li>
                    <li>Friendly customer support</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <h5>Need Help?</h5>
                <p>If you have questions about our services, booking process, or promotions, visit our support pages below.</p>
                <a href="{{ route('support.faq') }}" class="btn btn-outline-danger w-100 mb-2">FAQ</a>
                <a href="{{ route('support.contact') }}" class="btn btn-danger w-100">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection
