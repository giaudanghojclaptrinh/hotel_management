<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoaiPhong;
use App\Models\DatPhong;
use App\Models\ChiTietDatPhong;
use App\Models\Phong;
use App\Models\KhuyenMai;
use App\Models\HoaDon; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Log; 

class BookingController extends Controller
{
    // ===============================================
    // HÀM HIỂN THỊ & API
    // ===============================================

    /**
     * Bước 1: Hiển thị form xác nhận đặt phòng
     * [FIXED] Thêm kiểm tra phòng trống ngay tại đây để chặn nếu đã hết phòng.
     */
    public function create(Request $request)
    {
        $loaiPhongId = $request->room_id;
        $checkIn = $request->checkin;
        $checkOut = $request->checkout;

        if (!$loaiPhongId || !$checkIn || !$checkOut) {
            return redirect()->route('phong.danh-sach')
                ->with('error', 'Vui lòng chọn ngày và loại phòng trước!');
        }

        // --- [LOGIC MỚI] CHECK TRÙNG LỊCH NGAY LẬP TỨC ---
        // Nếu không có phòng nào thỏa mãn điều kiện ngày tháng -> Quay về báo lỗi ngay
        $phongTrong = $this->findAvailableRoom($loaiPhongId, $checkIn, $checkOut);

        if (!$phongTrong) {
            return redirect()->back()
                ->with('error', 'Rất tiếc, loại phòng này đã HẾT PHÒNG hoặc CÓ NGƯỜI ĐẶT trong khoảng thời gian bạn chọn. Vui lòng chọn ngày khác!')
                ->withInput(); // Giữ lại ngày khách đã chọn để họ biết
        }
        // -------------------------------------------------

        $roomType = LoaiPhong::findOrFail($loaiPhongId);
        
        $start = Carbon::parse($checkIn);
        $end = Carbon::parse($checkOut);
        $days = $start->diffInDays($end) ?: 1;
        
        $totalPrice = $roomType->gia * $days;

        return view('client.booking.create', compact('roomType', 'checkIn', 'checkOut', 'days', 'totalPrice'));
    }

    public function checkPromotion(Request $request)
    {
        $request->validate(['code' => 'required|string', 'original_total' => 'required|numeric']);
        $code = strtoupper($request->code);
        $originalTotal = $request->original_total;
        
        $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $code)
            ->whereDate('ngay_bat_dau', '<=', Carbon::now())
            ->whereDate('ngay_ket_thuc', '>=', Carbon::now())
            ->first();

        if (!$khuyenMai) {
            return response()->json(['success' => false, 'discount_amount' => 0, 'final_total' => $originalTotal, 'message' => 'Mã không hợp lệ.'], 200);
        }

        $discountAmount = ($khuyenMai->chiet_khau_phan_tram > 0) 
            ? $originalTotal * ($khuyenMai->chiet_khau_phan_tram / 100)
            : $khuyenMai->so_tien_giam_gia;

        if ($discountAmount > $originalTotal) $discountAmount = $originalTotal;

