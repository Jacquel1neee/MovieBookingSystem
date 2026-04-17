@extends('layouts.app')

@section('title', 'FAQ - GSC Cinemas')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Frequently Asked Questions</h1>
            <p class="lead text-muted">Find answers to common booking and cinema questions.</p>
        </div>
    </div>
    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                    How do I book a ticket?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Select a movie, choose your showtime, pick seats, then complete payment to confirm your booking.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                    Can I change my booking?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    You can request an exchange for paid bookings that are still in the future. Use the exchange page to choose a new showtime for the same movie.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                    How do I get my QR ticket?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Open your booking history and click the ticket's QR button. Present the QR code at cinema entrance for check-in.
                </div>
            </div>
        </div>
    </div>
</div>
