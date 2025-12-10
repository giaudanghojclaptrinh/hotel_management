@extends('admin.layouts.dashboard')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4">
            <h2 class="text-lg font-semibold">Sửa loại phòng</h2>
            <a href="{{ route('admin.loai-phong') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('admin.loai-phong.sua', ['id' => $loaiPhong->id]) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="ten_loai_phong">Tên loại phòng</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" id="ten_loai_phong" name="ten_loai_phong" value="{{ $loaiPhong->ten_loai_phong }}" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="gia">Giá (VND)</label>
                    <input type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="gia" name="gia" value="{{ $loaiPhong->gia }}" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="so_nguoi">Số người</label>
                    <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="so_nguoi" name="so_nguoi" min="1" value="{{ $loaiPhong->so_nguoi }}" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="tien_nghi">Tiện nghi (JSON hoặc liệt kê)</label>
                    <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tien_nghi" name="tien_nghi" rows="3">{{ $loaiPhong->tien_nghi }}</textarea>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2">
                        <i class="fa fa-save"></i> Lưu
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
