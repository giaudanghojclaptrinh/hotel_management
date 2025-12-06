<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDatPhong;
use Illuminate\Http\Request;

class ChiTietDatPhongController extends Controller
{
    public function getDanhSach()
    {
        $chiTietDatPhongs = ChiTietDatPhong::all();
        return view('chi_tiet_dat_phong.danh_sach', compact('chiTietDatPhongs'));
    }

    public function getThem()
    {
        return view('chi_tiet_dat_phong.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'dat_phong_id' => 'required|exists:dat_phongs,id',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'phong_id' => 'required|exists:phongs,id',
            'so_luong' => 'required|integer|min:1',
            'gia' => 'required|numeric',
        ]);

        $orm = new ChiTietDatPhong();
        $orm->dat_phong_id = $data['dat_phong_id'];
        $orm->loai_phong_id = $data['loai_phong_id'];
        $orm->phong_id = $data['phong_id'];
        $orm->so_luong = $data['so_luong'];
        $orm->gia = $data['gia'];
        $orm->save();
        return redirect()->route('chi-tiet-dat-phong');
    }

    public function getSua($id)
    {
        $chiTietDatPhongs = ChiTietDatPhong::findOrFail($id);
        return view('chi_tiet_dat_phong.sua', compact('chiTietDatPhongs'));
    }

    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'dat_phong_id' => 'required|exists:dat_phongs,id',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'phong_id' => 'required|exists:phongs,id',
            'so_luong' => 'required|integer|min:1',
            'gia' => 'required|numeric',
        ]);

        $orm = ChiTietDatPhong::findOrFail($id);
        $orm->dat_phong_id = $data['dat_phong_id'];
        $orm->loai_phong_id = $data['loai_phong_id'];
        $orm->phong_id = $data['phong_id'];
        $orm->so_luong = $data['so_luong'];
        $orm->gia = $data['gia'];
        $orm->save();
        return redirect()->route('chi-tiet-dat-phong');
    }

    public function getXoa($id)
    {
        $orm = ChiTietDatPhong::findOrFail($id);
        $orm->delete();
        return redirect()->route('chi-tiet-dat-phong');
    }
    /**
     * Display the specified resource.
     */
    public function show(ChiTietDatPhong $chiTietDatPhong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChiTietDatPhong $chiTietDatPhong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChiTietDatPhong $chiTietDatPhong)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChiTietDatPhong $chiTietDatPhong)
    {
        //
    }
}
