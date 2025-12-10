<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoaiPhong;
use App\Models\KhuyenMai;
use Carbon\Carbon;

class PageController extends Controller
{
    /**
     * Trang chủ: Hiển thị phòng nổi bật và khuyến mãi hot
     */
    public function home()
    {
        // Lấy 3 loại phòng để show ở trang chủ
        $loaiPhongs = LoaiPhong::take(3)->get();
        
        // Lấy khuyến mãi còn hạn sử dụng
        $khuyenMais = KhuyenMai::where('ngay_ket_thuc', '>=', Carbon::today())->take(2)->get();

        return view('home', compact('loaiPhongs', 'khuyenMais'));
    }

    /**
     * Danh sách phòng: Có chức năng lọc/tìm kiếm
     */
    public function rooms(Request $request)
    {
        $query = LoaiPhong::query();

        // Nếu khách tìm kiếm theo số người
        if ($request->has('guests') && $request->guests > 0) {
            $query->where('suc_chua', '>=', $request->guests);
        }

        // Lấy danh sách phân trang (9 phòng/trang)
        $rooms = $query->paginate(9);

        return view('client.rooms.index', compact('rooms'));
    }

    /**
     * Chi tiết phòng: Xem ảnh, tiện nghi và form chọn ngày
     */
    public function roomDetail($id)
    {
        $room = LoaiPhong::with('phongs')->findOrFail($id);
        
        // Đếm số phòng trống thực tế (status = available)
        // Lưu ý: Cần đảm bảo bạn có cột 'tinh_trang' trong bảng 'phongs'
        $phongTrong = $room->phongs()->where('tinh_trang', 'available')->count();

        return view('client.rooms.detail', compact('room', 'phongTrong'));
    }

    /**
     * Danh sách khuyến mãi
     */
    public function promotions()
    {
        $promotions = KhuyenMai::where('ngay_ket_thuc', '>=', Carbon::today())->get();
        return view('client.promotions.index', compact('promotions'));
    }
}