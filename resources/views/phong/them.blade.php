@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Thêm phòng</h2>
            <a href="{{ route('phong') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('phong.them') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="so_phong">Số phòng</label>
                    <input type="text" id="so_phong" name="so_phong" value="{{ old('so_phong') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('so_phong') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="loai_phong_id">Loại phòng</label>
                    <select id="loai_phong_id" name="loai_phong_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900">
                        @foreach($loaiPhongs as $lp)
                            <option value="{{ $lp->id }}" {{ old('loai_phong_id') == $lp->id ? 'selected' : '' }}>{{ $lp->ten_loai_phong }}</option>
                        @endforeach
                    </select>
                    @error('loai_phong_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="tinh_trang">Tình trạng</label>
                    <select id="tinh_trang" name="tinh_trang" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900">
                        @foreach(\App\Models\Phong::tinh_trang_options as $key => $meta)
                            <option value="{{ $key }}" {{ old('tinh_trang') == $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                    @error('tinh_trang') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('phong') }}" class="text-sm text-gray-600 hover:underline">Hủy</a>
                <button type="submit" class="btn-primary">Thêm</button>
            </div>
        </form>
    </div>
</div>
@endsection

