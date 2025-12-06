@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4">
            <h2 class="text-lg font-semibold">Sửa phòng</h2>
            <a href="{{ route('phong') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('phong.sua', ['id' => $phong->id]) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="so_phong">Số phòng</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" id="so_phong" name="so_phong" value="{{ $phong->so_phong }}" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="loai_phong_id">Loại phòng</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" id="loai_phong_id" name="loai_phong_id" required>
                        @foreach($loaiPhongs as $lp)
                            <option value="{{ $lp->id }}" {{ $phong->loai_phong_id == $lp->id ? 'selected' : '' }}>{{ $lp->ten_loai_phong }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="tinh_trang">Tình trạng</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tinh_trang" name="tinh_trang">
                        @foreach(\App\Models\Phong::tinh_trang_options as $key => $meta)
                            <option value="{{ $key }}" {{ $phong->tinh_trang == $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
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
