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
     * Danh sách Loại Phòng (Tìm kiếm & Lọc & Sắp xếp)
     */
    public function rooms(Request $request)
    {
        $query = LoaiPhong::query();

        // 1. ĐẾM SỐ PHÒNG TRỐNG (SỬA LẠI TÊN CỘT VÀ GIÁ TRỊ)
        // Cột trong DB là 'tinh_trang', giá trị là 'available'
        $query->withCount(['phongs' => function ($q) {
            $q->where('tinh_trang', 'available'); 
        }]);

        // 2. CÁC BỘ LỌC
        
        // Lọc Giá Min
        if ($request->filled('min_price')) {
            $query->where('gia', '>=', $request->input('min_price'));
        }
        
        // Lọc Giá Max
        if ($request->filled('max_price')) {
            $query->where('gia', '<=', $request->input('max_price'));
        }

        // Lọc Sức chứa
        if ($request->filled('capacity')) {
            $capacities = $request->input('capacity');
            $query->where(function($q) use ($capacities) {
                $q->whereIn('suc_chua', $capacities); // Lưu ý: DB bạn là 'so_nguoi' hay 'suc_chua'? 
                // Trong file SQL là 'so_nguoi', nếu code lỗi cột suc_chua thì đổi thành so_nguoi nhé.
                // Tạm thời mình giữ 'suc_chua' theo các bước trước, nếu lỗi báo mình đổi lại 'so_nguoi'.
                if (in_array('4', $capacities)) {
                    $q->orWhere('suc_chua', '>=', 4);
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

        // 3. SẮP XẾP CHÍNH (Đẩy phòng còn trống lên trên)
        $query->orderBy('phongs_count', 'desc');

        // 4. SẮP XẾP PHỤ
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

        // 5. Phân trang
        $rooms = $query->paginate(9)->withQueryString();
        
        // 6. Lấy dữ liệu cho Sidebar
        $tienNghis = TienNghi::all();
        $allLoaiPhongs = LoaiPhong::select('id', 'ten_loai_phong')->get();

        return view('client.rooms.index', compact('rooms', 'tienNghis', 'allLoaiPhongs'));
    }

    /**
     * Chi tiết Loại Phòng
     */
    public function roomDetail($id)
    {
        $room = LoaiPhong::with(['tienNghis', 'phongs'])->findOrFail($id);
        
        // SỬA LẠI: Đếm số phòng trống thực tế theo cột 'tinh_trang'
        $phongTrong = $room->phongs->where('tinh_trang', 'available')->count(); 

        // Gợi ý phòng khác (Lưu ý cột so_nguoi/suc_chua)
        $relatedRooms = LoaiPhong::where('so_nguoi', $room->so_nguoi) // SQL của bạn dùng 'so_nguoi'
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