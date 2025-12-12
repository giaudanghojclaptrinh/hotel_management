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
    // 1. HÀM HIỂN THỊ & API
    // ===============================================

    public function create(Request $request)
    {
        $loaiPhongId = $request->room_id;
        $checkIn = $request->checkin;
        $checkOut = $request->checkout;

        if (!$loaiPhongId || !$checkIn || !$checkOut) {
            return redirect()->route('phong.danh-sach')
                ->with('error', 'Vui lòng chọn ngày và loại phòng trước!');
        }

        // Kiểm tra phòng trống
        $phongTrong = $this->findAvailableRoom($loaiPhongId, $checkIn, $checkOut);
        
        if (!$phongTrong) {
            return redirect()->back()
                ->with('error', 'Rất tiếc, hạng phòng này đã HẾT CHỖ trong khoảng thời gian bạn chọn.')
                ->withInput();
        }

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
        $finalTotal = $originalTotal - $discountAmount;

        return response()->json([
            'success' => true,
            'discount_amount' => round($discountAmount),
            'final_total' => round($finalTotal),
            'message' => 'Áp dụng mã thành công!'
        ], 200);
    }

    public function success() {
        $bookingId = session('booking_id');
        $booking = null;

        if ($bookingId) {
            $booking = DatPhong::find($bookingId);
        }

        return view('client.booking.success', compact('booking'));
    }

    // ===============================================
    // 2. LOGIC TÌM PHÒNG (CORE)
    // ===============================================

    private function findAvailableRoom($loaiPhongId, $checkIn, $checkOut)
    {
        $bookedRoomIds = ChiTietDatPhong::whereHas('datPhong', function ($query) use ($checkIn, $checkOut) {
            $query->whereIn('trang_thai', ['pending', 'confirmed', 'paid', 'awaiting_payment'])
                  ->where(function ($q) use ($checkIn, $checkOut) {
                      $q->where('ngay_den', '<', $checkOut)
                        ->where('ngay_di', '>', $checkIn);
                  });
        })->where('loai_phong_id', $loaiPhongId) 
          ->pluck('phong_id')
          ->toArray();

        $phongTrong = Phong::where('loai_phong_id', $loaiPhongId)
                           ->where('tinh_trang', '!=', 'maintenance') 
                           ->whereNotIn('id', $bookedRoomIds) 
                           ->first();
        
        return $phongTrong;
    }
    
    // ===============================================
    // 3. XỬ LÝ ĐẶT PHÒNG
    // ===============================================

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
            $phongTrong = $this->findAvailableRoom($request->room_id, $request->checkin, $request->checkout);

            if (!$phongTrong) {
                DB::rollBack();
                return back()->with('error', 'Rất tiếc, phòng vừa bị người khác đặt mất.');
            }

            $this->createBooking($request, 'pending', 'unpaid', $phongTrong);
            
            DB::commit();
            
            return redirect()->route('booking.success')
                ->with('success', 'Đặt phòng thành công! Đơn hàng đang chờ Admin xác nhận.')
                ->with('booking_id', session('temp_booking_id'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
    
    public function postVnPayStore(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:loai_phongs,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date',
            'vnp_BankCode' => 'required|string', 
        ]);

        DB::beginTransaction();
        try {
            $phongTrong = $this->findAvailableRoom($request->room_id, $request->checkin, $request->checkout);

            if (!$phongTrong) {
                DB::rollBack();
                return back()->with('error', 'Rất tiếc, phòng vừa bị người khác đặt mất.');
            }

            $booking = $this->createBooking($request, 'confirmed', 'paid', $phongTrong);
            
            HoaDon::create([
                'dat_phong_id' => $booking->id,
                'ma_hoa_don' => 'HD' . time() . rand(1000, 9999),
                'ngay_lap' => now(),
                'tong_tien' => $booking->tong_tien,
                'phuong_thuc_thanh_toan' => 'online', 
                'trang_thai' => 'paid', 
            ]);

            DB::commit();

            return redirect()->route('bookings.invoice', $booking->id)
                ->with('success', 'Thanh toán Online thành công! Đơn phòng của bạn đã được xác nhận tự động.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

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
            'ghi_chu' => $request->ghi_chu ?? ($request->vnp_OrderInfo ?? null),
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

    public function paymentCallback(Request $request) { return redirect()->route('trang_chu'); }

    // ===============================================
    // [HÀM LỊCH SỬ & HÓA ĐƠN]
    // ===============================================
    
    public function history()
    {
        $bookings = DatPhong::where('user_id', Auth::id())
            ->with(['chiTietDatPhongs.loaiPhong', 'hoaDon']) 
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.booking.history', compact('bookings'));
    }

    public function invoice($id)
    {
        $booking = DatPhong::where('user_id', Auth::id())
            ->with(['chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong', 'hoaDon', 'user'])
            ->findOrFail($id);

        // [FIXED] Sửa biến compact từ 'bookings' thành 'booking'
        return view('client.booking.invoice', compact('booking'));
    }
}