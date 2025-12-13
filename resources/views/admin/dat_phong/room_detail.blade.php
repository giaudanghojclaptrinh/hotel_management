@extends('admin.layouts.dashboard')
@section('title', 'Chi tiết Phòng ' . ($phong->so_phong ?? $phong->id))
@section('header', 'Thông tin phòng')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between mb-8 lux-card p-6 rounded-2xl shadow-lg border border-gray-800">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-xl bg-gray-800 flex items-center justify-center text-brand-gold shadow-md border border-gray-700">
                <span class="font-serif text-3xl font-bold">{{ $phong->so_phong ?? $phong->id }}</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Lịch sử & Trạng thái phòng</h1>
                <p class="text-sm text-gray-400 mt-1">
                    Loại: <span class="font-medium text-brand-gold">{{ $phong->loaiPhong->ten_loai ?? 'N/A' }}</span> • 
                    Giá: <span class="font-medium text-white">{{ number_format($phong->loaiPhong->gia ?? 0) }} đ/đêm</span>
                </p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dat-phong') }}" class="px-5 py-2.5 bg-gray-800 text-gray-300 rounded-xl font-bold hover:bg-gray-700 hover:text-white transition-all border border-gray-700 shadow-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
            </a>
            <a href="{{ route('admin.dat-phong.them', ['room_id' => $phong->id]) }}" class="px-5 py-2.5 bg-brand-gold text-gray-900 rounded-xl font-bold hover:bg-white shadow-md transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Tạo đơn mới
            </a>
        </div>
    </div>

    <div class="lux-card px-6 py-4 rounded-xl shadow-sm border border-gray-800 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <span class="text-sm font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap"><i class="fa-solid fa-filter mr-2"></i> Bộ lọc:</span>
        <form method="GET" class="flex-1 w-full flex gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm theo tên khách, mã đơn..." 
                   class="flex-1 rounded-lg bg-gray-900 border-gray-700 text-white placeholder-gray-500 focus:border-brand-gold focus:ring-brand-gold h-10">
            
            <select name="status" class="rounded-lg bg-gray-900 border-gray-700 text-white focus:border-brand-gold focus:ring-brand-gold h-10 cursor-pointer">
                <option value="">-- Tất cả --</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            
            <button type="submit" class="px-6 py-2 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white transition-all h-10">Lọc</button>
        </form>
    </div>

    <div class="space-y-6">
        @forelse($datPhongs as $bk)
            <div class="relative lux-card rounded-2xl shadow-md border border-gray-800 overflow-hidden group hover:border-brand-gold/30 transition-all duration-300 
                {{ $bk->trang_thai == 'pending' ? 'ring-1 ring-red-500' : '' }}">
                
                <div class="absolute top-0 right-0 px-4 py-1.5 rounded-bl-2xl text-xs font-bold uppercase tracking-wider text-white
                    {{ $bk->trang_thai == 'pending' ? 'bg-red-600' : 
                      ($bk->trang_thai == 'confirmed' ? 'bg-green-600' : 
                      ($bk->trang_thai == 'completed' ? 'bg-gray-600' : 'bg-gray-700 text-gray-400')) }}">
                    {{ $bk->trang_thai }}
                </div>

                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8">
                    
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-brand-gold font-serif font-bold text-lg border border-gray-700">
                                {{ substr($bk->user->name ?? 'G', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">{{ $bk->user->name ?? 'Khách vãng lai' }}</h3>
                                <p class="text-sm text-gray-400">{{ $bk->user->phone ?? '---' }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-gray-400 bg-gray-800/50 p-4 rounded-xl border border-gray-800">
                            <div class="flex justify-between">
                                <span>Mã đơn:</span> <span class="font-mono font-bold text-brand-gold">#{{ $bk->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Thanh toán:</span> <span class="font-medium text-gray-300">{{ ucfirst($bk->phuong_thuc_thanh_toan) }}</span>
                            </div>
                            @if($bk->ghi_chu)
                            <div class="pt-2 border-t border-gray-700 mt-2 text-xs italic text-yellow-500">
                                <i class="fa-solid fa-note-sticky mr-1"></i> "{{ $bk->ghi_chu }}"
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col justify-center border-l border-gray-800 md:pl-8">
                        <div class="flex items-center justify-between mb-4 text-gray-300">
                            <div class="text-center">
                                <span class="block text-xs text-gray-500 uppercase">Check-in</span>
                                <span class="block text-lg font-bold text-white">{{ \Carbon\Carbon::parse($bk->ngay_den)->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-gray-600"><i class="fa-solid fa-arrow-right-long text-xl"></i></div>
                            <div class="text-center">
                                <span class="block text-xs text-gray-500 uppercase">Check-out</span>
                                <span class="block text-lg font-bold text-white">{{ \Carbon\Carbon::parse($bk->ngay_di)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="text-center mt-2">
                            <span class="block text-xs text-gray-500 uppercase mb-1">Tổng thanh toán</span>
                            <span class="block text-3xl font-serif font-bold text-brand-gold">{{ number_format($bk->tong_tien) }} đ</span>
                        </div>
                    </div>

                    <div class="w-full md:w-48 flex flex-col gap-2 justify-center border-l border-gray-800 md:pl-8">
                        
                        @if($bk->trang_thai == 'pending')
                            <button data-id="{{ $bk->id }}" class="ajax-approve w-full py-3 bg-green-600 hover:bg-green-500 text-white rounded-lg font-bold shadow-md transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> DUYỆT
                            </button>
                            <button data-id="{{ $bk->id }}" class="ajax-cancel w-full py-2 bg-transparent border border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-lg font-bold transition-all">
                                Từ chối
                            </button>
                        
                        @elseif($bk->trang_thai != 'cancelled')
                            <a href="{{ route('admin.dat-phong.sua', $bk->id) }}" class="w-full py-2 bg-gray-800 border border-gray-600 text-gray-300 hover:text-white hover:border-brand-gold rounded-lg font-medium text-center transition-all">
                                <i class="fa-solid fa-pen-to-square mr-2"></i> Sửa
                            </a>
                            <button data-id="{{ $bk->id }}" class="ajax-cancel w-full py-2 text-red-500 hover:text-red-400 hover:bg-red-900/20 rounded-lg font-medium transition-all text-sm">
                                Hủy đơn
                            </button>
                            @if($bk->hoaDon)
                                <a href="{{ route('admin.dat-phong.hoa-don', $bk->id) }}" class="w-full py-2 text-brand-gold hover:text-white text-center text-sm font-bold hover:underline">
                                    Xem Hóa đơn
                                </a>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 lux-card rounded-2xl border border-dashed border-gray-700">
                <div class="inline-flex w-16 h-16 rounded-full bg-gray-800 items-center justify-center text-gray-600 mb-4">
                    <i class="fa-solid fa-clipboard-list text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-300">Chưa có lịch sử đặt phòng</h3>
                <p class="text-gray-500 mt-1">Phòng này chưa có đơn đặt nào theo bộ lọc hiện tại.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $datPhongs->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logic JS giữ nguyên
    function postAction(url) {
        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        }).then(r => r.json());
    }

    document.querySelectorAll('.ajax-approve').forEach(btn => {
        btn.addEventListener('click', function() {
            if(!confirm('Xác nhận DUYỆT đơn đặt phòng này?')) return;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...';
            this.disabled = true;
            postAction('{{ url('/admin/dat-phong') }}/duyet/' + this.dataset.id)
                .then(res => {
                    if(res.status === 'success' || res.success) window.location.reload();
                    else { alert('Lỗi: ' + (res.message || 'Không xác định')); this.innerHTML = originalText; this.disabled = false; }
                })
                .catch(() => { alert('Lỗi kết nối!'); this.innerHTML = originalText; this.disabled = false; });
        });
    });

    document.querySelectorAll('.ajax-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            if(!confirm('Xác nhận HỦY đơn đặt phòng này?')) return;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
            this.disabled = true;
            postAction('{{ url('/admin/dat-phong') }}/huy/' + this.dataset.id).then(() => window.location.reload());
        });
    });
</script>
@endpush