<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TienNghi;
use Illuminate\Http\Request;

class TienNghiController extends Controller
{
    // Danh sách
    public function getDanhSach()
    {
        $q = request()->query('q');
        $query = TienNghi::query();
        if ($q) {
            $query->where('ten_tien_nghi', 'like', "%{$q}%")->orWhere('ma_tien_nghi', 'like', "%{$q}%");
        }
        $tienNghis = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.tien_nghi.danh_sach', compact('tienNghis'));
    }

    // Form thêm
    public function getThem()
    {
        return view('admin.tien_nghi.them');
    }

    // Xử lý thêm
    public function postThem(Request $request)
    {
        $request->validate([
            'ten_tien_nghi' => 'required|string|max:255',
            'ma_tien_nghi' => 'required|string|unique:tien_nghis,ma_tien_nghi',
            'icon' => 'nullable|string', // Ví dụ: fa-solid fa-wifi
        ]);

        TienNghi::create($request->all());

        return redirect()->route('admin.tien-nghi')->with('success', 'Thêm tiện nghi thành công!');
    }

    // Form sửa
    public function getSua($id)
    {
        $tienNghi = TienNghi::findOrFail($id);
        return view('admin.tien_nghi.sua', compact('tienNghi'));
    }

    // Xử lý sửa
    public function postSua(Request $request, $id)
    {
        $request->validate([
            'ten_tien_nghi' => 'required|string|max:255',
            'ma_tien_nghi' => 'required|string|unique:tien_nghis,ma_tien_nghi,' . $id,
            'icon' => 'nullable|string',
        ]);

        $tienNghi = TienNghi::findOrFail($id);
        $tienNghi->update($request->all());

        return redirect()->route('admin.tien-nghi')->with('success', 'Cập nhật thành công!');
    }

    // Xóa
    public function getXoa($id)
    {
        TienNghi::destroy($id);
        return redirect()->route('admin.tien-nghi')->with('success', 'Xóa thành công!');
    }

    // Bulk delete
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tien_nghis,id',
        ]);
        TienNghi::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.tien-nghi')->with('success', 'Đã xóa các tiện nghi được chọn.');
    }
}