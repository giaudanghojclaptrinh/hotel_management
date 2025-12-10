<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoaiPhong;
use App\Models\KhuyenMai;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Allow guests to view the public homepage (index)
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Return the public default layout as the homepage.
        // The public layout file is at resources/views/layouts/app.blade.php

        // 1. Lấy danh sách Loại phòng nổi bật (Ví dụ lấy 3 loại phòng đầu tiên hoặc ngẫu nhiên)
        $loaiPhongs = LoaiPhong::take(3)->get();

        // 2. Lấy Khuyến mãi đang hiệu lực (Ngày bắt đầu <= hôm nay <= Ngày kết thúc)
        $now = Carbon::now();
        $khuyenMais = KhuyenMai::whereDate('ngay_bat_dau', '<=', $now)
                               ->whereDate('ngay_ket_thuc', '>=', $now)
                               ->take(2) // Lấy 2 khuyến mãi mới nhất
                               ->get();

        return view('home', compact('loaiPhongs', 'khuyenMais'));

    }
}
