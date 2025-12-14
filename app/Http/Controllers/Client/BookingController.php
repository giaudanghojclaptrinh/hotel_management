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
        
        // Tính VAT 8% cho preview
        $vatAmount = $totalPrice * 0.08;
        $totalWithVat = $totalPrice + $vatAmount;

        return view('client.booking.create', compact('roomType', 'checkIn', 'checkOut', 'days', 'totalPrice', 'vatAmount', 'totalWithVat'));
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

        // ✅ CHECK 1: Kiểm tra tổng số lần sử dụng (toàn hệ thống)
        if ($khuyenMai->usage_limit !== null && $khuyenMai->used_count >= $khuyenMai->usage_limit) {
            return response()->json(['success' => false, 'discount_amount' => 0, 'final_total' => $originalTotal, 'message' => 'Mã đã hết lượt sử dụng.'], 200);
        }

        // ✅ CHECK 2: Kiểm tra số lần user này đã dùng
        $userUsage = \App\Models\KhuyenMaiUsage::where('user_id', Auth::id())
            ->where('khuyen_mai_id', $khuyenMai->id)
            ->first();

        if ($userUsage && $userUsage->used_count >= $khuyenMai->usage_per_user) {
            return response()->json(['success' => false, 'discount_amount' => 0, 'final_total' => $originalTotal, 'message' => 'Bạn đã hết lượt sử dụng mã này.'], 200);
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
        // 1. Tìm ID các phòng vật lý đang bận (đã được đặt)
        $bookedRoomIds = ChiTietDatPhong::whereHas('datPhong', function ($query) use ($checkIn, $checkOut) {
            // Chỉ coi là bận nếu đơn đang trong trạng thái active (Pending, Confirmed, Paid, Awaiting)
            $query->whereIn('trang_thai', ['pending', 'confirmed', 'paid', 'awaiting_payment'])
                  ->where(function ($q) use ($checkIn, $checkOut) {
                      // Điều kiện trùng lịch: (Ngày Đến Cũ < Ngày Đi Mới) AND (Ngày Đi Cũ > Ngày Đến Mới)
                      $q->where('ngay_den', '<', $checkOut)
                        ->where('ngay_di', '>', $checkIn);
                  });
        })->where('loai_phong_id', $loaiPhongId) 
          ->pluck('phong_id')
          ->toArray(); // Mảng ID các phòng BẬN

        // 2. Tìm một phòng vật lý thuộc loại phòng này đang trống
        $phongTrong = Phong::where('loai_phong_id', $loaiPhongId)
                           ->where('tinh_trang', '!=', 'maintenance') // Loại trừ phòng bảo trì
                           ->whereNotIn('id', $bookedRoomIds) // Loại trừ phòng đang bận
                           ->first(); // Chỉ cần tìm MỘT phòng
        
        return $phongTrong;
    }
    
    // ===============================================
    // 3. XỬ LÝ ĐẶT PHÒNG TẠI KHÁCH SẠN (PAY AT HOTEL - PENDING)
    // ===============================================

    public function store(Request $request)
    {
        // Validation cơ bản (Đã bỏ payment_method vì nó được set là 'pay_at_hotel' ở client)
        $request->validate([
            'room_id' => 'required|exists:loai_phongs,id',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'payment_method' => 'required|in:pay_at_hotel',
            'ghi_chu' => 'nullable|string',
            'accepted_terms' => 'required|accepted'
        ], [
            'checkin.after_or_equal' => 'Ngày nhận phòng phải từ hôm nay trở đi.',
            'checkout.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'accepted_terms.accepted' => 'Bạn phải đồng ý với điều khoản để tiếp tục.',
        ]);

        DB::beginTransaction();
        try {
            // Bước 1: Tìm phòng trống
            $phongTrong = $this->findAvailableRoom($request->room_id, $request->checkin, $request->checkout);

            if (!$phongTrong) {
                DB::rollBack();
                return back()->with('error', 'Rất tiếc, không còn phòng trống.');
            }

            // 🔒 Bước 2: LOCK phòng này để tránh race condition
            $phongLocked = Phong::where('id', $phongTrong->id)
                ->lockForUpdate() // Chặn các user khác truy cập phòng này
                ->first();

            if (!$phongLocked) {
                DB::rollBack();
                return back()->with('error', 'Không thể khóa phòng. Vui lòng thử lại.');
            }

            // 🔍 Bước 3: RE-CHECK phòng vẫn còn trống sau khi lock
            $isBooked = ChiTietDatPhong::where('phong_id', $phongLocked->id)
                ->whereHas('datPhong', function($q) use ($request) {
                    $q->whereIn('trang_thai', ['pending', 'confirmed', 'paid', 'awaiting_payment'])
                      ->where('ngay_den', '<', $request->checkout)
                      ->where('ngay_di', '>', $request->checkin);
                })
                ->exists();

            if ($isBooked) {
                DB::rollBack();
                return back()->with('error', 'Phòng vừa bị người khác đặt. Vui lòng chọn phòng khác.');
            }

            // ✅ Tạo Booking: PENDING (Chờ duyệt), UNPAID (Chưa thanh toán)
            $booking = $this->createBooking($request, 'pending', 'unpaid', $phongLocked);
            
            DB::commit();
            
            // Chuyển hướng đến trang thành công
            return redirect()->route('booking.success')
                ->with('success', 'Đặt phòng thành công! Đơn hàng đang chờ Admin xác nhận.')
                ->with('booking_id', $booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo đơn (Store): ' . $e->getMessage());
            return back()->with('error', 'Lỗi hệ thống khi tạo đơn: ' . $e->getMessage());
        }
    }
    
    // ===============================================
    // 4. XỬ LÝ THANH TOÁN ONLINE (VNPAY) - KHÔNG TỰ ĐỘNG DUYỆT
    // Sau khi thanh toán online, hệ thống chỉ ghi nhận trạng thái thanh toán
    // nhưng vẫn giữ `trang_thai` là 'pending' để Admin duyệt và xác nhận phòng.
    // ===============================================
    
    public function postVnPayStore(Request $request)
    {
        // Validation cần thiết cho cả booking data và payment data
        $request->validate([
            'room_id' => 'required|exists:loai_phongs,id',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'payment_method' => 'required|in:online',
            'vnp_BankCode' => 'required|string',
            'accepted_terms' => 'required|accepted',
            // Các trường khác như promotion_code, discount_amount tự động được xử lý
        ], [
            'checkin.after_or_equal' => 'Ngày nhận phòng phải từ hôm nay trở đi.',
            'checkout.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'accepted_terms.accepted' => 'Bạn phải đồng ý với điều khoản để tiếp tục.',
        ]);

        DB::beginTransaction();
        try {
            // Bước 1: Tìm phòng trống
            $phongTrong = $this->findAvailableRoom($request->room_id, $request->checkin, $request->checkout);

            if (!$phongTrong) {
                DB::rollBack();
                return back()->with('error', 'Rất tiếc, không còn phòng trống.');
            }

            // 🔒 Bước 2: LOCK phòng để tránh race condition
            $phongLocked = Phong::where('id', $phongTrong->id)
                ->lockForUpdate()
                ->first();

            if (!$phongLocked) {
                DB::rollBack();
                return back()->with('error', 'Không thể khóa phòng. Vui lòng thử lại.');
            }

            // 🔍 Bước 3: RE-CHECK phòng sau khi lock
            $isBooked = ChiTietDatPhong::where('phong_id', $phongLocked->id)
                ->whereHas('datPhong', function($q) use ($request) {
                    $q->whereIn('trang_thai', ['pending', 'confirmed', 'paid', 'awaiting_payment'])
                      ->where('ngay_den', '<', $request->checkout)
                      ->where('ngay_di', '>', $request->checkin);
                })
                ->exists();

            if ($isBooked) {
                DB::rollBack();
                return back()->with('error', 'Phòng vừa bị người khác đặt. Vui lòng chọn phòng khác.');
            }

            // ✅ Tạo Booking: PENDING (chờ Admin duyệt) nhưng payment_status = PAID
            $booking = $this->createBooking($request, 'pending', 'paid', $phongLocked);
            
            // Tạo Hóa đơn ngay lập tức cho thanh toán online
            HoaDon::create([
                'dat_phong_id' => $booking->id,
                'ma_hoa_don' => 'HD' . time() . rand(1000, 9999),
                'ngay_lap' => now(),
                'subtotal' => $booking->subtotal,
                'vat_amount' => $booking->vat_amount,
                'tong_tien' => $booking->tong_tien,
                'phuong_thuc_thanh_toan' => $request->payment_method, // 'online'
                'trang_thai' => 'paid', 
            ]);

            DB::commit();

            // Redirect to success page (do not auto-open invoice). Pass booking_id for the success view.
            return redirect()->route('booking.success')
                ->with('success', 'Thanh toán Online thành công! Đơn phòng đã được ghi nhận. Vui lòng chờ Admin xác nhận.')
                ->with('booking_id', $booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xử lý VNPay Store: ' . $e->getMessage());
            return back()->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }
    
    // ===============================================
    // 5. HÀM TẠO BOOKING CHUNG
    // ===============================================

    private function createBooking($request, $status, $paymentStatus, $phongTrong)
    {
        $loaiPhong = LoaiPhong::find($request->room_id);

        $days = Carbon::parse($request->checkin)->diffInDays(Carbon::parse($request->checkout)) ?: 1;
        $originalTotal = $loaiPhong->gia * $days;
        
        // ✅ VALIDATE DISCOUNT AMOUNT (không tin client)
        $discountAmount = 0;
        if ($request->promotion_code) {
            $promoCode = strtoupper($request->promotion_code);
            $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $promoCode)
                ->whereDate('ngay_bat_dau', '<=', Carbon::now())
                ->whereDate('ngay_ket_thuc', '>=', Carbon::now())
                ->first();
            
            if ($khuyenMai) {
                // Tính lại discount từ database (không tin client)
                $discountAmount = ($khuyenMai->chiet_khau_phan_tram > 0) 
                    ? $originalTotal * ($khuyenMai->chiet_khau_phan_tram / 100)
                    : $khuyenMai->so_tien_giam_gia;
                
                // Giới hạn discount không vượt quá giá gốc
                if ($discountAmount > $originalTotal) {
                    $discountAmount = $originalTotal;
                }
                
                // ✅ TRACKING: Ghi nhận user đã dùng mã này
                $this->trackPromoUsage($khuyenMai->id, Auth::id());
                
            } else {
                // Mã không hợp lệ → discount = 0
                $discountAmount = 0;
            }
        }
        
        // ✅ BẮT BUỘC: Tính lại từ server, không tin client input
        $subtotal = $originalTotal - $discountAmount;
        $vatAmount = $subtotal * 0.08;
        $finalTotal = $subtotal + $vatAmount;

        $booking = DatPhong::create([
            'user_id' => Auth::id(),
            'ngay_den' => $request->checkin,
            'ngay_di' => $request->checkout,
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'tong_tien' => $finalTotal,
            'trang_thai' => $status,
            'payment_status' => $paymentStatus,
            'payment_method' => $request->payment_method,
            'promotion_code' => $request->promotion_code,
            'discount_amount' => $discountAmount,
            'ghi_chu' => $request->ghi_chu ?? ($request->vnp_OrderInfo ?? null),
        ]);

        // Tạo chi tiết đặt phòng (gán phòng vật lý đã tìm thấy)
        ChiTietDatPhong::create([
            'dat_phong_id' => $booking->id,
            'loai_phong_id' => $loaiPhong->id,
            'phong_id' => $phongTrong->id,
            'so_luong' => 1,
            'don_gia' => $loaiPhong->gia,
            'thanh_tien' => $originalTotal,
        ]);

        // Lưu ID đơn hàng vào session (dùng cho trang success)
        // Dùng key 'booking_id' để nhất quán với các redirect khác.
        session()->flash('booking_id', $booking->id);
        return $booking;
    }

    public function paymentCallback(Request $request) { return redirect()->route('trang_chu'); }

    // ===============================================
    // [TRACKING MÃ KHUYẾN MÃI]
    // ===============================================
    
    private function trackPromoUsage($khuyenMaiId, $userId)
    {
        // Tăng usage_count trong bảng khuyến mãi
        KhuyenMai::where('id', $khuyenMaiId)->increment('used_count');
        
        // Ghi nhận hoặc cập nhật lần sử dụng của user
        $usage = \App\Models\KhuyenMaiUsage::firstOrNew([
            'user_id' => $userId,
            'khuyen_mai_id' => $khuyenMaiId,
        ]);
        
        $usage->used_count = ($usage->used_count ?? 0) + 1;
        $usage->last_used_at = now();
        $usage->save();
    }

    // ===============================================
    // [HÀM LỊCH SỬ & HÓA ĐƠN]
    // ===============================================
    
    public function history()
    {
        $bookings = DatPhong::where('user_id', Auth::id())
            ->with(['chiTietDatPhongs.loaiPhong', 'hoaDon'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('client.booking.history', compact('bookings'));
    }

    public function detail($id)
    {
        $booking = DatPhong::where('user_id', Auth::id())
            ->with(['chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong', 'hoaDon', 'user'])
            ->findOrFail($id);

        return view('client.booking.detail', compact('booking'));
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500'
        ], [
            'cancel_reason.required' => 'Vui lòng nhập lý do hủy đơn.',
            'cancel_reason.max' => 'Lý do hủy không được quá 500 ký tự.'
        ]);

        DB::beginTransaction();
        try {
            $booking = DatPhong::where('user_id', Auth::id())
                ->with(['chiTietDatPhongs.phong', 'hoaDon'])
                ->findOrFail($id);

            // Chỉ cho phép hủy đơn chưa thanh toán và chưa hoàn thành
            if ($booking->payment_status == 'paid') {
                return back()->with('error', 'Không thể hủy đơn đã thanh toán. Vui lòng liên hệ với chúng tôi để được hỗ trợ.');
            }

            if (in_array($booking->trang_thai, ['completed', 'cancelled'])) {
                return back()->with('error', 'Không thể hủy đơn đã hoàn thành hoặc đã bị hủy trước đó.');
            }

            // Cập nhật trạng thái đơn
            $booking->update([
                'trang_thai' => 'cancelled',
                'cancel_reason' => $request->cancel_reason,
                'cancelled_at' => now()
            ]);

            // Giải phóng phòng (nếu đã được gán)
            foreach ($booking->chiTietDatPhongs as $detail) {
                if ($detail->phong) {
                    $detail->phong->update(['tinh_trang' => 'available']);
                }
            }

            // Xóa hoặc cập nhật hóa đơn
            if ($booking->hoaDon) {
                $booking->hoaDon->update(['trang_thai' => 'cancelled']);
            }

            DB::commit();

            return redirect()->route('bookings.history')
                ->with('success', 'Đã hủy đơn đặt phòng thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi hủy đơn: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi hủy đơn: ' . $e->getMessage());
        }
    }

    public function invoice($id)
    {
        $booking = DatPhong::where('user_id', Auth::id())
            ->with(['chiTietDatPhongs.loaiPhong', 'chiTietDatPhongs.phong', 'hoaDon', 'user'])
            ->findOrFail($id);

        // If request contains ?print=1 or ?pdf=1, use the minimal print layout
        $usePrintLayout = request()->query('print') || request()->query('pdf');
        $layout = $usePrintLayout ? 'layouts.print' : 'layouts.app';

        return view('client.booking.invoice', compact('booking'))->with('layout', $layout);
    }

    // Server-side PDF export removed; printing is handled via browser print and `layouts.print` when ?print=1
}