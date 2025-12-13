<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatPhong;
use App\Models\User;
use App\Models\Phong;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\BookingStatusUpdated;
use Illuminate\Pagination\LengthAwarePaginator; // Import để phân trang thủ công

class DatPhongController extends Controller
{
    /**
     * Hiển thị Sơ đồ phòng để quản lý đơn (Thay vì danh sách bảng).
     */
    public function getDanhSach(Request $request)
    {
        // 1. Lấy tất cả các phòng
        // Eager load các đơn đặt phòng ĐANG HOẠT ĐỘNG (pending, confirmed, paid, awaiting_payment)
        // Chúng ta lấy đơn mới nhất để hiển thị trạng thái
        $phongs = Phong::with(['chiTietDatPhongs.datPhong' => function($q) {
            $q->whereIn('trang_thai', ['pending', 'confirmed', 'paid', 'awaiting_payment'])
              ->with('user') // Lấy thông tin khách
              ->orderBy('created_at', 'desc'); // Lấy đơn mới nhất
        }])->get();

        // 2. Sắp xếp phòng từ nhỏ đến lớn (Natural Sort: 101, 102, 201...)
        $phongs = $phongs->sortBy('so_phong', SORT_NATURAL);

        // 3. Thực hiện phân trang thủ công (Pagination)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20; // Số phòng mỗi trang
        $currentItems = $phongs->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $paginatedPhongs = new LengthAwarePaginator($currentItems, count($phongs), $perPage);
        $paginatedPhongs->setPath($request->url());

        // Pass paginator to view under both variable names to remain compatible
        return view('admin.dat_phong.danh_sach', [
            'phongs' => $paginatedPhongs,
            'paginatedPhongs' => $paginatedPhongs,
        ]);
    }

    // [MỚI] Hàm lấy danh sách Thùng rác (Đơn bị hủy/từ chối)
    public function getThungRac()
    {
        $datPhongs = DatPhong::where('trang_thai', 'cancelled')
                             ->with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
                             ->orderBy('updated_at', 'desc')
                             ->paginate(10);
        return view('admin.dat_phong.trash', compact('datPhongs'));
    }

    // [MỚI] Hàm lấy danh sách Lịch sử (Đơn đã duyệt/hoàn thành)
    public function getLichSu()
    {
        $datPhongs = DatPhong::whereIn('trang_thai', ['confirmed', 'completed', 'paid'])
                             ->with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
                             ->orderBy('updated_at', 'desc')
                             ->paginate(10);
        return view('admin.dat_phong.history', compact('datPhongs'));
    }

    // --- CÁC HÀM XỬ LÝ (GIỮ NGUYÊN LOGIC, CHỈ CẬP NHẬT REDIRECT NẾU CẦN) ---

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

