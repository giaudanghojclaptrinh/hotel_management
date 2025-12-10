<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatPhong;
use App\Models\User;
use App\Models\Phong; // <-- Import Model Phong
use App\Models\HoaDon; // <-- ĐÃ THÊM LOGIC CHO HÓA ĐƠN
use App\Models\ChiTietDatPhong; // <-- Đã có
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Import DB

class DatPhongController extends Controller
{
    /**
     * Hiển thị danh sách các đơn đặt phòng (Sử dụng Eager Loading).
     */
    public function getDanhSach()
    {
        // Eager loading user, chi tiết, và loại phòng để hiển thị thông tin đầy đủ
        $datPhongs = DatPhong::with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
                             ->orderBy('created_at', 'desc')
                             ->get();
        
        return view('admin.dat_phong.danh_sach', compact('datPhongs'));
    }

    // Các hàm getThem, postThem, getSua, postSua giữ nguyên...
    public function getThem()
    {
        $users = User::all();
        return view('admin.dat_phong.them', compact('users'));
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ngay_den' => 'required|date',
            'ngay_di' => 'required|date|after_or_equal:ngay_den',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = new DatPhong();
        $orm->user_id = $data['user_id'];
        $orm->ngay_den = $data['ngay_den'];
        $orm->ngay_di = $data['ngay_di'];
        $orm->trang_thai = $data['trang_thai'] ?? 'pending'; 
        $orm->save();
        
        return redirect()->route('admin.dat-phong')->with('success', 'Thêm đơn đặt phòng thành công!');
    }
    
    public function getSua($id)
    {
        $datPhong = DatPhong::findOrFail($id);
        $users = User::all();
        return view('admin.dat_phong.sua', compact('datPhong', 'users'));
    }

    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ngay_den' => 'required|date',
            'ngay_di' => 'required|date|after_or_equal:ngay_den',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = DatPhong::findOrFail($id);
        $orm->user_id = $data['user_id'];
        $orm->ngay_den = $data['ngay_den'];
        $orm->ngay_di = $data['ngay_di'];
        $orm->trang_thai = $data['trang_thai'] ?? $orm->trang_thai;
        $orm->save();
        
