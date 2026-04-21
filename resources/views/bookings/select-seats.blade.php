@extends('layouts.app')

@section('title', 'Select Seats - ' . $showtime->movie->title)

@push('styles')
<style>
.cinema-layout {
    max-width: 1000px;
    margin: 0 auto;
    background: #f5f5f5;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.screen-area {
    text-align: center;
    margin-bottom: 40px;
}

.screen {
    background: #333;
    color: white;
    padding: 15px 30px;
    width: 80%;
    margin: 0 auto;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
    font-size: 18px;
    letter-spacing: 2px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.seats-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.seats-table td {
    padding: 5px;
    text-align: center;
}

.row-label {
    font-weight: bold;
    color: #666;
    width: 40px;
    font-size: 16px;
}

.seat-btn {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px 8px 4px 4px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Regular seat - green */
.seat-btn.regular {
    background: #4CAF50;
    color: white;
}

/* VIP seat - yellow */
.seat-btn.vip {
    background: #ffd700;
    color: white;
    border: 2px solid #B71C1C;
    box-shadow: 0 2px 6px rgba(211, 47, 47, 0.35);
}

/* Selected regular seat - blue */
.seat-btn.regular.selected {
    background: #1976D2 !important;
    color: white !important;
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(76, 175, 80, 0.7);
    border: 2px solid white;
    font-weight: bold;
    z-index: 10;
    position: relative;
}

/* Selected VIP seat - blue */
.seat-btn.vip.selected {
    background: #1976D2 !important;
    color: #333 !important;
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(255, 235, 59, 0.7);
    border: 2px solid #FBC02D;
    font-weight: bold;
    z-index: 10;
    position: relative;
}

.seat-btn.booked {
    background: #e0e0e0;
    color: #9e9e9e;
    cursor: not-allowed;
    position: relative;
    overflow: hidden;
}

.seat-btn.booked::before,
.seat-btn.booked::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #ff4444;
}

.seat-btn.booked::before {
    transform: rotate(-45deg);
}

.seat-btn.booked::after {
    transform: rotate(45deg);
}

.seat-btn:not(.booked):not(.selected):hover {
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    filter: brightness(1.1);
}

.aisle-col {
    width: 30px;
}

.legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
    padding: 20px;
    background: white;
    border-radius: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.legend-box {
    width: 30px;
    height: 30px;
    border-radius: 6px;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.selected-seat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 8px;
    border-left: 4px solid;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.selected-seat-item.regular {
    border-left-color: #4CAF50;
}

.selected-seat-item.vip {
    border-left-color: #FFD700;
}

.seat-count-badge {
    background: #2196F3;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    transition: all 0.3s;
}

@media (max-width: 768px) {
    .seat-btn {
        width: 35px;
        height: 35px;
        font-size: 12px;
    }
    
    .row-label {
        width: 30px;
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .seat-btn {
        width: 30px;
        height: 30px;
        font-size: 10px;
    }
    
    .cinema-layout {
        padding: 15px;
    }
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="container-fluid bg-danger py-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-white fw-bold mb-2">Choose Your Seats</h1>
                <p class="text-white-50 mb-0">{{ $showtime->movie->title }} - {{ $showtime->hall->name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-12 col-lg-8 mb-4 mb-lg-0">
            <div class="cinema-layout">
                <div class="screen-area">
                    <div class="screen">🎬 SCREEN 🎬</div>
                </div>
                
                <!-- 座位图 -->
                <div class="seats-wrapper" style="overflow-x: auto;">
                    <form id="seat-selection-form" action="{{ route('bookings.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                        <div id="selected-seats-inputs"></div>
                        
                        <table class="seats-table">
                            @php
                                $seatsByRow = $showtime->hall->seats->groupBy('row')->sortKeys();
                                $totalColumns = $showtime->hall->columns;
                                $midPoint = floor($totalColumns / 2);
                            @endphp
                            
                            @foreach($seatsByRow as $row => $seats)
                            <tr>
                                <!-- 左边行号 -->
                                <td class="row-label">{{ chr(64 + $row) }}</td>
                                
                                <!-- 左边座位 (1到中间) -->
                                @foreach($seats->sortBy('column') as $seat)
                                    @if($seat->column <= $midPoint)
                                        @php
                                            $isBooked = in_array($seat->id, $bookedSeats);
                                            $seatType = $seat->type;
                                        @endphp
                                        
                                        <td>
                                            @if($isBooked)
                                                <button type="button" class="seat-btn booked" disabled>
                                                    {{ $seat->column }}
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="seat-btn {{ $seatType }}"
                                                        data-seat-id="{{ $seat->id }}"
                                                        data-seat-number="{{ $seat->seat_number }}"
                                                        data-price="{{ $showtime->price }}"
                                                        data-type="{{ $seatType }}"
                                                        data-row="{{ chr(64 + $row) }}"
                                                        data-column="{{ $seat->column }}"
                                                        onclick="toggleSeat(this, {{ $seat->column }}, {{ $row }})">
                                                    {{ $seat->column }}
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                                
                                <!-- 过道 -->
                                <td class="aisle-col"></td>
                                
                                <!-- 右边座位 (中间+1到最后一列) -->
                                @foreach($seats->sortBy('column') as $seat)
                                    @if($seat->column > $midPoint)
                                        @php
                                            $isBooked = in_array($seat->id, $bookedSeats);
                                            $seatType = $seat->type;
                                        @endphp
                                        
                                        <td>
                                            @if($isBooked)
                                                <button type="button" class="seat-btn booked" disabled>
                                                    {{ $seat->column }}
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="seat-btn {{ $seatType }}"
                                                        data-seat-id="{{ $seat->id }}"
                                                        data-seat-number="{{ $seat->seat_number }}"
                                                        data-price="{{ $showtime->price }}"
                                                        data-type="{{ $seatType }}"
                                                        data-row="{{ chr(64 + $row) }}"
                                                        data-column="{{ $seat->column }}"
                                                        onclick="toggleSeat(this, {{ $seat->column }}, {{ $row }})">
                                                    {{ $seat->column }}
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                                
                                <!-- 右边行号 -->
                                <td class="row-label">{{ chr(64 + $row) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right: Booking Summary -->
        <div class="col-12 col-lg-4">
            <div class="summary-card sticky-top" style="top: 20px;">
                <!-- Movie Info -->
                <div class="text-center mb-4">
                    @if($showtime->movie->poster)
                        <img src="{{ $showtime->movie->poster_url }}" alt="{{ $showtime->movie->title }}"
                             style="width: 100px; height: 150px; object-fit: cover; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                    @endif
                    <h5 class="fw-bold mt-3">{{ $showtime->movie->title }}</h5>
                    <div class="text-muted small">
                        <div><i class="bi bi-calendar3"></i> {{ $showtime->start_time->format('d M Y') }}</div>
                        <div><i class="bi bi-clock"></i> {{ $showtime->start_time->format('h:i A') }}</div>
                        <div><i class="bi bi-building"></i> {{ $showtime->hall->name }}</div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Pricing -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">💰 Pricing</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="legend-box" style="background: #4CAF50; display: inline-block; width: 15px; height: 15px; border-radius: 3px;"></span> Regular Seat:</span>
                        <span class="fw-bold">RM {{ number_format($showtime->price, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="legend-box" style="background: #FFD700; display: inline-block; width: 15px; height: 15px; border-radius: 3px;"></span> VIP Seat:</span>
                        <span class="fw-bold text-warning">RM {{ number_format($showtime->price * 1.5, 2) }}</span>
                    </div>
                </div>
                
                <!-- Selected Seats Count -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">🎫 Selected Seats</h6>
                    <span class="seat-count-badge" id="seat-count">0</span>
                </div>
                
                <!-- Selected Seats List -->
                <div class="mb-4">
                    <div id="selected-seats-list" style="max-height: 200px; overflow-y: auto; min-height: 100px;">
                        <p class="text-muted text-center py-3">👆 Click any green or gold seat above to begin selecting.</p>
                    </div>
                </div>
                
                <!-- Continue Button -->
                <div class="bg-light p-3 rounded mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 fw-bold">💵 Total:</span>
                        <span class="h4 fw-bold text-danger" id="total-amount">RM 0.00</span>
                    </div>
                </div>
                
                <button type="button" class="btn btn-danger w-100 py-3 fw-bold mb-2" id="continue-btn" disabled onclick="submitSelection()">
                    Continue to Review →
                </button>
                
                <a href="{{ route('movies.show', $showtime->movie->id) }}" class="btn btn-outline-secondary w-100">
                    ← Back to Showtimes
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Store selected seats
let selectedSeats = [];

// Toggle seat selection
function toggleSeat(button, column, row) {
    console.log('Seat clicked:', 'row:', row, 'column:', column);
    
    if (button.classList.contains('booked')) {
        return;
    }
    
    const seatId = button.dataset.seatId;
    const seatNumber = button.dataset.seatNumber;
    const seatType = button.dataset.type;
    const basePrice = parseFloat(button.dataset.price);
    
    const finalPrice = seatType === 'vip' ? basePrice * 1.5 : basePrice;
    
    if (button.classList.contains('selected')) {
        console.log('Deselected:', seatNumber);
        button.classList.remove('selected');
        selectedSeats = selectedSeats.filter(s => s.id !== seatId);
    } else {
        console.log('Selected:', seatNumber);
        button.classList.add('selected');
        selectedSeats.push({
            id: seatId,
            number: seatNumber,
            type: seatType,
            price: finalPrice,
            row: button.dataset.row,
            column: button.dataset.column,
            rowNum: row,
            colNum: column
        });
    }
    
    // Update UI
    updateSelectedSeats();
    
    // Force a redraw so the seat color change is visible
    button.style.transform = 'scale(1.1)';
    setTimeout(() => {
        button.style.transform = '';
    }, 200);
}

// Update the selected seats display
function updateSelectedSeats() {
    const seatsContainer = document.getElementById('selected-seats-inputs');
    const seatsList = document.getElementById('selected-seats-list');
    const totalAmount = document.getElementById('total-amount');
    const continueBtn = document.getElementById('continue-btn');
    const seatCount = document.getElementById('seat-count');
    
    seatsContainer.innerHTML = '';
    selectedSeats.forEach(seat => {
        seatsContainer.innerHTML += `<input type="hidden" name="seats[]" value="${seat.id}">`;
    });
    
    console.log('Selected seats count:', selectedSeats.length);
    
    if (selectedSeats.length > 0) {
        seatCount.textContent = selectedSeats.length;
        seatCount.style.background = '#4CAF50';
        
        let html = '';
        let total = 0;
        
        selectedSeats.forEach(seat => {
            const seatClass = seat.type === 'vip' ? 'vip' : 'regular';
            html += `
                <div class="selected-seat-item ${seatClass}">
                    <div>
                        <span class="fw-bold">${seat.row}${seat.column}</span>
                        <small class="text-muted ms-2">(${seat.type === 'vip' ? 'VIP' : 'Regular'})</small>
                    </div>
                    <span class="fw-bold">RM ${seat.price.toFixed(2)}</span>
                </div>
            `;
            total += seat.price;
        });
        
        seatsList.innerHTML = html;
        totalAmount.textContent = 'RM ' + total.toFixed(2);
        continueBtn.disabled = false;
    } else {
        seatCount.textContent = '0';
        seatCount.style.background = '#2196F3';
        seatsList.innerHTML = '<p class="text-muted text-center py-3">👆 Click any green or gold seat above to begin selecting.</p>';
        totalAmount.textContent = 'RM 0.00';
        continueBtn.disabled = true;
    }
}

// Submit the selection for review
function submitSelection() {
    if (selectedSeats.length === 0) {
        return;
    }
    document.getElementById('seat-selection-form').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Seat selection page loaded. Available seats:', document.querySelectorAll('.seat-btn:not(.booked)').length);
});
</script>
@endsection