    // --- LOGIC DUYỆT ĐƠN (CẬP NHẬT ĐỂ QUAY VỀ LỊCH SỬ) ---
    public function duyetDon($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs.loaiPhong', 'user')->findOrFail($id);
            
            if ($datPhong->trang_thai !== 'pending') {
                return back()->with('error', 'Đơn này đã được xử lý rồi!');
            }
            
            // Logic khóa phòng (như cũ)
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong) $phong->update(['tinh_trang' => 'booked']);
            }

            // Cập nhật trạng thái
            $datPhong->update([
                'trang_thai' => 'confirmed',
                // Giữ nguyên logic payment status
                'payment_status' => ($datPhong->payment_method === 'online') ? 'awaiting_payment' : $datPhong->payment_status, 
            ]);

            // Gửi thông báo (như cũ)
            if ($datPhong->user) {
                $roomName = $chiTiet->loaiPhong->ten_loai_phong ?? 'Phòng';
                $message = "Đơn #{$datPhong->id} ({$roomName}) đã được xác nhận.";
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, 'Đã xác nhận', $message));
            }

            DB::commit();
            
            // [YÊU CẦU]: Duyệt xong chuyển qua view lịch sử
            return redirect()->route('admin.dat-phong.history')
                ->with('success', 'Đã duyệt đơn thành công! Đơn hàng đã chuyển sang danh sách hoạt động.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // --- LOGIC HỦY ĐƠN (CẬP NHẬT ĐỂ QUAY VỀ THÙNG RÁC) ---
    public function huyDon($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs', 'user')->findOrFail($id);
            
            // Logic nhả phòng (như cũ)
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong && $phong->tinh_trang === 'booked') {
                    $phong->update(['tinh_trang' => 'available']);
                }
            }

            // Cập nhật hủy (Chuyển vào "thùng rác" logic)
            $datPhong->update(['trang_thai' => 'cancelled']);

            // Gửi thông báo
            if ($datPhong->user) {
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, 'Đã hủy', "Đơn #{$datPhong->id} đã bị hủy."));
            }

            DB::commit();
            
            // [YÊU CẦU]: Từ chối/Hủy thì coi như vào "thùng rác" (ẩn khỏi sơ đồ active)
            return redirect()->route('admin.dat-phong.trash')
                ->with('success', 'Đã từ chối đơn hàng! Đơn đã được chuyển vào thùng rác.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // Các hàm khác (getHoaDon, postThanhToan, xoaHangLoat...) giữ nguyên để đảm bảo hệ thống chạy đúng
    // ...
    public function getHoaDon($dat_phong_id)
    {
        $datPhong = DatPhong::with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
                             ->findOrFail($dat_phong_id);

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
            
            if ($request->trang_thai === 'paid' && $datPhong->user) {
                // ... Logic thông báo ...
                $roomName = $datPhong->chiTietDatPhongs->first()->loaiPhong->ten_loai_phong ?? 'Phòng đặt';
                $message = "Đơn #{$datPhong->id} ({$roomName}) đã được xác nhận thanh toán thành công ({$request->phuong_thuc_thanh_toan}).";
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, 'Thanh toán thành công', $message));
            }

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
         // ... (Logic giữ nguyên) ...
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs', 'hoaDon')->findOrFail($id);
            // ... Logic nhả phòng ...
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong && $phong->tinh_trang === 'booked') {
                    $phong->update(['tinh_trang' => 'available']);
                }
            }
            
            $datPhong->hoaDon()->delete(); 
            $datPhong->chiTietDatPhongs()->delete();
            $datPhong->delete();

            DB::commit();
            return redirect()->route('admin.dat-phong')->with('success', 'Đã xóa vĩnh viễn đơn đặt phòng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống khi xóa đơn: ' . $e->getMessage());
        }
    }

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
            $datPhongs = DatPhong::with('chiTietDatPhongs', 'hoaDon')->whereIn('id', $ids)->get();

            foreach ($datPhongs as $datPhong) {
                $chiTiet = $datPhong->chiTietDatPhongs->first();
                if ($chiTiet && $chiTiet->phong_id) {
                    $phong = Phong::find($chiTiet->phong_id);
                    if ($phong && $phong->tinh_trang === 'booked') {
                        $phong->update(['tinh_trang' => 'available']);
                    }
                }
                $datPhong->hoaDon()->delete();
                $datPhong->chiTietDatPhongs()->delete();
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

    /**
     * Hiển thị trang chi tiết một phòng: liệt kê tất cả các đơn đặt phòng liên quan
     */
    public function getRoomDetail($phong_id)
    {
        $phong = Phong::findOrFail($phong_id);

        // Lấy tất cả các DatPhong liên quan đến phòng này thông qua chiTietDatPhongs
        $datPhongs = DatPhong::whereHas('chiTietDatPhongs', function($q) use ($phong_id) {
            $q->where('phong_id', $phong_id);
        })->with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
          ->orderBy('created_at', 'desc')
          ->paginate(10);

        return view('admin.dat_phong.room_detail', compact('phong', 'datPhongs'));
    }
}