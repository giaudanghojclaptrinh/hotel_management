@extends('layouts.app')
@section('title', '401 — Chưa xác thực')

@section('content')
<style>
    .error-page-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: radial-gradient(circle at center, #242424 0%, #111 100%);
    }

    .error-card {
        text-align: center;
        position: relative;
        z-index: 10;
        padding: 2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Icon chìm làm nền */
    .bg-icon-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 15rem;
        color: rgba(205, 164, 94, 0.03);
        z-index: 0;
        pointer-events: none;
    }

    .error-code {
        font-family: 'Playfair Display', serif;
        font-size: 8rem;
        font-weight: 700;
        line-height: 1;
        color: #cda45e;
        margin: 0;
        text-shadow: 0 10px 30px rgba(205, 164, 94, 0.2);
        animation: float 3s ease-in-out infinite;
    }

    .gold-divider {
        width: 60px;
        height: 3px;
        background-color: #cda45e;
        margin: 1.5rem auto;
        border-radius: 2px;
    }

    .error-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #fff;
        margin-bottom: 1rem;
    }

    .error-desc {
        color: #a3a3a3;
        font-size: 1.1rem;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    .btn-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    .btn-gold {
        background: #cda45e;
        color: #000;
        border: 1px solid #cda45e;
        box-shadow: 0 4px 15px rgba(205, 164, 94, 0.3);
    }
    .btn-gold:hover {
        background: #d9b876;
        transform: translateY(-2px);
    }

    .btn-dark-outline {
        background: transparent;
        color: #cda45e;
        border: 1px solid rgba(205, 164, 94, 0.5);
    }
    .btn-dark-outline:hover {
        border-color: #cda45e;
        background: rgba(205, 164, 94, 0.1);
        color: #fff;
    }
</style>

<div class="error-page-wrapper">
    <div class="bg-icon-watermark">
        <i class="fa-solid fa-user-lock"></i>
    </div>

    <div class="container">
        <div class="error-card">
            <h1 class="error-code">401</h1>
            
            <div class="gold-divider"></div>
            
            <h2 class="error-title">Chưa xác thực</h2>
            
            <p class="error-desc">
                Bạn chưa đăng nhập hoặc phiên làm việc đã hết hạn.<br>
                Vui lòng đăng nhập để tiếp tục truy cập tài nguyên này.
            </p>

            <div class="action-buttons">
                <a href="{{ route('login') }}" class="btn-custom btn-gold">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Đăng nhập ngay
                </a>
                
                <a href="{{ url('/') }}" class="btn-custom btn-dark-outline">
                    <i class="fa-solid fa-house mr-2"></i> Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection