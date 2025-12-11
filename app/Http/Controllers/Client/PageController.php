<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// --- 1. IMPORT MODELS ---
use App\Models\LoaiPhong;
use App\Models\Phong;
use App\Models\KhuyenMai;
use App\Models\TienNghi;
use App\Models\ChiTietDatPhong; // <--- Cần thêm model này để check lịch

class PageController extends Controller
{
    /**
     * Trang chủ
     */
    public function home()
    {
        $loaiPhongs = LoaiPhong::take(3)->get();
        $khuyenMais = KhuyenMai::where('ngay_ket_thuc', '>=', Carbon::today())->take(2)->get();

        return view('home', compact('loaiPhongs', 'khuyenMais'));
    }

    /**
     * Danh sách Loại Phòng (Tìm kiếm & Lọc & Sắp xếp & Check Lịch)
     */
    public function rooms(Request $request)
    {
        $query = LoaiPhong::query();

        // --- 1. LOGIC TÌM PHÒNG TRỐNG THEO NGÀY (NÂNG CAO) ---
        $busyRoomIds = [];

        // Nếu khách hàng có chọn ngày checkin/checkout trên bộ lọc
        if ($request->filled('checkin') && $request->filled('checkout')) {
            $checkIn = Carbon::parse($request->checkin);
            $checkOut = Carbon::parse($request->checkout);

            // Tìm các phòng đã có đơn đặt trong khoảng thời gian này
            // Logic trùng: (Ngày Đến Cũ < Ngày Đi Mới) VÀ (Ngày Đi Cũ > Ngày Đến Mới)
            $busyRoomIds = ChiTietDatPhong::whereHas('datPhong', function ($q) use ($checkIn, $checkOut) {
                // Chỉ xét các đơn đang hoạt động (chưa hủy)
                // Kể cả 'pending' (chưa duyệt) cũng phải chặn để tránh khách khác đặt chồng lên
                $q->whereIn('trang_thai', ['pending', 'confirmed', 'awaiting_payment']) 
                  ->where(function ($sub) use ($checkIn, $checkOut) {
                      $sub->where('ngay_den', '<', $checkOut)
                          ->where('ngay_di', '>', $checkIn);
                  });
            })->pluck('phong_id')->toArray();
        }

        // Đếm số phòng trống thực tế cho từng loại phòng
        // (Trừ đi các phòng đang bảo trì HOẶC đã có người đặt trong ngày đó)
        $query->withCount(['phongs' => function ($q) use ($busyRoomIds) {
            // Phòng vật lý phải không bảo trì
            $q->where('tinh_trang', '!=', 'maintenance'); 
            
            // Nếu có danh sách phòng bận theo ngày, loại bỏ chúng ra
            if (!empty($busyRoomIds)) {
                $q->whereNotIn('id', $busyRoomIds);
            }
        }]);

        // --- 2. CÁC BỘ LỌC CƠ BẢN ---
        
        // Lọc Giá Min
        if ($request->filled('min_price')) {
            $query->where('gia', '>=', $request->input('min_price'));
        }
        
        // Lọc Giá Max
        if ($request->filled('max_price')) {
            $query->where('gia', '<=', $request->input('max_price'));
        }

        // Lọc Sức chứa (Sửa 'suc_chua' -> 'so_nguoi')
        if ($request->filled('capacity')) {
            $capacities = $request->input('capacity');
            $query->where(function($q) use ($capacities) {
                // Sửa tên cột cho khớp với database
                $q->whereIn('so_nguoi', $capacities); 
                
                if (in_array('4', $capacities)) {
                    $q->orWhere('so_nguoi', '>=', 4);
                }
            });
        }

        // Lọc Tiện nghi
        if ($request->filled('amenities')) {
            $amenities = $request->input('amenities');
            $query->whereHas('tienNghis', function($q) use ($amenities) {
                $q->whereIn('tien_nghis.id', $amenities); 
            });
        }

        // Lọc Loại phòng (Checkbox)
        if ($request->filled('room_types')) {
            $query->whereIn('id', $request->input('room_types'));
        }

        // --- 3. SẮP XẾP CHÍNH (Đẩy phòng còn trống lên trên) ---
        $query->orderBy('phongs_count', 'desc');

        // --- 4. SẮP XẾP PHỤ ---
        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('gia', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('gia', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $rooms = $query->paginate(9)->withQueryString();
        
        // Dữ liệu Sidebar
        $tienNghis = TienNghi::all();
        $allLoaiPhongs = LoaiPhong::select('id', 'ten_loai_phong')->get();

        return view('client.rooms.index', compact('rooms', 'tienNghis', 'allLoaiPhongs'));
    }

    /**
     * Chi tiết Loại Phòng
     */
    public function roomDetail($id, Request $request)
    {
        $room = LoaiPhong::with(['tienNghis', 'phongs'])->findOrFail($id);
        
        // Logic đếm phòng trống tương tự cho trang chi tiết
        $busyRoomIds = [];
        if ($request->filled('checkin') && $request->filled('checkout')) {
            $checkIn = Carbon::parse($request->checkin);
            $checkOut = Carbon::parse($request->checkout);
            
            $busyRoomIds = ChiTietDatPhong::whereHas('datPhong', function ($q) use ($checkIn, $checkOut) {
                $q->whereIn('trang_thai', ['pending', 'confirmed', 'awaiting_payment']) 
                  ->where(function ($sub) use ($checkIn, $checkOut) {
                      $sub->where('ngay_den', '<', $checkOut)
                          ->where('ngay_di', '>', $checkIn);
                  });
            })->pluck('phong_id')->toArray();
        }

        // Đếm số phòng trống cụ thể của loại này trong khoảng thời gian đó
        $phongTrong = $room->phongs()
            ->where('tinh_trang', '!=', 'maintenance')
            ->when(!empty($busyRoomIds), function($q) use ($busyRoomIds) {
                $q->whereNotIn('id', $busyRoomIds);
            })
            ->count();

        // Gợi ý phòng khác (Sửa 'suc_chua' -> 'so_nguoi')
        $relatedRooms = LoaiPhong::where('so_nguoi', $room->so_nguoi)
                             ->where('id', '!=', $id)
                             ->take(3)
                             ->get();

        return view('client.rooms.detail', compact('room', 'relatedRooms', 'phongTrong'));
    }

    /**
     * Trang khuyến mãi
     */
    public function promotions()
    {
        $promotions = KhuyenMai::where('ngay_ket_thuc', '>=', Carbon::today())->get();
        return view('client.promotions.index', compact('promotions'));
    }
}