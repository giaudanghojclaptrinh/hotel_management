@extends('admin.layouts.dashboard')
@section('title', 'Quản lý Doanh thu')
@section('header', 'Doanh thu & Hóa đơn')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-brand-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-brand-800 opacity-20 text-9xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div class="relative z-10">
                <p class="text-brand-gold text-sm font-bold uppercase tracking-wider mb-1">Tổng thực thu</p>
                <h2 class="text-3xl font-serif font-bold text-white">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }} <span class="text-lg">đ</span></h2>
                <p class="text-xs text-gray-400 mt-2">Dựa trên các hóa đơn "Đã thanh toán"</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase mb-1">Đã thanh toán</p>
                <h2 class="text-3xl font-serif font-bold text-green-600">{{ $countPaid ?? 0 }} <span class="text-sm text-gray-400 font-sans font-normal">đơn</span></h2>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xl">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase mb-1">Chờ thanh toán</p>
                <h2 class="text-3xl font-serif font-bold text-red-500">{{ $countUnpaid ?? 0 }} <span class="text-sm text-gray-400 font-sans font-normal">đơn</span></h2>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-500 text-xl">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.hoa-don') }}" class="flex flex-col lg:flex-row gap-4 items-center">
            
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm mã hóa đơn hoặc tên khách..." 
                       class="w-full pl-10 rounded-lg border-gray-300 focus:border-brand-gold focus:ring-brand-gold h-10 transition-all text-sm">
            </div>

            <div class="w-full lg:w-48">
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-brand-gold focus:ring-brand-gold h-10 text-sm">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                </select>
            </div>

            <div class="flex gap-2 w-full lg:w-auto items-center">
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="h-10 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold" title="Từ ngày">
                <span class="self-center text-gray-400">-</span>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="h-10 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold" title="Đến ngày">
            </div>

            <button type="submit" class="h-10 px-4 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-all flex items-center justify-center">
                <i class="fa-solid fa-filter mr-2 text-gray-500"></i> Lọc
            </button>

            @if(request('q') || request('status') || request('from_date') || request('to_date'))
                <a href="{{ route('admin.hoa-don') }}" class="h-10 px-4 bg-white border border-gray-300 text-gray-500 rounded-lg hover:bg-gray-50 flex items-center justify-center" title="Xóa bộ lọc">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            @if($hoaDons->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fa-solid fa-file-invoice-dollar text-4xl text-gray-300 mb-3"></i>
                    <p>Không tìm thấy hóa đơn nào.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-brand-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Mã HĐ</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Ngày lập</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-brand-gold uppercase tracking-wider">Phương thức</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-brand-gold uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-brand-gold uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-brand-gold uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($hoaDons as $hd)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-sm font-bold text-gray-700">
                                #{{ $hd->ma_hoa_don }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $hd->datPhong->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500">{{ $hd->datPhong->user->phone ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $hd->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-medium px-2 py-1 rounded bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ ucfirst($hd->phuong_thuc_thanh_toan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($hd->trang_thai == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <i class="fa-solid fa-check mr-1"></i> Đã thanh toán
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        <i class="fa-solid fa-clock mr-1"></i> Chờ xử lý
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="font-serif font-bold text-lg {{ $hd->trang_thai == 'paid' ? 'text-brand-900' : 'text-gray-500' }}">
                                    {{ number_format($hd->tong_tien, 0, ',', '.') }} đ
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.hoa-don.sua', $hd->id) }}" class="text-brand-gold hover:text-brand-900 font-bold transition-colors flex items-center justify-end gap-1">
                                    Chi tiết <i class="fa-solid fa-chevron-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $hoaDons->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
