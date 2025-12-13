@extends('admin.layouts.dashboard')
@section('title', 'Chi tiết Phòng')

@section('content')

<div class="container mx-auto py-8">
	<div class="flex items-center justify-between mb-6">
		<h1 class="text-2xl font-semibold">Chi tiết Phòng: {{ $phong->so_phong ?? $phong->id }}</h1>
		<a href="{{ route('admin.dat-phong') }}" class="btn btn-primary">Quay về Sơ đồ</a>
	</div>

	@if($datPhongs->isEmpty())
		<div class="empty-state">
			<p class="empty-title">Chưa có đơn đặt phòng cho phòng này.</p>
		</div>
	@else
		<div class="space-y-4">
			@foreach($datPhongs as $dat)
				<div class="booking-card p-4 flex items-start justify-between">
					<div class="flex gap-4 items-start">
						<div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-800">#{{ $dat->id }}</div>
						<div>
							<div class="text-lg font-semibold">{{ $dat->user->name ?? 'Khách lẻ' }} <span class="text-sm text-gray-500">({{ $dat->user->email ?? 'no-email' }})</span></div>
							<div class="text-sm text-muted">{{ \Carbon\Carbon::parse($dat->ngay_den)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dat->ngay_di)->format('d/m/Y') }} • {{ number_format($dat->tong_tien) }} VND</div>
							<div class="mt-2">
								<span class="status-badge {{ $dat->trang_thai == 'pending' ? 'status-pending' : ($dat->trang_thai == 'confirmed' ? 'status-confirmed' : '') }}">{{ strtoupper($dat->trang_thai) }}</span>
								<span class="ml-2 text-sm text-muted">Thanh toán: {{ $dat->payment_status ?? 'unpaid' }}</span>
							</div>
						</div>
					</div>

					<div class="flex items-center gap-2">
						@if($dat->trang_thai === 'pending')
							<form action="{{ route('admin.dat-phong.duyet.post', $dat->id) }}" method="POST" onsubmit="return confirm('Xác nhận duyệt đơn?');">
								@csrf
								<button class="btn btn-primary">Duyệt</button>
							</form>
						@endif

						@if($dat->trang_thai !== 'cancelled')
							<form action="{{ route('admin.dat-phong.huy.post', $dat->id) }}" method="POST" onsubmit="return confirm('Xác nhận hủy đơn?');">
								@csrf
								<button class="btn btn-danger">Hủy</button>
							</form>
						@endif

						<a href="{{ route('admin.dat-phong.sua', $dat->id) }}" class="btn btn-outline">Sửa</a>
						<a href="{{ route('admin.dat-phong.xoa', $dat->id) }}" class="btn btn-outline text-red-600" onclick="return confirm('Xác nhận xóa vĩnh viễn?');">Xóa</a>
					</div>
				</div>
			@endforeach
		</div>

		<div class="mt-6">
			{{ $datPhongs->links() }}
		</div>
	@endif

</div>

@endsection
