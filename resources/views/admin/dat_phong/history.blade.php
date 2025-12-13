@extends('admin.layouts.dashboard')
@section('title', 'Lịch sử đặt phòng')
@section('header', 'Lịch sử đặt phòng')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Danh sách đã duyệt</h1>
            <p class="text-sm text-gray-500 mt-1">Các đơn đặt phòng đã hoàn thành hoặc đang hoạt động</p>
        </div>
        <a href="{{ route('admin.dat-phong') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-brand-gold hover:text-brand-gold transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại Sơ đồ
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-brand-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-brand-gold">Mã Đơn</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-brand-gold">Khách hàng</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-brand-gold">Phòng</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-brand-gold">Thời gian</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-brand-gold">Trạng thái</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-brand-gold">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($datPhongs as $dp)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-900">#{{ $dp->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $dp->user->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $dp->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($dp->chiTietDatPhongs->first()) 
                                <span class="px-2 py-1 rounded bg-gray-100 font-serif font-bold text-gray-800">{{ $dp->chiTietDatPhongs->first()->phong->so_phong ?? '—' }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($dp->ngay_den)->format('d/m') }} <i class="fa-solid fa-arrow-right mx-1 text-xs text-gray-400"></i> {{ \Carbon\Carbon::parse($dp->ngay_di)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                {{ ucfirst($dp->trang_thai) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($dp->hoaDon)
                            <a href="{{ route('admin.dat-phong.hoa-don', $dp->id) }}" class="text-brand-gold hover:text-brand-900 font-bold transition-colors">
                                Chi tiết <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                            </a>
                            @else
                            <span class="text-gray-400 italic text-xs">Chưa có HĐ</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $datPhongs->links() }}
        </div>
    </div>
</div>
@endsection