<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phong;
use App\Models\DatPhong;
use App\Models\User;
use App\Models\HoaDon;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        // Protect admin area by auth + AdminMiddleware
        $this->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
    }

    public function index()
    {
        // 1. Thống kê cơ bản
        $totalRevenue = HoaDon::where('trang_thai', 'paid')->sum('tong_tien');
        $todayRevenue = HoaDon::where('trang_thai', 'paid')->whereDate('created_at', Carbon::today())->sum('tong_tien');

        $pendingBookings = DatPhong::where('trang_thai', 'pending')->count();
        $totalBookings = DatPhong::count();

        $totalRooms = Phong::count();
        $occupiedRooms = Phong::where('tinh_trang', 'occupied')->count();
        $availableRooms = Phong::where('tinh_trang', 'available')->count();

        $totalUsers = User::where('role', 'user')->count();

        // 2. Danh sách đơn mới nhất
        $recentBookings = DatPhong::with('user', 'chiTietDatPhongs.phong')
                            ->orderBy('created_at', 'desc')
                            ->take(6)
                            ->get();

        // 3. Dữ liệu biểu đồ (7 ngày gần nhất)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            $chartData[] = HoaDon::where('trang_thai', 'paid')
                                 ->whereDate('created_at', $date)
                                 ->sum('tong_tien');
        }

        return view('admin.dashboard', compact(
            'totalRevenue', 'todayRevenue', 
            'pendingBookings', 'totalBookings',
            'totalRooms', 'occupiedRooms', 'availableRooms',
            'totalUsers', 'recentBookings',
            'chartLabels', 'chartData'
        ));
    }
}
