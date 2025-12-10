@extends('admin.layouts.dashboard')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    
    <div class="toolbar mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Quản lý Đơn đặt phòng</h1>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Thành công!</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="font-medium">Lỗi!</span> {{ session('error') }}
        </div>
    @endif
    
    <div class="card-common bg-white shadow rounded-lg overflow-hidden">
        
        <form action="{{ route('admin.dat-phong.xoa-hang-loat') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn XÓA VĨNH VIỄN các đơn đặt phòng đã chọn? Hành động này không thể hoàn tác và sẽ mở lại phòng.')">
            @csrf
            @method('DELETE') 

            <div class="toolbar p-4 flex justify-between items-center">
                <p class="text-sm text-gray-600">Tổng số đơn: {{ $datPhongs->count() }}</p>
                <button type="submit" id="mass-delete-btn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-medium disabled:opacity-50" disabled>
                    <i class="fa fa-trash"></i> Xóa đã chọn (0)
                </button>
            </div>

            <div class="overflow-x-auto">
                @if($datPhongs->isEmpty())
                    <div class="p-10 text-center flex flex-col items-center justify-center text-gray-500">
                        <i class="fa-solid fa-hotel text-4xl mb-3 text-gray-300"></i>
                        <p>Chưa có đơn đặt phòng nào trong hệ thống.</p>
                    </div>
                @else
                <table class="table-common min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left w-10">
                                <input type="checkbox" id="select-all" class="rounded text-brand-900 border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Đơn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chi tiết Phòng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Hóa đơn</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Xử lý Đơn</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Sửa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($datPhongs as $dp)
                        <tr class="hover:bg-gray-50">
                            
                            <td class="px-3 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $dp->id }}" class="row-checkbox rounded text-brand-900 border-gray-300">
                            </td>

                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">#{{ $dp->id }}</td>
                            
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                @if($dp->user)
                                    {{ $dp->user->name }} 
                                    <div class="text-xs text-gray-500">{{ $dp->user->phone }}</div>
                                @else
                                    —
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-xs text-gray-700">
                                @if($dp->chiTietDatPhongs && $dp->chiTietDatPhongs->isNotEmpty())
                                    @php $ct = $dp->chiTietDatPhongs->first(); @endphp
                                    <div class="font-medium">{{ $ct->loaiPhong->ten_loai_phong ?? 'N/A' }}</div>
                                    <div class="text-gray-500">Phòng: **{{ $ct->phong->so_phong ?? 'Chưa gán' }}**</div>
                                @else
                                    —
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-sm font-bold">
                                <div class="text-gray-900">{{ number_format($dp->tong_tien, 0, ',', '.') }} VND</div>
                                @if($dp->discount_amount > 0)
                                    <div class="text-xs text-red-500">Giảm: {{ number_format($dp->discount_amount) }}đ ({{ $dp->promotion_code }})</div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                @php
                                    $status = $dp->trang_thai;
                                    $payment = $dp->payment_status;

                                    if ($status == 'pending') $badgeClass = 'bg-yellow-100 text-yellow-800';
                                    elseif ($status == 'confirmed') $badgeClass = 'bg-blue-100 text-blue-800';
                                    elseif ($status == 'cancelled') $badgeClass = 'bg-red-100 text-red-800';
                                    else $badgeClass = 'bg-gray-100 text-gray-800';
                                @endphp
                                
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }} mb-1">
                                    {{ ucfirst($status) }}
                                </span>
                                
                                <div class="text-xs text-gray-500">
                                    @if($payment == 'paid')
                                        <span class="text-green-600 font-medium">Đã TT</span>
                                    @else
                                        <span class="text-red-500">Chưa TT</span>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.dat-phong.hoa-don', $dp->id) }}" 
                                   class="btn-action inline-flex items-center px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition text-xs font-medium" 
                                   title="Xem chi tiết thanh toán/Hóa đơn">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                </a>
                            </td>
                            
                            <td class="px-6 py-4 text-center text-xs">
                                <div class="flex flex-col space-y-1 items-center justify-center">
                                    @if($dp->trang_thai == 'pending')
                                        <a href="{{ route('admin.dat-phong.duyet', $dp->id) }}" 
                                           class="text-green-600 hover:text-green-800" 
                                           onclick="return confirm('DUYỆT đơn và KHÓA phòng này?')" 
                                           title="Duyệt đơn">
                                            <i class="fa-solid fa-check mr-1"></i> Duyệt
                                        </a>
                                        <a href="{{ route('admin.dat-phong.huy', $dp->id) }}" 
                                           class="text-red-600 hover:text-red-800" 
                                           onclick="return confirm('HỦY đơn #{{ $dp->id }}?')" 
                                           title="Hủy đơn">
                                            <i class="fa-solid fa-times mr-1"></i> Hủy
                                        </a>
                                        
                                    @elseif($dp->trang_thai == 'confirmed')
                                        <a href="{{ route('admin.dat-phong.huy', $dp->id) }}" 
                                           class="text-blue-600 hover:text-blue-800" 
                                           onclick="return confirm('Xác nhận khách đã TRẢ PHÒNG và MỞ lại phòng?')" 
                                           title="Trả phòng">
                                            <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> Trả phòng
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">—</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.dat-phong.sua', ['id' => $dp->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800" 
                                   title="Sửa thông tin cơ bản">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const deleteBtn = document.getElementById('mass-delete-btn');

        function updateDeleteButton() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            deleteBtn.textContent = 'Xóa đã chọn (' + checkedCount + ')';
            deleteBtn.disabled = checkedCount === 0;
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateDeleteButton();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (!this.checked) {
                    selectAll.checked = false;
                } else {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                }
                updateDeleteButton();
            });
        });

        updateDeleteButton(); // Khởi tạo lần đầu
    });
</script>

@endsection