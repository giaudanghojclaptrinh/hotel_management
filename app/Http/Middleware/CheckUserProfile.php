<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra đăng nhập (để chắc chắn)
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Kiểm tra xem user có thiếu số điện thoại hoặc CCCD không
        if (empty($user->phone) || empty($user->cccd)) {
            
            // Lưu lại URL hiện tại để sau khi cập nhật xong thì redirect lại trang đặt phòng
            session()->put('url.intended', $request->url());

            // Chuyển hướng sang trang cập nhật hồ sơ với thông báo lỗi
            return redirect()->route('profile.edit')
                ->with('error', 'Vui lòng cập nhật đầy đủ Số điện thoại và CCCD để tiếp tục đặt phòng!');
        }

        // 3. Nếu đủ thông tin thì cho đi tiếp
        return $next($request);
    }
}