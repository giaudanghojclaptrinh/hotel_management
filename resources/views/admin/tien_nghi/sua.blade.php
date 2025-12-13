@extends('admin.layouts.dashboard')
@section('title', 'Sửa Tiện nghi')
@section('header', 'Cập nhật tiện nghi')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">
            Cập nhật: <span class="text-brand-gold">{{ $tienNghi->ten_tien_nghi }}</span>
        </h1>
        <a href="{{ route('admin.tien-nghi') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="bg-gray-800/50 px-8 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-brand-gold uppercase tracking-wider">Thông tin chi tiết</h3>
        </div>

        <div class="p-8">
            
            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-900/20 border border-red-800 text-red-400 text-sm shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tien-nghi.update', $tienNghi->id) }}">
                @csrf
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Tên tiện nghi <span class="text-red-500">*</span></label>
                        <input type="text" name="ten_tien_nghi" value="{{ old('ten_tien_nghi', $tienNghi->ten_tien_nghi) }}" 
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 placeholder-gray-600" 
                               required />
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Mã tiện nghi <span class="text-red-500">*</span></label>
                        <input type="text" name="ma_tien_nghi" value="{{ old('ma_tien_nghi', $tienNghi->ma_tien_nghi) }}" 
                               class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 placeholder-gray-600 font-mono" 
                               required />
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2">Biểu tượng (FontAwesome Class)</label>
                        <div class="flex gap-4 items-start">
                            <div class="flex-1">
                                <input type="text" name="icon" id="iconInput" value="{{ old('icon', $tienNghi->icon) }}" 
                                       class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 placeholder-gray-600 font-mono text-sm" 
                                       placeholder="fa-solid fa-wifi" 
                                       oninput="updateIconPreview(this.value)" />
                                <p class="text-xs text-gray-500 mt-2">
                                    Tìm icon tại <a href="https://fontawesome.com/search?m=free" target="_blank" class="text-brand-gold hover:underline font-bold">FontAwesome Free</a>.
                                </p>
                            </div>
                            
                            <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-gray-800 rounded-lg shadow-sm border border-gray-700 text-brand-gold text-xl transition-all">
                                <i class="{{ $tienNghi->icon ?? 'fa-solid fa-icons' }}" id="iconPreview"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.tien-nghi') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                            <i class="fa-solid fa-check mr-2"></i> Cập nhật
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
        if (!val) {
            iconEl.className = 'fa-solid fa-icons';
            return;
        }
        iconEl.className = val;
    }
</script>
@endpush
@endsection