@extends('admin.layouts.dashboard')
@section('title', 'Thêm Tiện nghi')
@section('header', 'Thêm mới tiện nghi')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-gray-900">Thông tin tiện nghi</h1>
        <a href="{{ route('admin.tien-nghi') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-brand-gold hover:text-brand-gold transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-8">
            
            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tien-nghi.store') }}">
                @csrf
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tên tiện nghi <span class="text-red-500">*</span></label>
                        <input type="text" name="ten_tien_nghi" value="{{ old('ten_tien_nghi') }}" 
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11" 
                               required placeholder="Ví dụ: Máy lạnh, Wifi tốc độ cao..." />
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mã tiện nghi (Code) <span class="text-red-500">*</span></label>
                        <input type="text" name="ma_tien_nghi" value="{{ old('ma_tien_nghi') }}" 
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11" 
                               required placeholder="wifi, ac, tv, pool..." />
                        <p class="text-xs text-gray-500 mt-1">Dùng để định danh trong hệ thống (nên viết liền không dấu).</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Biểu tượng (FontAwesome Class)</label>
                        <div class="flex gap-4 items-start">
                            <div class="flex-1">
                                <input type="text" name="icon" id="iconInput" value="{{ old('icon') }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11" 
                                       placeholder="fa-solid fa-wifi" 
                                       oninput="updateIconPreview(this.value)" />
                                <p class="text-xs text-gray-500 mt-1">
                                    Tìm icon tại <a href="https://fontawesome.com/search?m=free" target="_blank" class="text-brand-gold hover:underline font-bold">FontAwesome Free</a>.
                                </p>
                            </div>
                            
                            <div class="w-11 h-11 flex items-center justify-center bg-brand-900 rounded-lg shadow-sm border border-gray-200 text-brand-gold text-xl transition-all" id="iconPreviewBox">
                                <i class="fa-solid fa-icons" id="iconPreview"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.tien-nghi') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition-all">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-brand-900 text-brand-gold rounded-lg font-bold hover:bg-gray-800 shadow-md transition-all flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Lưu Tiện Nghi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateIconPreview(val) {
        const iconEl = document.getElementById('iconPreview');
        // Nếu ô trống, hiện icon mặc định
        if (!val) {
            iconEl.className = 'fa-solid fa-icons';
            return;
        }
        // Gán class mới
        iconEl.className = val;
    }
</script>
@endpush
@endsection