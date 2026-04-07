@extends('layouts.app')

@section('title', 'Select Seats - ' . $showtime->movie->title)

@push('styles')
<style>
/* 整体影院布局 */
.cinema-layout {
    max-width: 1000px;
    margin: 0 auto;
    background: #f5f5f5;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* 屏幕区域 */
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

/* 座位表格 */
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

/* 座位样式 */
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

/* 普通座位 - 绿色 */
.seat-btn.regular {
    background: #4CAF50;
    color: white;
}

/* VIP座位 - 金色 */
.seat-btn.vip {
    background: #FFD700;
    color: #333;
}

/* 已选座位 - 亮蓝色，带光效 */
.seat-btn.selected {
    background: #2196F3 !important;
    color: white !important;
    transform: scale(1.1);
    box-shadow: 0 0 15px #2196F3;
    border: 2px solid white;
    font-weight: bold;
    z-index: 10;
    position: relative;
}

/* 已售出座位 - 灰色打叉 */
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

/* 悬停效果 - 只有可选座位才有 */
.seat-btn:not(.booked):not(.selected):hover {
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    filter: brightness(1.1);
}

/* 过道列 */
.aisle-col {
    width: 30px;
}

/* 图例 */
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

/* 侧边栏 */
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

/* 响应式 */
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
        <!-- 左边：电影院座位平面图 -->
        <div class="col-12 col-lg-8 mb-4 mb-lg-0">
            <div class="cinema-layout">
                <!-- 屏幕 -->
                <div class="screen-area">
                    <div class="screen">🎬 银幕 SCREEN 🎬</div>
                </div>
                
                <!-- 座位图 -->
                <div class="seats-wrapper" style="overflow-x: auto;">
                    <form id="seat-selection-form" action="{{ route('bookings.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                        <input type="hidden" name="seats" id="selected-seats-input" value="">
                        
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
                
                <!-- 图例说明 -->
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-box" style="background: #4CAF50;"></div>
                        <span>普通座</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box" style="background: #FFD700;"></div>
                        <span>VIP座</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box" style="background: #2196F3;"></div>
                        <span>已选择</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box" style="background: #e0e0e0; position: relative;">
                            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #ff4444; font-weight: bold;">✕</span>
                        </div>
                        <span>已售出</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 右边：订票摘要 -->
        <div class="col-12 col-lg-4">
            <div class="summary-card sticky-top" style="top: 20px;">
                <!-- 电影信息 -->
                <div class="text-center mb-4">
                    @if($showtime->movie->poster)
                        <img src="{{ $showtime->movie->poster }}" alt="{{ $showtime->movie->title }}" 
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
                
                <!-- 票价信息 -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">💰 票价信息</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="legend-box" style="background: #4CAF50; display: inline-block; width: 15px; height: 15px; border-radius: 3px;"></span> 普通座:</span>
                        <span class="fw-bold">RM {{ number_format($showtime->price, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="legend-box" style="background: #FFD700; display: inline-block; width: 15px; height: 15px; border-radius: 3px;"></span> VIP座:</span>
                        <span class="fw-bold text-warning">RM {{ number_format($showtime->price * 1.5, 2) }}</span>
                    </div>
                </div>
                
                <!-- 已选座位计数 -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">🎫 已选座位</h6>
                    <span class="seat-count-badge" id="seat-count">0</span>
                </div>
                
                <!-- 已选座位列表 -->
                <div class="mb-4">
                    <div id="selected-seats-list" style="max-height: 200px; overflow-y: auto; min-height: 100px;">
                        <p class="text-muted text-center py-3">👆 点击上方绿色或金色座位开始选择</p>
                    </div>
                </div>
                
                <!-- 总计 -->
                <div class="bg-light p-3 rounded mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 fw-bold">💵 总计:</span>
                        <span class="h4 fw-bold text-danger" id="total-amount">RM 0.00</span>
                    </div>
                </div>
                
                <!-- 继续按钮 -->
                <button type="button" class="btn btn-danger w-100 py-3 fw-bold mb-2" id="continue-btn" disabled onclick="submitSelection()">
                    继续付款 →
                </button>
                
                <a href="{{ route('movies.show', $showtime->movie->id) }}" class="btn btn-outline-secondary w-100">
                    ← 返回场次选择
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// 存储选中的座位
let selectedSeats = [];

// 切换座位选择
function toggleSeat(button, column, row) {
    console.log('点击座位:', '行:', row, '列:', column);
    
    // 如果座位已预订，不能选择
    if (button.classList.contains('booked')) {
        alert('这个座位已经被预订了');
        return;
    }
    
    const seatId = button.dataset.seatId;
    const seatNumber = button.dataset.seatNumber;
    const seatType = button.dataset.type;
    const basePrice = parseFloat(button.dataset.price);
    
    // VIP座位价格上浮50%
    const finalPrice = seatType === 'vip' ? basePrice * 1.5 : basePrice;
    
    // 检查是否已经选中
    if (button.classList.contains('selected')) {
        console.log('取消选择:', seatNumber);
        // 取消选择
        button.classList.remove('selected');
        selectedSeats = selectedSeats.filter(s => s.id !== seatId);
    } else {
        console.log('选择座位:', seatNumber);
        // 选择座位
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
    
    // 更新界面
    updateSelectedSeats();
    
    // 强制浏览器重绘，确保颜色变化
    button.style.transform = 'scale(1.1)';
    setTimeout(() => {
        button.style.transform = '';
    }, 200);
}

// 更新已选座位显示
function updateSelectedSeats() {
    const seatsInput = document.getElementById('selected-seats-input');
    const seatsList = document.getElementById('selected-seats-list');
    const totalAmount = document.getElementById('total-amount');
    const continueBtn = document.getElementById('continue-btn');
    const seatCount = document.getElementById('seat-count');
    
    // 更新隐藏输入
    seatsInput.value = JSON.stringify(selectedSeats.map(s => s.id));
    
    console.log('当前已选座位数量:', selectedSeats.length);
    
    if (selectedSeats.length > 0) {
        // 更新座位计数
        seatCount.textContent = selectedSeats.length;
        seatCount.style.background = '#4CAF50';
        
        // 显示已选座位
        let html = '';
        let total = 0;
        
        selectedSeats.forEach(seat => {
            const seatClass = seat.type === 'vip' ? 'vip' : 'regular';
            html += `
                <div class="selected-seat-item ${seatClass}">
                    <div>
                        <span class="fw-bold">${seat.row}${seat.column}</span>
                        <small class="text-muted ms-2">(${seat.type === 'vip' ? 'VIP' : '普通'})</small>
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
        seatsList.innerHTML = '<p class="text-muted text-center py-3">👆 点击上方绿色或金色座位开始选择</p>';
        totalAmount.textContent = 'RM 0.00';
        continueBtn.disabled = true;
    }
}

// 提交选座
function submitSelection() {
    const seatsInput = document.getElementById('selected-seats-input');
    const selectedSeatsValue = seatsInput.value;
    
    if (!selectedSeatsValue || selectedSeatsValue === '[]') {
        alert('请至少选择一个座位');
        return;
    }
    
    if (confirm('确认选择这些座位吗？')) {
        document.getElementById('seat-selection-form').submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('页面加载完成，可用座位数量:', document.querySelectorAll('.seat-btn:not(.booked)').length);
});
</script>
@endsection