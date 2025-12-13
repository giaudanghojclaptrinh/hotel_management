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
        // Lấy các phòng có trạng thái available để chọn
        $phongs = Phong::with('loaiPhong')->where('tinh_trang', 'available')->get();
        return view('admin.dat_phong.them', compact('users', 'phongs'));
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'phong_id' => 'required|exists:phongs,id',
            'ngay_den' => 'required|date',
            'ngay_di' => 'required|date|after_or_equal:ngay_den',
            'payment_method' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create with temporary tong_tien=0 to satisfy non-null DB column, we'll update later
            $datPhong = DatPhong::create([
                'user_id' => $data['user_id'],
                'ngay_den' => $data['ngay_den'],
                'ngay_di' => $data['ngay_di'],
                'trang_thai' => 'pending',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'ghi_chu' => $data['note'] ?? null,
                'tong_tien' => 0,
            ]);

            // Tạo chi tiết đặt phòng cho phòng đã chọn
            $phong = Phong::with('loaiPhong')->findOrFail($data['phong_id']);
            $donGia = $phong->loaiPhong->gia ?? 0;

            // Tính số đêm
            $start = \Carbon\Carbon::parse($data['ngay_den']);
            $end = \Carbon\Carbon::parse($data['ngay_di']);
            $nights = max(1, $end->diffInDays($start));

            $thanhTien = $donGia * $nights;

            $chiTiet = $datPhong->chiTietDatPhongs()->create([
                'loai_phong_id' => $phong->loai_phong_id,
                'phong_id' => $phong->id,
                'so_luong' => 1,
                'don_gia' => $donGia,
                'thanh_tien' => $thanhTien,
            ]);

            // Cập nhật tổng tiền cho DatPhong
            $datPhong->update(['tong_tien' => $thanhTien]);

            // Tạo hóa đơn (mặc định unpaid)
            HoaDon::create([
                'dat_phong_id' => $datPhong->id,
                'ma_hoa_don' => 'HD' . time() . rand(100,999),
                'ngay_lap' => now(),
                'tong_tien' => $thanhTien,
                'trang_thai' => 'unpaid',
                'phuong_thuc_thanh_toan' => $datPhong->payment_method,
            ]);

            // Đánh dấu phòng là booked
            $phong->update(['tinh_trang' => 'booked']);

            DB::commit();

            return redirect()->route('admin.dat-phong')->with('success', 'Thêm đơn đặt phòng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo đơn admin: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Lỗi khi tạo đơn: ' . $e->getMessage());
        }
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
            
            // Sau khi duyệt — trở về trang hiện tại (không bắt buộc điều hướng sang Lịch sử)
            return redirect()->back()->with('success', 'Đã duyệt đơn thành công! Đơn hàng đã được cập nhật.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * AJAX/POST endpoint to approve a booking (preferred for modal/AJAX actions)
     */
    public function postDuyet(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs.loaiPhong', 'user')->findOrFail($id);

            if ($datPhong->trang_thai !== 'pending') {
                if ($request->ajax()) return response()->json(['status' => 'error', 'message' => 'Đơn đã được xử lý'], 422);
                return back()->with('error', 'Đơn này đã được xử lý rồi!');
            }

            $chiTiet = $datPhong->chiTietDatPhongs->first();
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong) $phong->update(['tinh_trang' => 'booked']);
            }

            $datPhong->update([
                'trang_thai' => 'confirmed',
                'payment_status' => ($datPhong->payment_method === 'online') ? 'awaiting_payment' : $datPhong->payment_status,
            ]);

            if ($datPhong->user) {
                $roomName = $chiTiet->loaiPhong->ten_loai_phong ?? 'Phòng';
                $message = "Đơn #{$datPhong->id} ({$roomName}) đã được xác nhận.";
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, 'Đã xác nhận', $message));
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Đã duyệt đơn thành công']);
            }

            // Nếu không phải AJAX, quay về trang trước (ví dụ: chi tiết phòng)
            return redirect()->back()->with('success', 'Đã duyệt đơn thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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

    /**
     * AJAX/POST endpoint to cancel a booking (preferred for modal/AJAX actions)
     */
    public function postHuy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $datPhong = DatPhong::with('chiTietDatPhongs', 'user')->findOrFail($id);

            $chiTiet = $datPhong->chiTietDatPhongs->first();
            if ($chiTiet && $chiTiet->phong_id) {
                $phong = Phong::find($chiTiet->phong_id);
                if ($phong && $phong->tinh_trang === 'booked') {
                    $phong->update(['tinh_trang' => 'available']);
                }
            }

            $datPhong->update(['trang_thai' => 'cancelled']);

            if ($datPhong->user) {
                $datPhong->user->notify(new BookingStatusUpdated($datPhong, 'Đã hủy', "Đơn #{$datPhong->id} đã bị hủy."));
            }

            DB::commit();

            if ($request->ajax()) return response()->json(['status' => 'success', 'message' => 'Đã hủy đơn thành công']);

            return redirect()->route('admin.dat-phong.trash')->with('success', 'Đã từ chối đơn hàng! Đơn đã được chuyển vào thùng rác.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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
     * Move multiple bookings from history to trash (bulk)
     */
    public function bulkMoveToTrash(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:dat_phongs,id',
        ]);

        $ids = $request->ids;
        DB::beginTransaction();
        try {
            $datPhongs = DatPhong::with('chiTietDatPhongs')->whereIn('id', $ids)->get();
            foreach ($datPhongs as $dat) {
                // release room if booked
                $chiTiet = $dat->chiTietDatPhongs->first();
                if ($chiTiet && $chiTiet->phong_id) {
                    $phong = Phong::find($chiTiet->phong_id);
                    if ($phong && $phong->tinh_trang === 'booked') {
                        $phong->update(['tinh_trang' => 'available']);
                    }
                }
                $dat->update(['trang_thai' => 'cancelled']);
            }
            DB::commit();
            return redirect()->route('admin.dat-phong.history')->with('success', 'Đã chuyển các đơn được chọn vào thùng rác.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi chuyển: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete multiple bookings from trash (bulk)
     */
    public function bulkDeletePermanent(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:dat_phongs,id',
        ]);

        $ids = $request->ids;
        DB::beginTransaction();
        try {
            $datPhongs = DatPhong::with('chiTietDatPhongs', 'hoaDon')->whereIn('id', $ids)->get();
            foreach ($datPhongs as $dat) {
                $chiTiet = $dat->chiTietDatPhongs->first();
                if ($chiTiet && $chiTiet->phong_id) {
                    $phong = Phong::find($chiTiet->phong_id);
                    if ($phong && $phong->tinh_trang === 'booked') {
                        $phong->update(['tinh_trang' => 'available']);
                    }
                }
                $dat->hoaDon()->delete();
                $dat->chiTietDatPhongs()->delete();
                $dat->delete();
            }
            DB::commit();
            return redirect()->route('admin.dat-phong.trash')->with('success', 'Đã xóa vĩnh viễn các đơn được chọn.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị trang chi tiết một phòng: liệt kê tất cả các đơn đặt phòng liên quan
     */
    public function getRoomDetail(Request $request, $phong_id)
    {
        $phong = Phong::findOrFail($phong_id);

        // Build query with filters: q (user name/email or booking id), status, date range
        $query = DatPhong::whereHas('chiTietDatPhongs', function($q) use ($phong_id) {
            $q->where('phong_id', $phong_id);
        });

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhereHas('user', function($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->get('status'));
        }

        if ($request->filled('from_date')) {
            $from = $request->get('from_date');
            $query->whereDate('created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $to = $request->get('to_date');
            $query->whereDate('created_at', '<=', $to);
        }

        $datPhongs = $query->with(['user', 'chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        return view('admin.dat_phong.room_detail', compact('phong', 'datPhongs'));
    }

    /**
     * Báo cáo doanh thu đơn giản: lọc theo khoảng ngày và phòng
     */
    public function revenueReport(Request $request)
    {
        $from = $request->get('from_date');
        $to = $request->get('to_date');
        $room_id = $request->get('room_id');

        $query = DatPhong::query();

        // Chỉ tính các đơn đã được thanh toán/hoàn thành
        $query->where('payment_status', 'paid');

        if ($from) $query->whereDate('updated_at', '>=', $from);
        if ($to) $query->whereDate('updated_at', '<=', $to);

        if ($room_id) {
            $query->whereHas('chiTietDatPhongs', function($q) use ($room_id) {
                $q->where('phong_id', $room_id);
            });
        }

        $totalRevenue = (float) $query->sum('tong_tien');

        $bookings = $query->with(['user', 'chiTietDatPhongs.phong'])->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        $rooms = Phong::orderBy('so_phong')->get();

        return view('admin.reports.revenue', compact('bookings', 'totalRevenue', 'rooms'));
    }
}