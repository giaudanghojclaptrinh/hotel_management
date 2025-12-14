@extends('admin.layouts.dashboard')
@section('title', 'Chi tiết Phản hồi #' . $feedback->id)
@section('header', 'Chi tiết Phản hồi')

@section('content')
<style>
    /* CSS bổ sung cho xử lý từ dài/tràn nội dung */
    .feedback-message {
        word-break: break-word; /* Quan trọng: Ngắt từ dài để tránh tràn */
        max-height: 400px; /* Giới hạn chiều cao tối đa của nội dung */
        overflow-y: auto; /* Thêm thanh cuộn khi nội dung vượt quá giới hạn */
    }
    /* Đảm bảo style của bạn cho text-brand-gold được định nghĩa trong layout chính */
    .text-brand-gold {
        color: #d4af37; /* Giả định màu gold */
    }
</style>

<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-serif font-bold text-white">
            <i class="fa-solid fa-envelope-open-text mr-2 text-brand-gold"></i> Chi tiết Phản hồi #{{ $feedback->id }}
        </h1>
        <a href="{{ route('admin.feedbacks.index') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-brand-gold hover:border-brand-gold transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden">
        
        <div class="p-8">
            
            <div class="mb-6 pb-4 border-b border-gray-800 flex items-center justify-between">
                @if(!$feedback->handled)
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-red-900/30 text-red-400 border border-red-800">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> Trạng thái: Chưa xử lý
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-900/30 text-green-400 border border-green-800">
                        <i class="fa-solid fa-check-circle mr-1"></i> Đã xử lý
                    </span>
                @endif
                
                @if($feedback->handled)
                    <span class="text-sm text-gray-500">
                        Xử lý lúc: <strong class="text-gray-300">{{ $feedback->handled_at->format('d/m/Y H:i') }}</strong>
                    </span>
                @endif
            </div>

            <div class="space-y-4 mb-8 p-4 bg-gray-800 rounded-lg border border-gray-700">
                <p class="text-sm text-gray-400">
                    <strong class="font-bold text-white block mb-1">Người gửi:</strong> 
                    {{ $feedback->name }}
                </p>
                <p class="text-sm text-gray-400">
                    <strong class="font-bold text-white block mb-1">Email:</strong> 
                    <span class="text-brand-gold">{{ $feedback->email }}</span>
                </p>
                <p class="text-sm text-gray-400">
                    <strong class="font-bold text-white block mb-1">Ngày gửi:</strong> 
                    {{ $feedback->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="mb-8">
                <h3 class="text-md font-bold text-white mb-3 border-l-4 border-brand-gold pl-3">Nội dung phản hồi</h3>
                <div class="p-4 bg-gray-800 rounded-lg border border-gray-700 text-gray-300 whitespace-pre-wrap leading-relaxed font-serif feedback-message">
                    {{ $feedback->message }}
                </div>
            </div>

            <div class="pt-6 border-t border-gray-800 flex items-center justify-end">
                @if(!$feedback->handled)
                    <form action="{{ route('admin.feedbacks.handle', $feedback) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-brand-gold text-gray-900 rounded-lg font-bold hover:bg-white shadow-md transition-all flex items-center">
                            <i class="fa-solid fa-check-double mr-2"></i> Đánh dấu đã xử lý
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection