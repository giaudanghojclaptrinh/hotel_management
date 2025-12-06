@extends('layouts.app')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6"></div>
    <div class="toolbar mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Người dùng</h1>
        <a href="{{ route('user.them') }}" class="btn-primary inline-flex items-center gap-2 text-sm font-semibold">
            <i class="fa fa-plus"></i> Thêm người dùng
        </a>
    </div>

    <div class="card-common">
        <div class="overflow-x-auto">
            @if($users->isEmpty())
                <div class="p-6 text-center text-gray-500">Chưa có khách hàng nào. Hãy thêm mới.</div>
            @else
            <table class="table-common min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên người dùng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">số điện thoại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CCCD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quyền hạn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name}}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->cccd }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->username }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->role }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="w-32 truncate font-mono text-xs text-gray-500 bg-gray-100 p-1 rounded border border-gray-200 cursor-help" title="{{ $user->password }}">
                                {{ $user->password }}
                            </div>
                        </td>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <a href="{{ route('user.sua', ['id' => $user->id]) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="Sửa"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('user.xoa', ['id' => $user->id]) }}" onclick="return confirm('Bạn có muốn xóa phòng {{ $user->name }} không?')" class="text-red-600 hover:text-red-800" title="Xóa"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

@endsection
