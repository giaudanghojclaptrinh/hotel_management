@extends('admin.layouts.dashboard')
@section('title', 'Thêm Hạng phòng')
@section('header', 'Tạo hạng phòng mới')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-gray-900">Thông tin hạng phòng</h1>
        <a href="{{ route('admin.loai-phong') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-brand-gold hover:text-brand-gold transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.loai-phong.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tên hạng phòng <span class="text-red-500">*</span></label>
                            <input type="text" name="ten_loai_phong" required placeholder="Ví dụ: Deluxe King Ocean View"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all h-11">
                            @error('ten_loai_phong') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Giá niêm yết (1 đêm) <span class="text-red-500">*</span></label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" name="gia" required placeholder="0"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold pr-12 transition-all h-11 font-medium text-brand-900">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm font-serif font-bold">VNĐ</span>
                                </div>
                            </div>
                            @error('gia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả chi tiết</label>
                            <textarea name="mo_ta" rows="6" placeholder="Mô tả về tiện nghi, hướng nhìn, diện tích..."
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold transition-all"></textarea>
                            @error('mo_ta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tiện nghi (Chọn nhiều)</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($tienNghis as $tn)
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="tien_nghi[]" value="{{ $tn->id }}" class="form-checkbox" {{ in_array($tn->id, old('tien_nghi', [])) ? 'checked' : '' }}>
                                        <span>{{ $tn->ten_tien_nghi }} <small class="text-xs text-gray-400">({{ $tn->ma_tien_nghi }})</small></span>
                                    </label>
                                @endforeach
                            </div>
                            @error('tien_nghi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh đại diện</label>
                        
                        <div class="w-full aspect-[4/3] bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl hover:bg-gray-100 hover:border-brand-gold transition-all relative overflow-hidden group">
                            
                            <input id="hinh_anh" name="hinh_anh" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(event)">
                            
                            <div id="upload-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 z-10 pointer-events-none">
                                <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center mb-3">
                                    <i class="fa-regular fa-image text-xl text-brand-gold"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-600">Tải ảnh lên</p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG tối đa 2MB</p>
                            </div>

                            <img id="preview-img" src="#" class="hidden absolute inset-0 w-full h-full object-cover z-0">
                        </div>
                        @error('hinh_anh') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <h4 class="text-xs font-bold text-blue-800 uppercase mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Lưu ý</h4>
                            <p class="text-xs text-blue-600 leading-relaxed">Nên sử dụng hình ảnh tỉ lệ 4:3 hoặc 16:9 để hiển thị đẹp nhất trên website.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.loai-phong') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition-all">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-900 text-brand-gold rounded-lg font-bold hover:bg-gray-800 shadow-md transition-all flex items-center">
                        <i class="fa-solid fa-check mr-2"></i> Lưu Hạng Phòng
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
            placeholder.classList.add('hidden'); // Ẩn placeholder khi đã chọn ảnh
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endpush
@endsection