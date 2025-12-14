@extends('admin.layouts.dashboard')
@section('title', 'Quản lý Doanh thu')
@section('header', 'Doanh thu & Hóa đơn')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl p-6 text-white shadow-lg border border-brand-gold/30 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-brand-gold opacity-10 text-9xl group-hover:scale-110 transition-transform duration-500">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div class="relative z-10">
                <p class="text-brand-gold text-xs font-bold uppercase tracking-widest mb-2 opacity-90">Tổng thực thu</p>
                <h2 class="text-3xl font-serif font-bold text-white tracking-wide">
                    {{ number_format($totalRevenue ?? 0, 0, ',', '.') }} <span class="text-lg font-sans font-normal text-brand-gold">đ</span>
                </h2>
                <p class="text-xs text-gray-400 mt-2 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Đã thanh toán
                </p>
            </div>
        </div>

        <a href="{{ route('admin.hoa-don', ['status' => 'paid']) }}" class="block bg-gray-900 rounded-2xl p-6 shadow-md border border-gray-800 flex items-center justify-between group hover:border-green-900/50 transition-all">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Đã thanh toán</p>
                <h2 class="text-3xl font-serif font-bold text-white">{{ $countPaid ?? 0 }} <span class="text-sm text-gray-500 font-sans font-normal">đơn</span></h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-900/20 flex items-center justify-center text-green-500 border border-green-900/30 group-hover:bg-green-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-check-double text-xl"></i>
            </div>
        </a>

        <a href="{{ route('admin.hoa-don', ['status' => 'unpaid']) }}" class="block bg-gray-900 rounded-2xl p-6 shadow-md border border-gray-800 flex items-center justify-between group hover:border-red-900/50 transition-all">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Chờ thanh toán</p>
                <h2 class="text-3xl font-serif font-bold text-white">{{ $countUnpaid ?? 0 }} <span class="text-sm text-gray-500 font-sans font-normal">đơn</span></h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-900/20 flex items-center justify-center text-red-500 border border-red-900/30 group-hover:bg-red-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-hourglass-half text-xl"></i>
            </div>
        </a>
    </div>

    <div class="bg-gray-900 p-4 rounded-xl shadow-sm border border-gray-800 mb-6">
        <form method="GET" action="{{ route('admin.hoa-don') }}" class="flex flex-col lg:flex-row gap-4 items-center">
            
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-500"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm mã hóa đơn hoặc tên khách..." 
                       class="w-full pl-10 rounded-lg bg-gray-800 border-gray-700 text-white placeholder-gray-500 focus:border-brand-gold focus:ring-brand-gold h-10 transition-all text-sm">
            </div>

            <div class="w-full lg:w-48">
                <select name="status" class="w-full rounded-lg bg-gray-800 border-gray-700 text-white focus:border-brand-gold focus:ring-brand-gold h-10 text-sm cursor-pointer">
                    <option value="">-- Trạng thái --</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                </select>
            </div>

            <div class="flex gap-2 w-full lg:w-auto items-center">
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="h-10 rounded-lg bg-gray-800 border-gray-700 text-white text-sm focus:border-brand-gold focus:ring-brand-gold w-full" title="Từ ngày">
                <span class="self-center text-gray-500">-</span>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="h-10 rounded-lg bg-gray-800 border-gray-700 text-white text-sm focus:border-brand-gold focus:ring-brand-gold w-full" title="Đến ngày">
            </div>

            <button type="submit" class="h-10 px-6 bg-brand-gold text-gray-900 font-bold rounded-lg hover:bg-white transition-all flex items-center justify-center shadow-md w-full lg:w-auto">
                <i class="fa-solid fa-filter mr-2"></i> Lọc
            </button>

            @if(request('q') || request('status') || request('from_date') || request('to_date'))
                <a href="{{ route('admin.hoa-don') }}" class="h-10 px-4 bg-gray-800 border border-gray-700 text-red-400 rounded-lg hover:bg-gray-700 flex items-center justify-center transition-all" title="Xóa bộ lọc">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            @if($hoaDons->isEmpty())
                <div class="p-16 text-center">
                    <div class="inline-flex w-16 h-16 rounded-full bg-gray-800 items-center justify-center text-gray-600 mb-4">
                        <i class="fa-solid fa-file-invoice-dollar text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-300">Không tìm thấy hóa đơn nào</h3>
                    <p class="text-gray-500 mt-1">Thử thay đổi bộ lọc hoặc kiểm tra lại dữ liệu.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Mã HĐ</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Ngày lập</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Phương thức</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800">
                        @foreach($hoaDons as $hd)
                        <tr class="hover:bg-gray-800/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-sm font-bold text-brand-gold group-hover:text-white transition-colors">
                                #{{ $hd->ma_hoa_don }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-white">{{ $hd->datPhong->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500">{{ $hd->datPhong->user->phone ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $hd->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-bold px-2 py-1 rounded bg-gray-800 text-gray-400 border border-gray-700">
                                    {{ ucfirst($hd->phuong_thuc_thanh_toan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($hd->trang_thai == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-900/30 text-green-400 border border-green-800">
                                        <i class="fa-solid fa-check mr-1"></i> Đã thanh toán
                                    </span>
                                @elseif($hd->trang_thai == 'awaiting_payment')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-900/30 text-amber-300 border border-amber-800">
                                        <i class="fa-solid fa-clock mr-1"></i> Chờ thanh toán
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-900/30 text-brand-gold border border-yellow-800">
                                        <i class="fa-solid fa-clock mr-1"></i> Chờ xử lý
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="font-serif font-bold text-lg {{ $hd->trang_thai == 'paid' ? 'text-white' : 'text-gray-500' }}">
                                    {{ number_format($hd->tong_tien, 0, ',', '.') }} đ
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.hoa-don.sua', $hd->id) }}" class="text-gray-500 hover:text-brand-gold font-bold transition-colors inline-flex items-center gap-1 group/btn">
                                    Chi tiết <i class="fa-solid fa-chevron-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-800">
            {{ $hoaDons->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection