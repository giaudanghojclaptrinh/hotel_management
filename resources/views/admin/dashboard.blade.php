@extends('admin.layouts.dashboard')
@section('title', 'Dashboard Quản trị')

@section('content')
<style>
    .text-gold { color: #d4af37; }
    .bg-gold { background-color: #d4af37; }
    .border-gold { border-color: #d4af37; }
    .shadow-gold { box-shadow: 0 4px 14px 0 rgba(212, 175, 55, 0.39); }
</style>

<div class="max-w-7xl mx-auto">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-white uppercase tracking-wide">Tổng quan Quản trị</h1>
            <p class="text-gray-400 mt-1 font-medium">Xin chào, chúc bạn một ngày kinh doanh thịnh vượng!</p>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center px-5 py-3 rounded-xl bg-gray-900 border border-gold text-sm font-bold text-gold shadow-gold">
                <i class="fa-regular fa-calendar mr-2"></i> {{ \Carbon\Carbon::now()->format('l, d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl p-6 text-white shadow-gold relative overflow-hidden group border border-gold">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity duration-500">
                <i class="fa-solid fa-coins text-7xl text-gold"></i>
            </div>
            <p class="text-gold text-xs font-bold uppercase tracking-widest mb-2">Tổng Doanh Thu</p>
            <h3 class="text-3xl font-serif font-bold text-white mb-2 tracking-wide">{{ number_format($totalRevenue ?? 0) }} <span class="text-lg text-gold font-sans">đ</span></h3>
            
            <div class="mt-4 pt-4 border-t border-gray-800 flex items-center justify-between">
                <span class="text-gray-400 text-xs">Hôm nay:</span>
                <span class="text-green-400 font-bold text-sm flex items-center bg-gray-800 px-2 py-1 rounded border border-gray-700">
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> +{{ number_format($todayRevenue ?? 0) }}
                </span>
            </div>
        </div>

        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 border-l-4 border-l-red-500 shadow-lg relative group hover:border-gray-700 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Đơn Chờ Duyệt</p>
                    <h3 class="text-4xl font-serif font-bold text-white">{{ $pendingBookings ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-900/30 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform border border-red-900">
                    <i class="fa-solid fa-bell text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.dat-phong') }}?status=pending" class="absolute bottom-4 right-6 text-xs font-bold text-red-400 hover:text-red-300 hover:underline flex items-center transition-colors">
                Xử lý ngay <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 border-l-4 border-l-blue-500 shadow-lg relative group hover:border-gray-700 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Công Suất Phòng</p>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-3xl font-serif font-bold text-white">{{ $occupiedRooms ?? 0 }}</h3>
                        <span class="text-lg text-gray-500 font-bold">/{{ $totalRooms ?? 0 }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-900/30 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform border border-blue-900">
                    <i class="fa-solid fa-bed text-xl"></i>
                </div>
            </div>
            <div class="w-full bg-gray-800 rounded-full h-2 mt-4 overflow-hidden border border-gray-700">
                <div class="bg-blue-600 h-2 rounded-full shadow-sm" style="width: {{ ($totalRooms > 0) ? (($occupiedRooms/$totalRooms)*100) : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 border-l-4 border-l-gold shadow-lg relative group hover:border-gray-700 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Khách Hàng</p>
                    <h3 class="text-4xl font-serif font-bold text-white">{{ $totalUsers ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-900/30 flex items-center justify-center text-gold group-hover:scale-110 transition-transform border border-yellow-900">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-green-400 font-bold mt-4 flex items-center">
                <i class="fa-solid fa-chart-line mr-1"></i> Tăng trưởng ổn định
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="lg:col-span-2 bg-gray-900 p-6 rounded-2xl shadow-lg border border-gray-800">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-800">
                <div>
                    <h3 class="font-bold text-white text-xl font-serif flex items-center">
                        <span class="w-2 h-8 bg-gold rounded mr-3 shadow-gold"></span>
                        Biểu đồ Doanh thu
                    </h3>
                </div>
                <a href="{{ route('admin.reports.revenue') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 text-gold text-xs font-bold rounded-lg hover:bg-black hover:border-gold transition-all shadow-md flex items-center">
                    Xem chi tiết <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="relative h-80 w-full p-2">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-gradient-to-b from-gray-900 to-black p-6 rounded-2xl shadow-gold border border-gold/20 text-white flex flex-col">
            <h3 class="font-bold text-gold text-lg mb-6 font-serif border-b border-gray-800 pb-4 flex justify-between items-center">
                <span>Tình trạng phòng</span>
                <i class="fa-solid fa-door-open opacity-50"></i>
            </h3>
            
            <div class="space-y-4 flex-1">
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-800/50 border border-gray-700 hover:border-green-500/50 transition-all group cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center text-green-400 border border-green-500/30 group-hover:bg-green-500/20 transition-colors">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white group-hover:text-green-400 transition-colors">Sẵn sàng</p>
                            <p class="text-xs text-gray-500">Available</p>
                        </div>
                    </div>
                    <span class="text-xl font-serif font-bold text-green-400">{{ $availableRooms ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-800/50 border border-gray-700 hover:border-red-500/50 transition-all group cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-400 border border-red-500/30 group-hover:bg-red-500/20 transition-colors">
                            <i class="fa-solid fa-user-lock"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white group-hover:text-red-400 transition-colors">Đang có khách</p>
                            <p class="text-xs text-gray-500">Occupied</p>
                        </div>
                    </div>
                    <span class="text-xl font-serif font-bold text-red-400">{{ $occupiedRooms ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-800/50 border border-gray-700 hover:border-yellow-500/50 transition-all group cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center text-yellow-500 border border-yellow-500/30 group-hover:bg-yellow-500/20 transition-colors">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white group-hover:text-yellow-500 transition-colors">Bảo trì / Dọn</p>
                            <p class="text-xs text-gray-500">Maintenance</p>
                        </div>
                    </div>
                    <span class="text-xl font-serif font-bold text-yellow-500">{{ ($totalRooms - ($occupiedRooms ?? 0) - ($availableRooms ?? 0)) }}</span>
                </div>
            </div>
            
            <a href="{{ route('admin.dat-phong') }}" class="mt-6 w-full py-3 bg-gold/10 border border-gold text-gold font-bold text-center rounded-lg hover:bg-gold hover:text-gray-900 transition-all shadow-md flex items-center justify-center gap-2">
                <i class="fa-solid fa-map"></i> Quản lý Sơ đồ phòng
            </a>
        </div>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-xl border border-gray-800 overflow-hidden mb-12">
        <div class="px-6 py-5 bg-gray-800 border-b border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-gold/10 border border-gold flex items-center justify-center text-gold">
                    <i class="fa-solid fa-list text-sm"></i>
                </div>
                <h3 class="font-bold text-white font-serif text-lg">Đơn đặt phòng mới nhất</h3>
            </div>
            <a href="{{ route('admin.dat-phong') }}" class="text-xs font-bold text-gray-400 hover:text-white border border-gray-600 px-4 py-2 rounded-lg hover:bg-gray-700 transition-all">
                Xem tất cả
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Mã đơn</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Phòng</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Ngày đến</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 bg-gray-900">
                    @foreach($recentBookings as $bk)
                    <tr class="hover:bg-gray-800/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-brand-gold">
                            #{{ $bk->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 text-xs font-bold mr-3 border border-gray-700 group-hover:border-brand-gold group-hover:text-brand-gold transition-colors">
                                    {{ substr($bk->user->name ?? 'G', 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-white">{{ $bk->user->name ?? 'Guest' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            <span class="bg-gray-800 px-2 py-1 rounded text-xs font-bold text-gray-300 border border-gray-700">
                                {{ $bk->chiTietDatPhongs->first()->phong->so_phong ?? '---' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-medium">
                            {{ \Carbon\Carbon::parse($bk->ngay_den)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($bk->trang_thai == 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-900/30 text-red-400 border border-red-900">
                                    Chờ duyệt
                                </span>
                            @elseif($bk->trang_thai == 'confirmed')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-900/30 text-green-400 border border-green-900">
                                    Đã duyệt
                                </span>
                            @elseif($bk->trang_thai == 'completed')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-800 text-gray-400 border border-gray-700">
                                    Hoàn thành
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-800 text-gray-500 border border-gray-700">
                                    {{ ucfirst($bk->trang_thai) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-white font-serif">
                            {{ number_format($bk->tong_tien) }} <span class="text-xs text-gray-500">đ</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        // Gradient màu vàng gold cho nền tối
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(212, 175, 55, 0.5)'); 
        gradient.addColorStop(1, 'rgba(212, 175, 55, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: data,
                    borderColor: '#d4af37', // Vàng đậm
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4, 
                    fill: true,
                    pointBackgroundColor: '#111827', // Đen (nền chart)
                    pointBorderColor: '#d4af37', // Vàng
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverBackgroundColor: '#d4af37',
                    pointHoverBorderColor: '#fff',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)', // Tooltip nền đen mờ
                        titleColor: '#d4af37', // Tiêu đề vàng
                        bodyColor: '#fff',
                        borderColor: '#374151',
                        borderWidth: 1,
                        titleFont: { size: 13, family: "'serif'" },
                        padding: 10,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#374151', drawBorder: false }, // Grid xám tối
                        ticks: { 
                            font: { size: 11 },
                            color: '#9ca3af', // Chữ màu xám nhạt
                            callback: function(value) { return value.toLocaleString('vi-VN') + ' đ'; } 
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: {
                            font: { size: 11 },
                            color: '#9ca3af' // Chữ màu xám nhạt
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endsection