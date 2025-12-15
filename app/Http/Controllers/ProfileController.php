<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Hiển thị form cập nhật hồ sơ
     */
    public function edit()
    {
        $user = Auth::user();
        return view('client.profile.edit', compact('user'));
    }

    /**
     * Lưu thông tin hồ sơ
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:15', Rule::unique('users')->ignore($user->id)],
            'cccd' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
        ], [
            // Tên
            'name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'name.string' => 'Họ và tên không hợp lệ.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            
            // Email
            'email.required' => 'Vui lòng nhập địa chỉ email của bạn.',
            'email.email' => 'Bạn đã nhập sai định dạng email. Vui lòng kiểm tra lại.',
            'email.unique' => 'Email này đã được sử dụng bởi tài khoản khác.',
            
            // Số điện thoại
            'phone.required' => 'Số điện thoại là bắt buộc để đặt phòng. Vui lòng cập nhật.',
            'phone.string' => 'Số điện thoại không hợp lệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng bởi tài khoản khác.',
            
            // CCCD/CMND
            'cccd.required' => 'CCCD/CMND là bắt buộc để làm thủ tục lưu trú. Vui lòng cập nhật.',
            'cccd.string' => 'CCCD/CMND không hợp lệ.',
            'cccd.max' => 'CCCD/CMND không được vượt quá 20 ký tự.',
            'cccd.unique' => 'CCCD/CMND này đã được sử dụng bởi tài khoản khác.',
        ]);

        // Cập nhật
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cccd' => $request->cccd,
        ]);

        // QUAN TRỌNG: Nếu trước đó User bị chặn khi đặt phòng (do thiếu thông tin),
        // hệ thống sẽ lấy lại URL cũ và chuyển hướng họ quay lại trang đặt phòng.
        if (session()->has('url.intended')) {
            return redirect(session()->pull('url.intended'));
        }

        return redirect()->route('profile.edit')->with('success', 'Cập nhật hồ sơ thành công!');
    }
}