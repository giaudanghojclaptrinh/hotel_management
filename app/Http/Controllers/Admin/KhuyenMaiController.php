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
            'chiet_khau_phan_tram' => 'nullable|numeric',
            'so_tien_giam_gia' => 'nullable|numeric',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date',
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
            'chiet_khau_phan_tram' => 'nullable|numeric',
            'so_tien_giam_gia' => 'nullable|numeric',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date',
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
        $orm->save();
        return redirect()->route('admin.khuyen-mai');
    }

    public function getXoa($id)
    {
        $orm = KhuyenMai::findOrFail($id);
        $orm->delete();
        return redirect()->route('admin.khuyen-mai');
    }

    // Bulk delete
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:khuyen_mais,id',
        ]);
        KhuyenMai::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.khuyen-mai')->with('success', 'Đã xóa các khuyến mãi được chọn.');
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
