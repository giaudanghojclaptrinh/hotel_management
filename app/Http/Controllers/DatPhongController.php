<?php

namespace App\Http\Controllers;

use App\Models\DatPhong;
use Illuminate\Http\Request;

class DatPhongController extends Controller
{
    public function getDanhSach()
    {
        $datPhongs = DatPhong::all();
        return view('dat_phong.danh_sach', compact('datPhongs'));
    }

    public function getThem()
    {
        return view('dat_phong.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ngay_den' => 'required|date',
            'ngay_di' => 'required|date|after_or_equal:ngay_den',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = new DatPhong();
        $orm->user_id = $data['user_id'];
        $orm->ngay_den = $data['ngay_den'];
        $orm->ngay_di = $data['ngay_di'];
        $orm->trang_thai = $data['trang_thai'] ?? null;
        $orm->save();
        return redirect()->route('dat-phong');
    }

    public function getSua($id)
    {
        $datPhongs = DatPhong::findOrFail($id);
        return view('dat_phong.sua', compact('datPhongs'));
    }
    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ngay_den' => 'required|date',
            'ngay_di' => 'required|date|after_or_equal:ngay_den',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = DatPhong::findOrFail($id);
        $orm->user_id = $data['user_id'];
        $orm->ngay_den = $data['ngay_den'];
        $orm->ngay_di = $data['ngay_di'];
        $orm->trang_thai = $data['trang_thai'] ?? $orm->trang_thai;
        $orm->save();
        return redirect()->route('dat-phong');
    }

    public function getXoa($id)
    {
        $orm = DatPhong::findOrFail($id);
        $orm->delete();
        return redirect()->route('dat-phong');
    }
    /**
     * Display the specified resource.
     */
    public function show(DatPhong $datPhong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatPhong $datPhong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatPhong $datPhong)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatPhong $datPhong)
    {
        //
    }
}
