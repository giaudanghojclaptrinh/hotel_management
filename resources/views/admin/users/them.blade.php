@extends('admin.layouts.dashboard')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-common p-6">
        <div class="toolbar mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Thêm người dùng</h2>
            <a href="{{ route('admin.user') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Quay lại</a>
        </div>

        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="name">Tên</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="phone">Số điện thoại</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="cccd">CCCD</label>
                    <input type="text" id="cccd" name="cccd" value="{{ old('cccd') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('cccd') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('username') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="role">Vai trò</label>
                    <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-brand-900 focus:border-brand-900" />
                    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.user') }}" class="text-sm text-gray-600 hover:underline">Hủy</a>
                <button type="submit" class="btn-primary">Thêm</button>
            </div>
        </form>
    </div>
</div>
@endsection

