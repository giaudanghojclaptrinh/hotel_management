@extends('admin.layouts.dashboard')
@section('title', 'Dashboard Quản trị')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-brand-900">Tổng quan Quản trị</h1>
            <p class="text-gray-500 mt-1">Xin chào, chúc bạn một ngày làm việc hiệu quả!</p>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center px-4 py-2 rounded-lg bg-white border border-gray-200 text-sm font-bold text-gray-600 shadow-sm">
                <i class="fa-regular fa-calendar mr-2 text-brand-gold"></i> {{ \Carbon\Carbon::now()->format('l, d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-brand-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-sack-dollar text-6xl text-brand-gold"></i>
            </div>
            <p class="text-brand-gold text-xs font-bold uppercase tracking-widest mb-1">Tổng Doanh Thu</p>
            <h3 class="text-2xl font-serif font-bold">{{ number_format($totalRevenue ?? 0) }} <span class="text-sm font-sans">đ</span></h3>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <span class="text-green-400 font-bold mr-1"><i class="fa-solid fa-arrow-up"></i> +{{ number_format($todayRevenue ?? 0) }} đ</span>
                <span>hôm nay</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Chờ Duyệt</p>
                    <h3 class="text-2xl font-serif font-bold text-gray-900">{{ $pendingBookings ?? 0 }} <span class="text-sm font-sans font-normal text-gray-400">đơn</span></h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>
            <a href="{{ route('admin.dat-phong') }}?status=pending" class="text-xs font-bold text-red-500 mt-4 block hover:underline">Xử lý ngay &rarr;</a>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Công suất phòng</p>
                    <h3 class="text-2xl font-serif font-bold text-gray-900">{{ $occupiedRooms ?? 0 }}<span class="text-lg text-gray-400 font-sans">/{{ $totalRooms ?? 0 }}</span></h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-bed"></i>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4">
                <div class="bg-brand-900 h-1.5 rounded-full" style="width: {{ ($totalRooms > 0) ? (($occupiedRooms/$totalRooms)*100) : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Tổng khách hàng</p>
                    <h3 class="text-2xl font-serif font-bold text-gray-900">{{ $totalUsers ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-4 font-bold"><i class="fa-solid fa-chart-line"></i> Đang tăng trưởng</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-gray-900 text-lg">Biểu đồ doanh thu (7 ngày)</h3>
                <a href="{{ route('admin.reports.revenue') }}" class="text-xs font-bold text-brand-gold hover:text-brand-900 border border-brand-gold px-3 py-1 rounded-full transition-colors">Xem chi tiết</a>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-900 text-lg mb-4">Trạng thái nhanh</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-green-600 shadow-sm"><i class="fa-solid fa-check"></i></div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Phòng sẵn sàng</p>
                            <p class="text-xs text-gray-500">Available</p>
                        </div>
                    </div>
                    <span class="text-lg font-serif font-bold text-green-700">{{ $availableRooms ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-red-500 shadow-sm"><i class="fa-solid fa-user-lock"></i></div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Đang có khách</p>
                            <p class="text-xs text-gray-500">Occupied</p>
                        </div>
                    </div>
                    <span class="text-lg font-serif font-bold text-red-700">{{ $occupiedRooms ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-yellow-50 border border-yellow-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-yellow-600 shadow-sm"><i class="fa-solid fa-tools"></i></div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Đang bảo trì/Dọn</p>
                            <p class="text-xs text-gray-500">Maintenance</p>
                        </div>
                    </div>
                    <span class="text-lg font-serif font-bold text-yellow-700">{{ ($totalRooms - ($occupiedRooms ?? 0) - ($availableRooms ?? 0)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h3 class="font-bold text-gray-900">Đơn đặt phòng gần đây</h3>
            <a href="{{ route('admin.dat-phong') }}" class="text-xs font-bold text-brand-900 hover:text-brand-gold transition-colors">Xem tất cả &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Phòng</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Ngày đến</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentBookings as $bk)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">#{{ $bk->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $bk->user->name ?? 'Guest' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bk->chiTietDatPhongs->first()->phong->so_phong ?? '---' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($bk->ngay_den)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($bk->trang_thai == 'pending')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">Chờ duyệt</span>
                            @elseif($bk->trang_thai == 'confirmed')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">Đã duyệt</span>
                            @elseif($bk->trang_thai == 'completed')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Hoàn thành</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-brand-gold">
                            {{ number_format($bk->tong_tien) }} đ
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
        
        // Dữ liệu từ Controller (Blade)
        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: data,
                    borderColor: '#D4AF37', // Brand Gold
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    borderWidth: 2,
                    tension: 0.4, // Đường cong mềm mại
                    fill: true,
                    pointBackgroundColor: '#1a1a1a', // Brand 900
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#D4AF37'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f3f4f6' },
                        ticks: { callback: function(value) { return value.toLocaleString() + ' đ'; } }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
