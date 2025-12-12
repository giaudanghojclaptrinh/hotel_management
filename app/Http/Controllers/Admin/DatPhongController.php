<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatPhong;
use App\Models\User;
use App\Models\Phong;
use App\Models\HoaDon;
use App\Models\ChiTietDatPhong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Cần thiết cho logging lỗi
use App\Notifications\BookingStatusUpdated; // [QUAN TRỌNG] Import Notification Class

class DatPhongController extends Controller
{
    /**
     * Hiển thị danh sách các đơn đặt phòng (Sử dụng Eager Loading).
     */
    public function getDanhSach()
    {
        $datPhongs = DatPhong::with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
                             ->orderBy('created_at', 'desc')
                             ->get();
        
        return view('admin.dat_phong.danh_sach', compact('datPhongs'));
    }

    // Các hàm getThem, postThem giữ nguyên...
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

    // --- HÀM XỬ LÝ HÓA ĐƠN & THANH TOÁN ---
    
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

            $datPhong = DatPhong::with('user', 'chiTietDatPhongs.loaiPhong')->findOrFail($dat_phong_id);
            $datPhong->update([
                'payment_status' => $request->trang_thai,
                'payment_method' => $request->phuong_thuc_thanh_toan,
            ]);
            
            // Gửi thông báo nếu trạng thái thanh toán là PAID
            if ($request->trang_thai === 'paid' && $datPhong->user) {
                $roomName = $datPhong->chiTietDatPhongs->first()->loaiPhong->ten_loai_phong ?? 'Phòng đặt';
                $message = "Đơn #{$datPhong->id} ({$roomName}) đã được xác nhận thanh toán thành công ({$request->phuong_thuc_thanh_toan}).";
                
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, 'Thanh toán thành công', $message));
            }

            DB::commit();
            return back()->with('success', 'Cập nhật trạng thái thanh toán thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi cập nhật thanh toán đơn {$dat_phong_id}: " . $e->getMessage());
            return back()->with('error', 'Lỗi khi cập nhật thanh toán: ' . $e->getMessage());
        }
    }


    // --- HÀM XÓA ĐƠN ĐẶT PHÒNG ĐƠN LẺ ---
    public function getXoa($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs', 'hoaDon')->findOrFail($id);
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            
            // 1. Nhả phòng (nếu phòng đang bị khóa)
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong && $phong->tinh_trang === 'booked') {
                    $phong->update(['tinh_trang' => 'available']);
                }
            }
            
            // 2. Xóa bản ghi con trước: HoaDon và ChiTietDatPhong
            $datPhong->hoaDon()->delete(); 
            $datPhong->chiTietDatPhongs()->delete();

            // 3. Xóa đơn đặt phòng cha
            $datPhong->delete();

            DB::commit();
            return redirect()->route('admin.dat-phong')->with('success', 'Đã xóa vĩnh viễn đơn đặt phòng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi xóa đơn {$id}: " . $e->getMessage());
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
            $datPhongs = DatPhong::with('chiTietDatPhongs', 'hoaDon')->whereIn('id', $ids)->get();

            foreach ($datPhongs as $datPhong) {
                // 1. NHẢ PHÒNG (NẾU ĐÃ KHÓA)
                $chiTiet = $datPhong->chiTietDatPhongs->first();
                if ($chiTiet && $chiTiet->phong_id) {
                    $phong = Phong::find($chiTiet->phong_id);
                    if ($phong && $phong->tinh_trang === 'booked') {
                        $phong->update(['tinh_trang' => 'available']);
                    }
                }

                // 2. XÓA BẢN GHI CON TRƯỚC
                $datPhong->hoaDon()->delete();
                $datPhong->chiTietDatPhongs()->delete();

                // 3. XÓA BẢN GHI CHA
                $datPhong->delete();
                $deletedCount++;
            }

            DB::commit();
            return redirect()->route('admin.dat-phong')
                             ->with('success', "Đã xóa thành công $deletedCount đơn đặt phòng và mở lại các phòng tương ứng!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi xóa hàng loạt: " . $e->getMessage());
            return back()->with('error', 'Lỗi hệ thống khi xóa hàng loạt: ' . $e->getMessage());
        }
    }
    
    // --- LOGIC DUYỆT ĐƠN VÀ KHÓA PHÒNG (Gửi thông báo) ---
    public function duyetDon($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs.loaiPhong', 'user')->findOrFail($id);
            
            if ($datPhong->trang_thai !== 'pending') {
                return back()->with('error', 'Đơn này đã được xử lý rồi!');
            }
            
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            if (!$chiTiet || !$chiTiet->phong_id) {
                return back()->with('error', 'Lỗi: Đơn này không có chi tiết phòng hoặc phòng vật lý.');
            }
            
            $phong = Phong::find($chiTiet->phong_id);
            
            // Cần kiểm tra trạng thái phòng khả dụng tại thời điểm duyệt
            // (Mặc dù check lịch được thực hiện khi đặt, nhưng cần khóa phòng vật lý)
            if (!$phong || $phong->tinh_trang !== 'available') {
                 // Nếu phòng không có trạng thái 'available' (ví dụ: maintenance hoặc đã booked thủ công)
                DB::rollBack();
                return back()->with('error', 'Lỗi: Phòng vật lý (' . ($phong->so_phong ?? 'N/A') . ') hiện không trống hoặc đang bảo trì. Vui lòng gán lại phòng!');
            }

            // 1. Khóa phòng
            $phong->update(['tinh_trang' => 'booked']);

            // 2. Cập nhật trạng thái đơn
            $datPhong->update([
                'trang_thai' => 'confirmed',
                // Nếu thanh toán online, chuyển sang awaiting_payment để khách hoàn tất
                'payment_status' => ($datPhong->payment_method === 'online') ? 'awaiting_payment' : $datPhong->payment_status, 
            ]);

            // 3. Gửi thông báo cho User
            if ($datPhong->user) {
                $status = 'Đã xác nhận';
                $roomName = $chiTiet->loaiPhong->ten_loai_phong ?? 'Phòng đặt';
                $message = "Đơn #{$datPhong->id} ({$roomName}) của bạn đã được xác nhận thành công. Phòng số: {$phong->so_phong}.";
                
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, $status, $message));
            }

            DB::commit();
            return back()->with('success', 'Đã duyệt đơn và khóa phòng (' . $phong->so_phong . ') thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi duyệt đơn {$id}: " . $e->getMessage());
            return back()->with('error', 'Lỗi hệ thống khi duyệt đơn: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý Hủy Đơn / Trả phòng (Cancel/Complete) - Nhả phòng (Gửi thông báo).
     */
    public function huyDon($id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs.loaiPhong', 'user')->findOrFail($id);
            $chiTiet = $datPhong->chiTietDatPhongs->first();
            $phong = ($chiTiet && $chiTiet->phong_id) ? Phong::find($chiTiet->phong_id) : null;
            
            $isCancellation = $datPhong->trang_thai !== 'confirmed'; // Nếu không phải confirmed thì là hủy đơn (pending -> cancelled)
            
            // 1. KIỂM TRA ĐIỀU KIỆN (Chặn trả phòng nếu chưa thanh toán)
            if ($datPhong->trang_thai === 'confirmed' && $datPhong->payment_status !== 'paid') {
                DB::rollBack();
                return back()->with('error', 'Lỗi: Khách hàng chưa thanh toán! Vui lòng xác nhận thanh toán trước khi trả phòng.');
            }

            // 2. CẬP NHẬT TRẠNG THÁI ĐƠN
            $newStatus = ($datPhong->trang_thai === 'confirmed') ? 'completed' : 'cancelled';
            $datPhong->update(['trang_thai' => $newStatus]);
            
            $msgAction = ($newStatus === 'completed') ? 'Trả phòng' : 'Hủy đơn';

            // 3. NHẢ PHÒNG
            if ($phong && $phong->tinh_trang === 'booked') {
                 $phong->update(['tinh_trang' => 'available']);
            }

            // 4. GỬI THÔNG BÁO cho User
            if ($datPhong->user) {
                $status = ($newStatus === 'completed') ? 'Hoàn thành' : 'Đã hủy';
                $roomName = $chiTiet->loaiPhong->ten_loai_phong ?? 'Phòng đặt';
                
                if ($newStatus === 'completed') {
                    $message = "Đơn #{$datPhong->id} ({$roomName}) đã được xử lý TRẢ PHÒNG và hoàn thành.";
                } else {
                    $message = "Đơn #{$datPhong->id} ({$roomName}) của bạn đã bị HỦY.";
                }
                
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, $status, $message));
            }

            DB::commit();
            return back()->with('success', "Đã xử lý {$msgAction} đơn hàng thành công và mở lại phòng!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi hủy/trả phòng {$id}: " . $e->getMessage());
            return back()->with('error', 'Lỗi hệ thống khi hủy/trả phòng: ' . $e->getMessage());
        }
    }
}