        return response()->json([
            'success' => true,
            'discount_amount' => round($discountAmount),
            'final_total' => round($originalTotal - $discountAmount),
            'message' => 'Áp dụng mã thành công!'
        ], 200);
    }

    public function success() {
        return view('client.booking.success');
    }

    // ================================================================
    // LOGIC TÌM PHÒNG TRỐNG THEO NGÀY
    // ================================================================

    /**
     * Tìm 1 phòng vật lý thuộc Loại Phòng, KHÔNG BỊ TRÙNG LỊCH trong khoảng thời gian khách chọn.
     */
    private function findAvailableRoom($loaiPhongId, $checkIn, $checkOut)
    {
        // 1. Lấy danh sách ID các phòng ĐANG BẬN trong khoảng thời gian này
        // Logic trùng lịch: (NgayDen_Moi < NgayDi_Cu) AND (NgayDi_Moi > NgayDen_Cu)
        $bookedRoomIds = ChiTietDatPhong::whereHas('datPhong', function ($query) use ($checkIn, $checkOut) {
            // Chỉ xét các đơn đang hoạt động (kể cả pending chưa duyệt)
            $query->whereIn('trang_thai', ['pending', 'confirmed', 'awaiting_payment'])
                  ->where(function ($q) use ($checkIn, $checkOut) {
                      $q->where('ngay_den', '<', $checkOut)
                        ->where('ngay_di', '>', $checkIn);
                  });
        })->where('loai_phong_id', $loaiPhongId) 
          ->pluck('phong_id')
          ->toArray();

        // 2. Tìm phòng thuộc loại này, KHÔNG nằm trong danh sách bận, và không bảo trì
        $phongTrong = Phong::where('loai_phong_id', $loaiPhongId)
                           ->where('tinh_trang', '!=', 'maintenance') 
                           ->whereNotIn('id', $bookedRoomIds) 
                           ->first();
        
        return $phongTrong;
    }
    
    // ===============================================
    // XỬ LÝ LƯU ĐƠN HÀNG (STORE LOGIC)
    // ===============================================

    /**
     * Case 1: Thanh toán tại khách sạn
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:loai_phongs,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'payment_method' => 'required|in:pay_at_hotel',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Check lại lần nữa khi submit cho chắc chắn
            $phongTrong = $this->findAvailableRoom($request->room_id, $request->checkin, $request->checkout);

            if (!$phongTrong) {
                DB::rollBack();
                return back()->with('error', 'Rất tiếc, phòng vừa bị người khác đặt mất trong lúc bạn đang thao tác.');
            }

            $this->createBooking($request, 'pending', 'unpaid', $phongTrong);
            
            DB::commit();
            return redirect()->route('booking.success')
                ->with('success', 'Đặt phòng thành công! Vui lòng chờ Admin xác nhận.')
                ->with('booking_id', session('temp_booking_id'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
    
    /**
     * Case 2: Thanh toán Online VNPay (Demo)
     */
    public function postVnPayStore(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:loai_phongs,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'vnp_BankCode' => 'required|string', 
        ]);

        DB::beginTransaction();
        try {
            $phongTrong = $this->findAvailableRoom($request->room_id, $request->checkin, $request->checkout);

            if (!$phongTrong) {
                DB::rollBack();
                return back()->with('error', 'Rất tiếc, phòng vừa bị người khác đặt mất trong lúc bạn đang thao tác.');
            }

            // Tạo đơn confirmed & paid
            $booking = $this->createBooking($request, 'confirmed', 'paid', $phongTrong);
            
            // Tạo hóa đơn
            HoaDon::create([
                'dat_phong_id' => $booking->id,
                'ma_hoa_don' => 'HD' . time() . rand(1000, 9999),
                'ngay_lap' => now(),
                'tong_tien' => $booking->tong_tien,
                'phuong_thuc_thanh_toan' => 'online', 
                'trang_thai' => 'paid', 
            ]);

            DB::commit();

            return redirect()->route('booking.success')
                ->with('success', 'Thanh toán Online thành công! Đơn phòng của bạn đã được xác nhận tự động.')
                ->with('booking_id', $booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    // --- Helper function ---
    private function createBooking($request, $status, $paymentStatus, $phongTrong)
    {
        $loaiPhong = LoaiPhong::find($request->room_id);
        $days = Carbon::parse($request->checkin)->diffInDays(Carbon::parse($request->checkout)) ?: 1;
        $originalTotal = $loaiPhong->gia * $days;
        $discountAmount = $request->discount_amount ?? 0;
        $finalTotal = $originalTotal - $discountAmount;

        $booking = DatPhong::create([
            'user_id' => Auth::id(),
            'ngay_den' => $request->checkin,
            'ngay_di' => $request->checkout,
            'tong_tien' => $finalTotal,
            'trang_thai' => $status, 
            'payment_status' => $paymentStatus, 
            'payment_method' => $request->payment_method,
            'promotion_code' => $request->promotion_code,
            'discount_amount' => $discountAmount,
            'ghi_chu' => $request->ghi_chu,
        ]);

        ChiTietDatPhong::create([
            'dat_phong_id' => $booking->id,
            'loai_phong_id' => $loaiPhong->id,
            'phong_id' => $phongTrong->id,
            'so_luong' => 1,
            'don_gia' => $loaiPhong->gia,
            'thanh_tien' => $originalTotal,
        ]);
        
        session()->flash('temp_booking_id', $booking->id);
        return $booking;
    }
}