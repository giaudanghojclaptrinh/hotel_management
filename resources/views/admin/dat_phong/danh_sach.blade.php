@extends('admin.layouts.dashboard')
@section('title', 'Sơ đồ Phòng & Đặt phòng')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    
    <div class="toolbar mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Quản lý Đặt phòng (Sơ đồ)</h1>
        
        <div class="flex gap-4 items-center">
            <!-- Chú thích màu sắc -->
            <div class="flex items-center gap-3 text-xs font-medium bg-white px-3 py-2 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-gray-100 border border-gray-300"></span> Trống</div>
                <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-100 border border-red-500"></span> Chờ duyệt</div>
                <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-100 border border-green-500"></span> Có khách</div>
                <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-100 border border-yellow-500"></span> Bảo trì</div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.dat-phong.history') }}" class="btn-secondary inline-flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i> Lịch sử
                </a>
                <a href="{{ route('admin.dat-phong.trash') }}" class="btn-secondary inline-flex items-center gap-2 text-sm text-red-600 hover:text-red-700">
                    <i class="fa-solid fa-trash"></i> Thùng rác
                </a>
                <a href="{{ route('admin.dat-phong.them') }}" class="btn-primary inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fa fa-plus"></i> Tạo đơn mới
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200" role="alert">
            <i class="fa-solid fa-check-circle mr-1"></i> <span class="font-medium">Thành công!</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> <span class="font-medium">Lỗi!</span> {{ session('error') }}
        </div>
    @endif
    
    <!-- GRID SƠ ĐỒ PHÒNG -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach($paginatedPhongs as $phong)
            @php
                // Logic xác định trạng thái phòng để hiển thị
                $activeBooking = null;
                // Mặc định: Trống (Xám)
                $statusColor = 'bg-white border-gray-200 hover:border-gray-300 text-gray-500'; 
                $icon = 'fa-door-open';
                $statusText = 'Phòng trống';
                $ringColor = '';

                // Tìm đơn đặt phòng ACTIVE trong danh sách eager loaded
                // (Vì query trong controller đã filter theo status, ta chỉ cần tìm cái không null)
                $chiTiet = $phong->chiTietDatPhongs->first(function($detail) {
                    return $detail->datPhong !== null;
                });

                if ($chiTiet) {
                    $activeBooking = $chiTiet->datPhong;
                    
                    if ($activeBooking->trang_thai == 'pending') {
                        // Đỏ: Chờ duyệt
                        $statusColor = 'bg-red-50 border-red-200 hover:border-red-300 text-red-700';
                        $ringColor = 'ring-2 ring-red-500 ring-offset-1';
                        $icon = 'fa-clock';
                        $statusText = 'Chờ duyệt';
                    } elseif (in_array($activeBooking->trang_thai, ['confirmed', 'paid', 'awaiting_payment'])) {
                        // Xanh: Đang hoạt động (Có khách)
                        $statusColor = 'bg-green-50 border-green-200 hover:border-green-300 text-green-700';
                        $ringColor = 'ring-2 ring-green-500 ring-offset-1';
                        $icon = 'fa-user-check';
                        $statusText = 'Có khách';
                    }
                } elseif ($phong->tinh_trang === 'maintenance') {
                    // Vàng: Bảo trì (Nếu có logic này)
                    $statusColor = 'bg-yellow-50 border-yellow-200 text-yellow-700';
                    $icon = 'fa-screwdriver-wrench';
                    $statusText = 'Bảo trì';
                }
            @endphp

            <!-- ROOM CARD -->
            <a href="{{ route('admin.dat-phong.room-detail', $phong->id) }}" class="relative block p-6 rounded-xl border {{ $statusColor }} transition-all duration-200 cursor-pointer shadow-sm flex flex-col items-center justify-center h-40 {{ $activeBooking ? 'hover:shadow-md transform hover:-translate-y-1' : '' }} {{ $activeBooking && $activeBooking->trang_thai == 'pending' ? 'animate-pulse-border' : '' }}">
                
                <span class="text-3xl font-bold mb-2 tracking-tight">{{ $phong->so_phong }}</span>
                
                <div class="flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid {{ $icon }}"></i>
                    <span>{{ $statusText }}</span>
                </div>

                @if($activeBooking)
                    <!-- Chỉ báo trạng thái góc -->
                    <span class="absolute top-3 right-3 flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $activeBooking->trang_thai == 'pending' ? 'bg-red-400' : 'bg-green-400' }}"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 {{ $activeBooking->trang_thai == 'pending' ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    </span>

                    <!-- Thông tin đơn (mã, ngày, trạng thái) -->
                    <div class="absolute bottom-3 w-full text-center px-2">
                        <p class="text-xs truncate opacity-80">Đơn #{{ $activeBooking->id }} · {{ \Carbon\Carbon::parse($activeBooking->ngay_den)->format('d/m') }}‑{{ \Carbon\Carbon::parse($activeBooking->ngay_di)->format('d/m') }} · {{ ucfirst($activeBooking->trang_thai) }}</p>
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    <!-- PHÂN TRANG -->
    <div class="mt-8">
        {{ $paginatedPhongs->links() }}
    </div>

    <!-- MODAL CHI TIẾT ĐẶT PHÒNG -->
    <div x-show="modalOpen" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.away="closeModal()" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-bed text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                                    Chi tiết phòng <span class="text-blue-600 text-xl" x-text="selectedBooking?.room"></span>
                                </h3>
                                <div class="mt-4 border-t border-gray-100 pt-4">
                                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Khách hàng</p>
                                            <p class="font-medium text-gray-900" x-text="selectedBooking?.customer"></p>
                                            <p x-text="selectedBooking?.phone"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Mã đơn</p>
                                            <p class="font-mono text-gray-900">#<span x-text="selectedBooking?.id"></span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 grid grid-cols-2 gap-4 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                        <div>
                                            <p class="text-xs text-gray-400">Ngày nhận</p>
                                            <p class="font-semibold text-gray-800" x-text="selectedBooking?.checkin"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400">Ngày trả</p>
                                            <p class="font-semibold text-gray-800" x-text="selectedBooking?.checkout"></p>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex justify-between items-center text-sm">
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Tổng tiền</p>
                                            <p class="text-lg font-bold text-blue-600"><span x-text="selectedBooking?.total"></span> đ</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-400 uppercase font-bold">Thanh toán</p>
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset" 
                                                  :class="selectedBooking?.payment_status === 'paid' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-yellow-50 text-yellow-800 ring-yellow-600/20'">
                                                <span x-text="selectedBooking?.payment_status"></span>
                                            </span>
                                            <p class="text-xs text-gray-400 mt-1" x-text="selectedBooking?.payment_method"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-400 uppercase font-bold">Ghi chú</p>
                                        <p class="text-sm italic text-gray-600 bg-gray-50 p-2 rounded border border-gray-100" x-text="selectedBooking?.note"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        
                        <!-- Nút Duyệt (Chỉ hiện khi Pending) -->
                        <template x-if="selectedBooking?.status === 'pending'">
                            <a :href="selectedBooking?.approve_url" 
                               onclick="return confirm('Xác nhận DUYỆT đơn đặt phòng này?')"
                               class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">
                               <i class="fa-solid fa-check mr-2 mt-0.5"></i> Duyệt đơn
                            </a>
                        </template>
                        
                        <!-- Nút Từ chối / Hủy -->
                        <template x-if="selectedBooking?.status !== 'cancelled'">
                            <a :href="selectedBooking?.cancel_url" 
                               onclick="return confirm('Xác nhận TỪ CHỐI/HỦY đơn này?')"
                               class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                               <i class="fa-solid fa-ban mr-2 mt-0.5"></i> Hủy đơn
                            </a>
                        </template>

                        <!-- Nút Xem Hóa đơn -->
                        <a :href="selectedBooking?.invoice_url" 
                           class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                           Xem Hóa đơn
                        </a>
                        
                        <button type="button" @click="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection