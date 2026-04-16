@extends('layouts.app')

@section('title', 'Feedback - GSC Cinemas')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Feedback</h1>
            <p class="lead text-muted">Share your experience and help us improve.</p>
        </div>
    </div>
    <div class="card border-0 shadow-sm p-4">
        <form>
            <div class="mb-3">
                <label for="feedbackName" class="form-label">Name</label>
                <input type="text" class="form-control" id="feedbackName" placeholder="Your name">
            </div>
            <div class="mb-3">
                <label for="feedbackEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="feedbackEmail" placeholder="Your email">
            </div>
            <div class="mb-3">
                <label for="feedbackMessage" class="form-label">Message</label>
                <textarea class="form-control" id="feedbackMessage" rows="5" placeholder="Tell us what you liked or how we can improve."></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Submit Feedback</button>
        </form>
    </div>
</div>
