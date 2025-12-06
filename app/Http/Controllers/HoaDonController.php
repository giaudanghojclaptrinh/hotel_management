<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    
    public function getDanhSach()
    {
        $hoaDons = HoaDon::all();
        return view('hoa_don.danh_sach', compact('hoaDons'));
    }

    public function getThem()
    {
        return view('hoa_don.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'dat_phong_id' => 'required|exists:dat_phongs,id',
            'ma_hoa_don' => 'required|string|max:100',
            'ngay_lap' => 'required|date',
            'tong_tien' => 'required|numeric',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = new HoaDon();
        $orm->dat_phong_id = $data['dat_phong_id'];
        $orm->ma_hoa_don = $data['ma_hoa_don'];
        $orm->ngay_lap = $data['ngay_lap'];
        $orm->tong_tien = $data['tong_tien'];
        $orm->trang_thai = $data['trang_thai'] ?? null;
        $orm->save();
        return redirect()->route('hoa-don');
    }

    public function getSua($id)
    {
        $hoaDons = HoaDon::findOrFail($id);
        return view('hoa_don.sua', compact('hoaDons'));
    }

    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'dat_phong_id' => 'required|exists:dat_phongs,id',
            'ma_hoa_don' => 'required|string|max:100',
            'ngay_lap' => 'required|date',
            'tong_tien' => 'required|numeric',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = HoaDon::findOrFail($id);
        $orm->dat_phong_id = $data['dat_phong_id'];
        $orm->ma_hoa_don = $data['ma_hoa_don'];
        $orm->ngay_lap = $data['ngay_lap'];
        $orm->tong_tien = $data['tong_tien'];
        $orm->trang_thai = $data['trang_thai'] ?? $orm->trang_thai;
        $orm->save();
        return redirect()->route('hoa-don');
    }

    public function getXoa($id)
    {
        $orm = HoaDon::findOrFail($id);
        $orm->delete();
        return redirect()->route('hoa-don');
    }

    /**
     * Display the specified resource.
     */
    public function show(HoaDon $hoaDon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HoaDon $hoaDon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HoaDon $hoaDon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoaDon $hoaDon)
    {
        //
    }
}
