<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    public function getDanhSach()
    {
        $q = request()->query('q');
        $query = KhuyenMai::query();
        if ($q) {
            $query->where('ten_khuyen_mai', 'like', "%{$q}%")
                  ->orWhere('ma_khuyen_mai', 'like', "%{$q}%");
        }
        $khuyenMais = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.khuyen_mai.danh_sach', compact('khuyenMais'));
    }

    public function getThem()
    {
        return view('admin.khuyen_mai.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'ten_khuyen_mai' => 'required|string|max:150',
            'ma_khuyen_mai' => 'required|string|max:50|unique:khuyen_mais,ma_khuyen_mai',
            'chiet_khau_phan_tram' => 'nullable|numeric|min:0|max:100',
            'so_tien_giam_gia' => 'nullable|numeric|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
        ], [
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'chiet_khau_phan_tram.max' => 'Chiết khấu tối đa là 100%.',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn 0.',
            'usage_per_user.min' => 'Số lần dùng/người phải lớn hơn 0.',
        ]);

        $orm = new KhuyenMai();
        $orm->ten_khuyen_mai = $data['ten_khuyen_mai'];
        $orm->ma_khuyen_mai = $data['ma_khuyen_mai'];
        // Normalize values: DB columns are NOT NULL (per migration), so ensure defaults
        $percent = isset($data['chiet_khau_phan_tram']) ? (float)$data['chiet_khau_phan_tram'] : 0.0;
        $fixed = isset($data['so_tien_giam_gia']) ? (float)$data['so_tien_giam_gia'] : 0.0;

        // If percent provided (non-zero), prioritize it and set fixed to 0
        if ($percent > 0) {
            $orm->chiet_khau_phan_tram = $percent;
            $orm->so_tien_giam_gia = 0.0;
        } else {
            // use fixed amount (could be 0)
            $orm->chiet_khau_phan_tram = 0.0;
            $orm->so_tien_giam_gia = $fixed;
        }

        $orm->ngay_bat_dau = $data['ngay_bat_dau'];
        $orm->ngay_ket_thuc = $data['ngay_ket_thuc'];
        $orm->usage_limit = $data['usage_limit'] ?? null;
        $orm->used_count = 0;
        $orm->usage_per_user = $data['usage_per_user'] ?? 1;
        $orm->save();
        return redirect()->route('admin.khuyen-mai');
    }

    public function getSua($id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        return view('admin.khuyen_mai.sua', compact('khuyenMai'));
    }

    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'ten_khuyen_mai' => 'required|string|max:150',
            'ma_khuyen_mai' => 'required|string|max:50|unique:khuyen_mais,ma_khuyen_mai,' . $id,
            'chiet_khau_phan_tram' => 'nullable|numeric|min:0|max:100',
            'so_tien_giam_gia' => 'nullable|numeric|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
        ], [
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'chiet_khau_phan_tram.max' => 'Chiết khấu tối đa là 100%.',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn 0.',
            'usage_per_user.min' => 'Số lần dùng/người phải lớn hơn 0.',
        ]);

        $orm = KhuyenMai::findOrFail($id);
        $orm->ten_khuyen_mai = $data['ten_khuyen_mai'];
        $orm->ma_khuyen_mai = $data['ma_khuyen_mai'];
        $percent = isset($data['chiet_khau_phan_tram']) ? (float)$data['chiet_khau_phan_tram'] : 0.0;
        $fixed = isset($data['so_tien_giam_gia']) ? (float)$data['so_tien_giam_gia'] : 0.0;

        if ($percent > 0) {
            $orm->chiet_khau_phan_tram = $percent;
            $orm->so_tien_giam_gia = 0.0;
        } else {
            $orm->chiet_khau_phan_tram = 0.0;
            $orm->so_tien_giam_gia = $fixed;
        }

        $orm->ngay_bat_dau = $data['ngay_bat_dau'];
        $orm->ngay_ket_thuc = $data['ngay_ket_thuc'];
        $orm->usage_limit = $data['usage_limit'] ?? null;
        $orm->usage_per_user = $data['usage_per_user'] ?? 1;
        $orm->save();
        return redirect()->route('admin.khuyen-mai');
    }

    public function getXoa($id)
    {
        $orm = KhuyenMai::withCount('usages')->findOrFail($id);
        
        // Kiểm tra xem khuyến mãi đã được sử dụng chưa
        if ($orm->used_count > 0 || $orm->usages_count > 0) {
            return redirect()->route('admin.khuyen-mai')
                ->with('error', 'Không thể xóa khuyến mãi đã được sử dụng!');
        }
        
        $orm->delete();
        return redirect()->route('admin.khuyen-mai')->with('success', 'Xóa khuyến mãi thành công!');
    }

    // Bulk delete
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:khuyen_mais,id',
        ]);
        
        $khuyenMais = KhuyenMai::withCount('usages')->whereIn('id', $request->ids)->get();
        $cannotDelete = [];
        $canDelete = [];
        
        foreach ($khuyenMais as $km) {
            if ($km->used_count > 0 || $km->usages_count > 0) {
                $cannotDelete[] = $km->ma_khuyen_mai;
            } else {
                $canDelete[] = $km->id;
            }
        }
        
        if (!empty($canDelete)) {
            KhuyenMai::whereIn('id', $canDelete)->delete();
        }
        
        if (!empty($cannotDelete)) {
            return redirect()->route('admin.khuyen-mai')
                ->with('warning', 'Đã xóa ' . count($canDelete) . ' khuyến mãi. Không thể xóa mã: ' . implode(', ', $cannotDelete) . ' (đã được sử dụng)');
        }
        
        return redirect()->route('admin.khuyen-mai')->with('success', 'Đã xóa ' . count($canDelete) . ' khuyến mãi thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KhuyenMai $khuyenMai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KhuyenMai $khuyenMai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KhuyenMai $khuyenMai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KhuyenMai $khuyenMai)
    {
        //
    }
}
