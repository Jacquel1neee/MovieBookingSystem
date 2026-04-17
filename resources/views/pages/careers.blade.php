@extends('layouts.app')

@section('title', 'Careers - GSC Cinemas')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Careers at GSC Cinemas</h1>
            <p class="lead text-muted">Join our team and help deliver cinematic magic every day.</p>
        </div>
    </div>
    <div class="row gy-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <h3>Open Positions</h3>
                <p>We are always looking for passionate people in customer service, marketing, operations, and cinema management.</p>
                <ul>
                    <li>Guest service representatives</li>
                    <li>Cinema operations staff</li>
                    <li>Marketing and promotions specialists</li>
                    <li>Technical and support roles</li>
                </ul>
                <h3>Why Work With Us?</h3>
                <p>At GSC, we believe in a supportive workplace, training, and growth opportunities within the cinema network.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <h5>Apply Today</h5>
                <p>Send your resume to careers@gsc-cinemas.example or use our contact page for enquiries.</p>
                <a href="{{ route('support.contact') }}" class="btn btn-danger w-100">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection
