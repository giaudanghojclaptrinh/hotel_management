@extends('layouts.app')
@section('title', 'Liên hệ - Luxury Stay')

@section('content')
<div class="contact-hero">
    <div class="container py-12">
        <h1 class="text-3xl font-bold mb-4">Liên hệ</h1>
        <p class="text-gray-600 mb-8">Mọi thắc mắc vui lòng liên hệ với chúng tôi hoặc gửi phản hồi qua form bên dưới.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="contact-card">
                <h3 class="text-xl font-semibold mb-3">Thông tin khách sạn</h3>
                <ul class="contact-list space-y-3">
                    <li><strong>Địa chỉ:</strong> Long Xuyên, An Giang</li>
                    <li><strong>Điện thoại:</strong> +84 792008096</li>
                    <li><strong>Email:</strong> booking@luxurystay.com</li>
                </ul>
            </div>

            <div class="contact-card">
                <h3 class="text-xl font-semibold mb-3">Người thiết kế website</h3>
                <ul class="contact-list space-y-2">
                    <li><strong>Tên:</strong> Trương Phước Giàu</li>
                    <li><strong>Trạng thái:</strong> Sinh viên, Đại học An Giang (Khóa 23DH)</li>
                    <li><strong>SĐT:</strong> 0792008096</li>
                    <li><strong>Email:</strong> giaudeptrainhat@gmail.com</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('resources/js/client/contact.js') }}"></script>
@endpush
