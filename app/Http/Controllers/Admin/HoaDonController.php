<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\DatPhong; // Import Model DatPhong để lấy dữ liệu nếu cần

class HoaDonController extends Controller
{
    
    public function getDanhSach(Request $request)
    {
        // 1. Khởi tạo Query với eager loading datPhong.user
        $query = HoaDon::with('datPhong.user');

        // 2. Xử lý bộ lọc
        if ($request->q) {
            $query->where(function($q) use ($request) {
                $q->where('ma_hoa_don', 'like', '%'.$request->q.'%')
                  ->orWhereHas('datPhong.user', function($qq) use ($request) {
                      $qq->where('name', 'like', '%'.$request->q.'%');
                  });
            });
        }

        if ($request->status) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // 3. Tính toán thống kê (clone query để không ảnh hưởng phân trang)
        $statsQuery = clone $query;
        $totalRevenue = (clone $statsQuery)->where('trang_thai', 'paid')->sum('tong_tien');
        $countPaid = (clone $query)->where('trang_thai', 'paid')->count();
        $countUnpaid = (clone $query)->where('trang_thai', 'unpaid')->count();

        // 4. Lấy dữ liệu phân trang, sắp xếp mới nhất trước
        $hoaDons = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.hoa_don.danh_sach', compact('hoaDons', 'totalRevenue', 'countPaid', 'countUnpaid'));
    }

    public function getThem()
    {
        // Có thể cần truyền danh sách DatPhong IDs để chọn
        $datPhongs = DatPhong::all(); 
        return view('admin.hoa_don.them', compact('datPhongs'));
    }

    public function postThem(Request $request)
    {
        // [CẬP NHẬT] Thêm validation cho cột thanh toán và ghi chú
        $data = $request->validate([
            'dat_phong_id' => 'required|exists:dat_phongs,id',
            'ma_hoa_don' => 'required|string|max:100|unique:hoa_dons,ma_hoa_don', // Thêm unique check
            'ngay_lap' => 'required|date',
            'tong_tien' => 'required|numeric|min:0',
            'phuong_thuc_thanh_toan' => 'required|string|max:50', // Cột bắt buộc
            'trang_thai' => 'nullable|string|max:50',
            'ghi_chu' => 'nullable|string', // Thêm ghi chú
        ]);

        $orm = new HoaDon();
        $orm->dat_phong_id = $data['dat_phong_id'];
        $orm->ma_hoa_don = $data['ma_hoa_don'];
        $orm->ngay_lap = $data['ngay_lap'];
        $orm->tong_tien = $data['tong_tien'];
        
        // [CẬP NHẬT] Lưu phương thức thanh toán
        $orm->phuong_thuc_thanh_toan = $data['phuong_thuc_thanh_toan']; 
        if (Schema::hasColumn('hoa_dons', 'ghi_chu')) {
            $orm->ghi_chu = $data['ghi_chu'] ?? null;
        }

        $orm->trang_thai = $data['trang_thai'] ?? 'unpaid';
        $orm->save();
        
        return redirect()->route('admin.hoa-don')->with('success', 'Thêm hóa đơn thành công!');
    }

    public function getSua($id)
    {
        $hoaDon = HoaDon::findOrFail($id); // Sửa tên biến từ $hoaDons -> $hoaDon
        $datPhongs = DatPhong::all();
        return view('admin.hoa_don.sua', compact('hoaDon', 'datPhongs'));
    }

    public function postSua(Request $request, $id)
    {
        // [CẬP NHẬT] Thêm validation cho cột thanh toán và ghi chú
        $data = $request->validate([
            'dat_phong_id' => 'required|exists:dat_phongs,id',
            // Unique, bỏ qua chính nó
            'ma_hoa_don' => 'required|string|max:100|unique:hoa_dons,ma_hoa_don,'.$id, 
            'ngay_lap' => 'required|date',
            'tong_tien' => 'required|numeric|min:0',
            'phuong_thuc_thanh_toan' => 'required|string|max:50', // Cột bắt buộc
            'trang_thai' => 'nullable|string|max:50',
            'ghi_chu' => 'nullable|string', 
        ]);

        $orm = HoaDon::findOrFail($id);
        $orm->dat_phong_id = $data['dat_phong_id'];
        $orm->ma_hoa_don = $data['ma_hoa_don'];
        $orm->ngay_lap = $data['ngay_lap'];
        $orm->tong_tien = $data['tong_tien'];
        
        // [CẬP NHẬT] Lưu phương thức thanh toán và ghi chú
        $orm->phuong_thuc_thanh_toan = $data['phuong_thuc_thanh_toan']; 
        if (Schema::hasColumn('hoa_dons', 'ghi_chu')) {
            $orm->ghi_chu = $data['ghi_chu'] ?? $orm->ghi_chu;
        }

        $orm->trang_thai = $data['trang_thai'] ?? $orm->trang_thai;
        $orm->save();
        
        return redirect()->route('admin.hoa-don')->with('success', 'Cập nhật hóa đơn thành công!');
    }

    public function getXoa($id)
    {
        $orm = HoaDon::findOrFail($id);
        $orm->delete();
        return redirect()->route('admin.hoa-don')->with('success', 'Xóa hóa đơn thành công!');
    }
}