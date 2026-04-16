@extends('layouts.app')

@section('title', 'Contact Us - GSC Cinemas')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Contact Us</h1>
            <p class="lead text-muted">Need help? Reach out to our support team.</p>
        </div>
    </div>
    <div class="row gy-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4">
                <h5>Customer Support</h5>
                <p>Email us at <a href="mailto:support@gsc-cinemas.example">support@gsc-cinemas.example</a> or call +60 3 1234 5678.</p>
                <p>Our support team is available daily from 9:00 AM to 9:00 PM.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4">
                <h5>Visit Us</h5>
                <p>Drop by the cinema for ticket changes or general enquiries. For the best service, please bring your booking number.</p>
            </div>
        </div>
    </div>
</div>
