@extends('admin.layouts.dashboard')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
	<div class="card-common p-6">
		<div class="toolbar mb-4">
			<h2 class="text-lg font-semibold">Thêm phòng</h2>
			<a href="{{ route('admin.phong') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
		</div>

		<form action="{{ route('admin.phong.store') }}" method="POST">
			@csrf
			<div class="grid grid-cols-1 gap-4">
				<div>
					<label class="block text-sm font-medium text-gray-700" for="so_phong">Số phòng</label>
					<input type="text" id="so_phong" name="so_phong" value="{{ old('so_phong') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700" for="loai_phong_id">Loại phòng</label>
					<select id="loai_phong_id" name="loai_phong_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
						<option value="">-- Chọn loại phòng --</option>
						@foreach(\App\Models\LoaiPhong::all() as $lp)
							<option value="{{ $lp->id }}">{{ $lp->ten_loai_phong }}</option>
						@endforeach
					</select>
				</div>

				<div class="flex items-center justify-end pt-2">
					<button type="submit" class="btn-primary inline-flex items-center gap-2">
						<i class="fa fa-save"></i> Thêm
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

