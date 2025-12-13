<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiPhong;
use App\Models\TienNghi; // [QUAN TRỌNG] Import model tiện nghi
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class LoaiPhongController extends Controller
{
    public function getDanhSach()
    {
        // Eager load tienNghis and paginate results
        $q = request()->query('q');
        $query = LoaiPhong::with('tienNghis');
        if ($q) {
            $query->where('ten_loai_phong', 'like', "%{$q}%");
        }
        $loaiPhongs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.loai_phong.danh_sach', compact('loaiPhongs'));
    }

    // --- CHỨC NĂNG THÊM MỚI ---
    public function getThem()
    {
        // Lấy danh sách tất cả tiện nghi để hiển thị Checkbox
        $tienNghis = TienNghi::all();
        return view('admin.loai_phong.them', compact('tienNghis'));
    }

    public function postThem(Request $request)
    {
        // 1. Validate
        $request->validate([
            'ten_loai_phong' => 'required|string|max:100',
            // Decimal(10,2) -> max integer part is 99999999, so cap accordingly to avoid DB overflow
            'gia' => 'nullable|numeric|max:99999999',
            'so_nguoi' => 'nullable|integer',
            'dien_tich' => 'nullable|integer',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Validate mảng tiện nghi (gửi từ checkbox name="tien_nghi[]")
            'tien_nghi' => 'nullable|array', 
            'tien_nghi.*' => 'exists:tien_nghis,id', // Đảm bảo ID tiện nghi tồn tại
        ]);

        // 2. Lưu thông tin cơ bản Loại Phòng
        $orm = new LoaiPhong();
        $orm->ten_loai_phong = $request->ten_loai_phong;
        $orm->gia = $request->gia ?? 0;
        $orm->so_nguoi = $request->so_nguoi ?? 1;
        $orm->dien_tich = $request->dien_tich ?? 0;
        // Lưu ý: Cột 'tien_nghi' (string) trong bảng loai_phongs giờ không dùng nữa 
        // vì đã có bảng trung gian, nên ta có thể bỏ qua hoặc để null.

        // 3. Xử lý Upload Ảnh
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/phongs'), $filename);
            $orm->hinh_anh = 'uploads/phongs/' . $filename;
        }

        $orm->save(); // Lưu xong mới có ID để gán quan hệ

        // 4. [QUAN TRỌNG] Lưu Tiện nghi vào bảng trung gian
        if ($request->has('tien_nghi')) {
            // attach: Thêm các dòng vào bảng loai_phong_tien_nghi
            $orm->tienNghis()->attach($request->tien_nghi);
        }

        return redirect()->route('admin.loai-phong')->with('success', 'Thêm mới thành công!');
    }
    
    // --- CHỨC NĂNG CẬP NHẬT (SỬA) ---
    public function getSua($id)
    {
        // Lấy loại phòng kèm theo các tiện nghi đã được gán
        $loaiPhong = LoaiPhong::with('tienNghis')->findOrFail($id);
        
        // Lấy tất cả tiện nghi để hiển thị checkbox
        $tienNghis = TienNghi::all();
        
        // Tạo mảng các ID tiện nghi đã chọn (để check sẵn trong View)
        // Ví dụ: [1, 3, 5]
        $selectedTienNghis = $loaiPhong->tienNghis->pluck('id')->toArray();

        return view('admin.loai_phong.sua', compact('loaiPhong', 'tienNghis', 'selectedTienNghis'));
    }

    public function postSua(Request $request, $id)
    {
        // 1. Validate
        $request->validate([
            'ten_loai_phong' => 'required|string|max:100',
            'gia' => 'nullable|numeric|max:99999999',
            'so_nguoi' => 'nullable|integer',
            'dien_tich' => 'nullable|integer',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tien_nghi' => 'nullable|array', // Mảng ID tiện nghi
        ]);

        $orm = LoaiPhong::findOrFail($id);
        
        // 2. Cập nhật thông tin cơ bản
        $orm->ten_loai_phong = $request->ten_loai_phong;
        if ($request->has('gia')) $orm->gia = $request->gia;
        if ($request->has('so_nguoi')) $orm->so_nguoi = $request->so_nguoi;
        if ($request->has('dien_tich')) $orm->dien_tich = $request->dien_tich;
        // 3. Xử lý Ảnh mới (nếu có)
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ
            if ($orm->hinh_anh && File::exists(public_path($orm->hinh_anh))) {
                File::delete(public_path($orm->hinh_anh));
            }
            // Up ảnh mới
            $file = $request->file('hinh_anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/phongs'), $filename);
            $orm->hinh_anh = 'uploads/phongs/' . $filename;
        }

        $orm->save();

        // 4. [QUAN TRỌNG] Đồng bộ tiện nghi (Sync)
        // Hàm sync sẽ: Xóa hết các ID cũ không có trong mảng gửi lên và Thêm các ID mới vào.
        // Nếu $request->tien_nghi là null (bỏ chọn hết), nó sẽ xóa sạch liên kết.
        $orm->tienNghis()->sync($request->input('tien_nghi', []));

        return redirect()->route('admin.loai-phong')->with('success', 'Cập nhật thành công!');
    }

    // --- CHỨC NĂNG XÓA ---
    public function getXoa($id)
    {
        $orm = LoaiPhong::findOrFail($id);
        
        // Xóa liên kết tiện nghi trong bảng trung gian
        $orm->tienNghis()->detach(); 

        // Xóa file ảnh
        if ($orm->hinh_anh && File::exists(public_path($orm->hinh_anh))) {
            File::delete(public_path($orm->hinh_anh));
        }

        $orm->delete();
        
        return redirect()->route('admin.loai-phong')->with('success', 'Xóa thành công!');
    }
}