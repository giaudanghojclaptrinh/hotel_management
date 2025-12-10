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
            'phone.required' => 'Số điện thoại là bắt buộc để đặt phòng.',
            'cccd.required' => 'CCCD/CMND là bắt buộc để làm thủ tục lưu trú.',
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