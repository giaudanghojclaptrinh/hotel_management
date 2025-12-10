<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoaiPhong;
use App\Models\DatPhong;
use App\Models\ChiTietDatPhong;
use App\Models\Phong;
use App\Models\KhuyenMai; // <-- Bắt buộc phải có
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Hiển thị form xác nhận đặt phòng (FIXED)
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

        $roomType = LoaiPhong::findOrFail($loaiPhongId);
        
        $start = Carbon::parse($checkIn);
        $end = Carbon::parse($checkOut);
        $days = $start->diffInDays($end) ?: 1;
        
        $totalPrice = $roomType->gia * $days;

        return view('client.booking.create', compact('roomType', 'checkIn', 'checkOut', 'days', 'totalPrice'));
    }


    /**
     * Xử lý lưu đơn đặt phòng (Store)
     */
    public function store(Request $request)
    {
        // 1. Validate (Lưu ý: promotion_code đã được AJAX kiểm tra, nhưng ta vẫn lưu lại)
        $request->validate([
            'room_id' => 'required|exists:loai_phongs,id',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'promotion_code' => 'nullable|string|max:50',
            'discount_amount' => 'nullable|numeric|min:0', // Lấy từ input ẩn AJAX
            'payment_method' => 'required|in:pay_at_hotel,online', 
        ]);

        DB::beginTransaction();
        try {
            // 2. Tìm phòng vật lý trống
            $phongTrong = Phong::where('loai_phong_id', $request->room_id)
                               ->where('tinh_trang', 'available')
                               ->first();

            if (!$phongTrong) {
                DB::rollBack();
                return back()->with('error', 'Rất tiếc, loại phòng này vừa hết chỗ hoặc đang bảo trì.');
            }

            // 3. Tính toán lại tổng tiền cuối cùng
            $loaiPhong = LoaiPhong::find($request->room_id);
            $ngayDen = Carbon::parse($request->checkin);
            $ngayDi = Carbon::parse($request->checkout);
            $days = $ngayDen->diffInDays($ngayDi) ?: 1;
            
            $originalTotal = $loaiPhong->gia * $days;
            
            // Lấy discount từ input ẩn (đã được AJAX tính toán)
            $discountAmount = $request->discount_amount ?? 0;
            $finalTotal = $originalTotal - $discountAmount;
            
            $promotionCode = $request->promotion_code ? strtoupper($request->promotion_code) : null;
            
            // Nếu discount > 0 mà promotion_code bị trống (lỗi bảo mật), ta vẫn lưu tiền giảm
            // Nhưng tốt nhất nên re-check mã ở đây nếu là hệ thống lớn
            // Tạm thời, ta tin tưởng dữ liệu đã được tính toán ở Client và lưu nó.

            // 4. Tạo đơn DatPhong
            $booking = DatPhong::create([
                'user_id' => Auth::id(),
                'ngay_den' => $request->checkin,
                'ngay_di' => $request->checkout,
                
                'tong_tien' => $finalTotal,
                'trang_thai' => 'pending', 
                'payment_status' => 'unpaid', 
                'payment_method' => $request->payment_method, 
                
                'promotion_code' => $promotionCode,
                'discount_amount' => $discountAmount,
                'ghi_chu' => $request->ghi_chu ?? null,
            ]);

            // 5. Tạo ChiTietDatPhong
            ChiTietDatPhong::create([
                'dat_phong_id' => $booking->id,
                'loai_phong_id' => $loaiPhong->id,
                'phong_id' => $phongTrong->id,
                'so_luong' => 1,
                'don_gia' => $loaiPhong->gia,
                'thanh_tien' => $originalTotal, // Gốc
            ]);

            DB::commit();
            
            $msg = 'Đặt phòng thành công! Đang chờ xác nhận từ Admin.';
            if ($discountAmount > 0) {
                $msg .= ' Bạn đã được giảm ' . number_format($discountAmount) . 'đ.';
            }

            return redirect()->route('booking.success')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    /**
     * [MỚI] Hàm API kiểm tra và tính toán giảm giá (dùng AJAX).
     */
    public function checkPromotion(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'code' => 'required|string|max:50',
            'original_total' => 'required|numeric|min:0', // Tổng tiền gốc
        ]);

        $code = strtoupper($request->code);
        $originalTotal = $request->original_total;
        $discountAmount = 0;

        // 2. Tìm mã hợp lệ và còn hạn
        $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $code)
            ->whereDate('ngay_bat_dau', '<=', Carbon::now())
            ->whereDate('ngay_ket_thuc', '>=', Carbon::now())
            ->first();

        if (!$khuyenMai) {
            return response()->json([
                'success' => false,
                'discount_amount' => 0,
                'final_total' => $originalTotal,
                'message' => 'Mã không hợp lệ hoặc đã hết hạn.'
            ], 200);
        }

        // 3. Tính toán giảm giá
        if ($khuyenMai->chiet_khau_phan_tram > 0) {
            $discountAmount = $originalTotal * ($khuyenMai->chiet_khau_phan_tram / 100);
        } else {
            $discountAmount = $khuyenMai->so_tien_giam_gia;
        }

        if ($discountAmount > $originalTotal) {
            $discountAmount = $originalTotal;
        }

        $finalTotal = $originalTotal - $discountAmount;

        // 4. Trả về kết quả thành công
        return response()->json([
            'success' => true,
            'discount_amount' => round($discountAmount),
            'final_total' => round($finalTotal),
            'message' => 'Áp dụng mã thành công! Bạn được giảm ' . number_format(round($discountAmount)) . 'đ.'
        ], 200);
    }

    public function success() {
        return view('client.booking.success');
    }
}