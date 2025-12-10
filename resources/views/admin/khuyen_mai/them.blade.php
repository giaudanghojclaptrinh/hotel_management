@extends('admin.layouts.dashboard')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
	<div class="card-common p-6">
		<div class="toolbar mb-4">
			<h2 class="text-lg font-semibold">Thêm khuyến mãi</h2>
			<a href="{{ route('admin.khuyen-mai') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
		</div>

		<form action="{{ route('admin.khuyen-mai.store') }}" method="POST">
			@csrf
			<div class="grid grid-cols-1 gap-4">
				<div>
					<label class="block text-sm font-medium text-gray-700" for="ten_khuyen_mai">Tên khuyến mãi</label>
					<input type="text" id="ten_khuyen_mai" name="ten_khuyen_mai" value="{{ old('ten_khuyen_mai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700" for="ma_khuyen_mai">Mã khuyến mãi</label>
					<input type="text" id="ma_khuyen_mai" name="ma_khuyen_mai" value="{{ old('ma_khuyen_mai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700" for="so_tien_giam_gia">Số tiền giảm (VND)</label>
					<input type="number" step="0.01" id="so_tien_giam_gia" name="so_tien_giam_gia" value="{{ old('so_tien_giam_gia') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
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

