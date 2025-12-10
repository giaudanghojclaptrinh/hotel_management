<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoaiPhong;
use App\Models\DatPhong;
use App\Models\ChiTietDatPhong;
use App\Models\Phong;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $loaiPhongId = $request->room_id;
        $checkIn = $request->checkin;
        $checkOut = $request->checkout;

        if (!$loaiPhongId || !$checkIn || !$checkOut) {
            return redirect()->route('phong')->with('error', 'Vui lòng chọn ngày và loại phòng trước!');
        }

        $roomType = LoaiPhong::findOrFail($loaiPhongId);
        
        $start = Carbon::parse($checkIn);
        $end = Carbon::parse($checkOut);
        $days = $start->diffInDays($end) ?: 1;
        
        // SỬA: gia_dem -> gia
        $totalPrice = $roomType->gia * $days;

        return view('client.booking.create', compact('roomType', 'checkIn', 'checkOut', 'days', 'totalPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:loai_phongs,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        DB::beginTransaction();
        try {
            $phongTrong = Phong::where('loai_phong_id', $request->room_type_id)
                               ->where('tinh_trang', 'available')
                               ->first();

            if (!$phongTrong) {
                return back()->with('error', 'Rất tiếc, loại phòng này vừa hết chỗ.');
            }

            $loaiPhong = LoaiPhong::find($request->room_type_id);
            $days = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out)) ?: 1;
            
            // SỬA: gia_dem -> gia
            $total = $loaiPhong->gia * $days;

            // Tạo đơn DatPhong
            $booking = DatPhong::create([
                'user_id' => Auth::id(),
                'ngay_den' => $request->check_in,
                'ngay_di' => $request->check_out,
                'tong_tien' => $total,
                'trang_thai' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Tạo ChiTietDatPhong
            ChiTietDatPhong::create([
                'dat_phong_id' => $booking->id,
                'loai_phong_id' => $loaiPhong->id,
                'phong_id' => $phongTrong->id,
                'so_luong' => 1,
                'don_gia' => $loaiPhong->gia, // SỬA: gia_dem -> gia
                'thanh_tien' => $total,
            ]);

            DB::commit();
            return redirect()->route('booking.success')->with('booking_id', $booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function success() {
        return view('client.booking.success');
    }
}