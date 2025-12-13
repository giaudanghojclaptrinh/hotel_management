@extends('admin.layouts.dashboard')
@section('title', 'Sửa Hạng phòng')
@section('header', 'Cập nhật hạng phòng')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">
            Cập nhật: <span class="text-brand-gold">{{ $loaiPhong->ten_loai_phong }}</span>
        </h1>
        <a href="{{ route('admin.loai-phong') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="p-8">
            <form action="{{ route('admin.loai-phong.update', ['id' => $loaiPhong->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 space-y-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Tên hạng phòng <span class="text-red-500">*</span></label>
                            <input type="text" name="ten_loai_phong" required value="{{ old('ten_loai_phong', $loaiPhong->ten_loai_phong) }}"
                                   class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11 placeholder-gray-500">
                            @error('ten_loai_phong') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Giá niêm yết (1 đêm) <span class="text-red-500">*</span></label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" name="gia" required value="{{ old('gia', $loaiPhong->gia) }}"
                                       class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold pr-12 transition-all h-11 font-mono font-bold">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm font-serif font-bold">VNĐ</span>
                                </div>
                            </div>
                            @error('gia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2">Mô tả chi tiết</label>
                            <textarea name="mo_ta" rows="6"
                                      class="w-full rounded-lg bg-gray-800 border-gray-700 text-white shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all placeholder-gray-500">{{ old('mo_ta', $loaiPhong->mo_ta ?? '') }}</textarea>
                            @error('mo_ta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-3">Tiện nghi (Chọn nhiều)</label>
                            <div class="grid grid-cols-2 gap-3 bg-gray-800/50 p-4 rounded-xl border border-gray-700">
                                @foreach($tienNghis as $tn)
                                    @php $checked = in_array($tn->id, old('tien_nghi', $selectedTienNghis ?? [])); @endphp
                                    <label class="inline-flex items-center gap-2 text-sm cursor-pointer group">
                                        <input type="checkbox" name="tien_nghi[]" value="{{ $tn->id }}" 
                                               class="rounded bg-gray-700 border-gray-600 text-brand-gold focus:ring-brand-gold focus:ring-offset-gray-900 transition-all" {{ $checked ? 'checked' : '' }}>
                                        <span class="text-gray-300 group-hover:text-white transition-colors">{{ $tn->ten_tien_nghi }} <small class="text-gray-500">({{ $tn->ma_tien_nghi }})</small></span>
                                    </label>
                                @endforeach
                            </div>
                            @error('tien_nghi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Hình ảnh đại diện</label>
                        
                        <div class="w-full aspect-[4/3] bg-gray-800 border-2 border-dashed border-gray-700 rounded-xl hover:border-brand-gold transition-all relative overflow-hidden group">
                            
                            <input id="hinh_anh" name="hinh_anh" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(event)">
                            
                            <div id="upload-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 z-10 pointer-events-none {{ $loaiPhong->hinh_anh ? 'hidden' : '' }}">
                                <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center mb-3">
                                    <i class="fa-regular fa-image text-xl text-gray-400"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-300">Tải ảnh lên</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG tối đa 2MB</p>
                            </div>

                            <img id="preview-img" 
                                 src="{{ $loaiPhong->hinh_anh ? asset($loaiPhong->hinh_anh) : '#' }}" 
                                 class="{{ $loaiPhong->hinh_anh ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover z-0">
                            
                            <div class="absolute inset-0 bg-black/60 items-center justify-center hidden group-hover:flex z-10 pointer-events-none transition-all">
                                <span class="text-white font-bold text-sm flex items-center"><i class="fa-solid fa-pen mr-2"></i> Đổi ảnh</span>
                            </div>
                        </div>
                        @error('hinh_anh') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <div class="bg-blue-900/20 p-4 rounded-xl border border-blue-800/50">
                            <h4 class="text-xs font-bold text-blue-400 uppercase mb-1 flex items-center"><i class="fa-solid fa-circle-info mr-1"></i> Lưu ý</h4>
                            <p class="text-xs text-blue-300 leading-relaxed">Nếu bạn không chọn ảnh mới, hệ thống sẽ giữ lại ảnh cũ hiện tại.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-800 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.loai-phong') }}" class="px-5 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview-img');
            const placeholder = document.getElementById('upload-placeholder');
            output.src = reader.result;
            output.classList.remove('hidden');
            if(placeholder) placeholder.classList.add('hidden');
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endpush
@endsection