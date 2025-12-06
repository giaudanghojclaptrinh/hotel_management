<?php

namespace App\Http\Controllers;

use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    public function getDanhSach()
    {
        $khuyenMais = KhuyenMai::all();
        return view('khuyen_mai.danh_sach', compact('khuyenMais'));
    }

    public function getThem()
    {
        return view('khuyen_mai.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'ten_khuyen_mai' => 'required|string|max:150',
            'phan_tram_giam' => 'required|numeric',
            'ngay_bat_dau' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date',
        ]);

        $orm = new KhuyenMai();
        $orm->ten_khuyen_mai = $data['ten_khuyen_mai'];
        $orm->phan_tram_giam = $data['phan_tram_giam'];
        $orm->ngay_bat_dau = $data['ngay_bat_dau'] ?? null;
        $orm->ngay_ket_thuc = $data['ngay_ket_thuc'] ?? null;
        $orm->save();
        return redirect()->route('khuyen-mai');
    }

    public function getSua($id)
    {
        $khuyenMais = KhuyenMai::findOrFail($id);
        return view('khuyen_mai.sua', compact('khuyenMais'));
    }

    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'ten_khuyen_mai' => 'required|string|max:150',
            'phan_tram_giam' => 'required|numeric',
            'ngay_bat_dau' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date',
        ]);

        $orm = KhuyenMai::findOrFail($id);
        $orm->ten_khuyen_mai = $data['ten_khuyen_mai'];
        $orm->phan_tram_giam = $data['phan_tram_giam'];
        $orm->ngay_bat_dau = $data['ngay_bat_dau'] ?? null;
        $orm->ngay_ket_thuc = $data['ngay_ket_thuc'] ?? null;
        $orm->save();
        return redirect()->route('khuyen-mai');
    }

    public function getXoa($id)
    {
        $orm = KhuyenMai::findOrFail($id);
        $orm->delete();
        return redirect()->route('khuyen-mai');
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