        return redirect()->route('admin.dat-phong')->with('success', 'Cập nhật đơn đặt phòng thành công!');
    }

    // --- HÀM XỬ LÝ HÓA ĐƠN & THANH TOÁN (FIXED) ---
    
    /**
     * Hiển thị chi tiết hóa đơn / Trang xử lý thanh toán.
     * @param int $dat_phong_id ID của đơn đặt phòng
     */
    public function getHoaDon($dat_phong_id)
    {
        // Lấy thông tin đơn đặt phòng chi tiết
        $datPhong = DatPhong::with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
                             ->findOrFail($dat_phong_id);

        // Lấy hoặc tạo mới hóa đơn nếu chưa tồn tại
        $hoaDon = HoaDon::firstOrCreate(
            ['dat_phong_id' => $dat_phong_id],
            [
                'ma_hoa_don' => 'HD' . time() . rand(100, 999), 
                'ngay_lap' => now(),
                'tong_tien' => $datPhong->tong_tien, 
                'phuong_thuc_thanh_toan' => $datPhong->payment_method ?? 'cash',
                'trang_thai' => $datPhong->payment_status,
            ]
        );

        return view('admin.dat_phong.hoa_don_chi_tiet', compact('datPhong', 'hoaDon'));
    }
    
    /**
     * Xử lý cập nhật trạng thái thanh toán của Hóa đơn và Đơn đặt phòng.
     * @param int $dat_phong_id ID của đơn đặt phòng
     */
    public function postThanhToan(Request $request, $dat_phong_id)
    {
        $request->validate([
            'trang_thai' => 'required|in:paid,unpaid', 
            'phuong_thuc_thanh_toan' => 'required|string',
        ]);
        
        DB::beginTransaction();
        try {
            $hoaDon = HoaDon::where('dat_phong_id', $dat_phong_id)->firstOrFail();
            $hoaDon->update([
                'trang_thai' => $request->trang_thai,
                'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
            ]);

            $datPhong = DatPhong::findOrFail($dat_phong_id);
            $datPhong->update([
                'payment_status' => $request->trang_thai,
                'payment_method' => $request->phuong_thuc_thanh_toan,
            ]);
            
            DB::commit();
            return back()->with('success', 'Cập nhật trạng thái thanh toán thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật thanh toán: ' . $e->getMessage());
        }
    }


    // --- HÀM XÓA ĐƠN ĐẶT PHÒNG ĐƠN LẺ ---
    public function getXoa($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs')->findOrFail($id);
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            
            // 1. Nhả phòng (nếu phòng đang bị khóa)
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong && $phong->tinh_trang === 'booked') {
                    $phong->update(['tinh_trang' => 'available']);
                }
            }
            
            // 2. Xóa đơn và các chi tiết/hóa đơn liên quan
            $datPhong->delete();

            DB::commit();
            return redirect()->route('admin.dat-phong')->with('success', 'Đã xóa vĩnh viễn đơn đặt phòng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống khi xóa đơn: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý Xóa Hàng Loạt đơn đặt phòng (Mass Delete).
     */
    public function xoaHangLoat(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:dat_phongs,id', 
        ]);

        $ids = $request->ids;
        $deletedCount = 0;

        DB::beginTransaction();
        try {
            $datPhongs = DatPhong::with('chiTietDatPhongs')->whereIn('id', $ids)->get();

            foreach ($datPhongs as $datPhong) {
                // 1. NHẢ PHÒNG (NẾU ĐÃ KHÓA)
                $chiTiet = $datPhong->chiTietDatPhongs->first();
                if ($chiTiet && $chiTiet->phong_id) {
                    $phong = Phong::find($chiTiet->phong_id);
                    if ($phong && $phong->tinh_trang === 'booked') {
                        $phong->update(['tinh_trang' => 'available']);
                    }
                }

                // 2. XÓA BẢN GHI
                $datPhong->delete();
                $deletedCount++;
            }

            DB::commit();
            return redirect()->route('admin.dat-phong')
                             ->with('success', "Đã xóa thành công $deletedCount đơn đặt phòng và mở lại các phòng tương ứng!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống khi xóa hàng loạt: ' . $e->getMessage());
        }
    }
    
    // --- LOGIC DUYỆT ĐƠN VÀ KHÓA PHÒNG (Giữ nguyên) ---
    public function duyetDon($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs')->findOrFail($id);
            if ($datPhong->trang_thai !== 'pending') {
                return back()->with('error', 'Đơn này đã được xử lý rồi!');
            }
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            if (!$chiTiet) {
                return back()->with('error', 'Lỗi: Đơn này không có chi tiết phòng.');
            }
            $phong = Phong::find($chiTiet->phong_id);
            if (!$phong || $phong->tinh_trang !== 'available') {
                DB::rollBack();
                return back()->with('error', 'Lỗi: Phòng vật lý (' . ($phong->so_phong ?? 'N/A') . ') hiện không trống.');
            }

            $phong->update(['tinh_trang' => 'booked']);
            $datPhong->update([
                'trang_thai' => 'confirmed',
                'payment_status' => ($datPhong->payment_method === 'online') ? 'awaiting_payment' : $datPhong->payment_status, 
            ]);

            DB::commit();
            return back()->with('success', 'Đã duyệt đơn và khóa phòng (' . $phong->so_phong . ') thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống khi duyệt đơn: ' . $e->getMessage());
        }
    }

    public function huyDon($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs')->findOrFail($id);
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong && $phong->tinh_trang === 'booked') {
                    $phong->update(['tinh_trang' => 'available']);
                }
            }

            $datPhong->update(['trang_thai' => 'cancelled']);

            DB::commit();
            return back()->with('success', 'Đã hủy đơn và mở lại phòng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống khi hủy đơn: ' . $e->getMessage());
        }
    }
}