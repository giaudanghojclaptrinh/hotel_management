@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6 bg-white rounded-lg shadow">
        
        <div class="toolbar mb-6 flex justify-between items-center border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800">Thêm loại phòng mới</h2>
            <a href="{{ route('admin.loai-phong') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.loai-phong.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="ten_loai_phong">
                            Tên loại phòng <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold sm:text-sm" 
                               id="ten_loai_phong" name="ten_loai_phong" value="{{ old('ten_loai_phong') }}" 
                               placeholder="VD: Phòng Deluxe King" required />
                        @error('ten_loai_phong') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="gia">
                                Giá (VNĐ) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="gia" name="gia" value="{{ old('gia') }}" step="1000"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold sm:text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="so_nguoi">
                                Sức chứa (Người) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="so_nguoi" name="so_nguoi" value="{{ old('so_nguoi', 2) }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold sm:text-sm" min="1" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="dien_tich">
                            Diện tích (m²)
                        </label>
                        <input type="number" id="dien_tich" name="dien_tich" value="{{ old('dien_tich') }}" 
                               placeholder="VD: 35" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold sm:text-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tiện nghi phòng</label>
                        
                        @if(isset($tienNghis) && count($tienNghis) > 0)
                            <div class="grid grid-cols-2 gap-3 p-4 border border-gray-200 rounded-md bg-gray-50 max-h-60 overflow-y-auto">
                                @foreach($tienNghis as $tn)
                                    <label class="inline-flex items-center space-x-2 cursor-pointer group">
                                        <input type="checkbox" 
                                               name="tien_nghi[]" 
                                               value="{{ $tn->id }}" 
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm text-gray-700 group-hover:text-blue-600 transition-colors">
                                            @if($tn->icon) <i class="{{ $tn->icon }} mr-1 text-gray-400"></i> @endif
                                            {{ $tn->ten_tien_nghi }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Chọn các tiện nghi có sẵn trong phòng này.</p>
                        @else
                            <div class="p-4 bg-yellow-50 text-yellow-700 text-sm rounded-md border border-yellow-200">
                                Chưa có tiện nghi nào. Vui lòng thêm tiện nghi trước.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="hinh_anh">
                            Hình ảnh đại diện
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-brand-gold transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fa-solid fa-image text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="hinh_anh" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Tải ảnh lên</span>
                                        <input id="hinh_anh" name="hinh_anh" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                    <p class="pl-1">hoặc kéo thả vào đây</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                            </div>
                        </div>
                        <div id="image-preview" class="mt-4 hidden text-center">
                            <p class="text-xs text-gray-500 mb-2">Xem trước:</p>
                            <img id="preview-img" src="#" alt="Preview" class="h-48 w-full object-cover rounded-md border border-gray-200">
                        </div>
                        @error('hinh_anh') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 gap-3">
                <a href="{{ route('admin.loai-phong') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm font-medium transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa fa-save"></i> Lưu Loại Phòng
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview-img');
            output.src = reader.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection