<?php

namespace App\Http\Controllers;

use App\Models\LoaiPhong;
use Illuminate\Http\Request;
use Str;

class LoaiPhongController extends Controller
{
    public function getDanhSach()
    {
        $loaiPhongs = LoaiPhong::all();
        return view('loai_phong.danh_sach', compact('loaiPhongs'));
    }

    public function getThem()
    {
        return view('loai_phong.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'ten_loai_phong' => 'required|string|max:100',
            'gia' => 'nullable|numeric',
            'so_nguoi' => 'nullable|integer',
            'tien_nghi' => 'nullable',
        ]);

        $orm = new LoaiPhong();
        $orm->ten_loai_phong = $data['ten_loai_phong'];
        $orm->gia = $data['gia'] ?? 0;
        $orm->so_nguoi = $data['so_nguoi'] ?? 1;
        $orm->tien_nghi = $data['tien_nghi'] ?? '';
        $orm->save();

        return redirect()->route('loai-phong');
    }
    
    public function getSua($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        return view('loai_phong.sua', compact('loaiPhong'));
    }

    public function getXoa($id)
    {
        $orm = LoaiPhong::findOrFail($id);
        $orm->delete();
        return redirect()->route('loai-phong');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'ten_loai_phong' => 'required|string|max:100',
            'gia' => 'nullable|numeric',
            'so_nguoi' => 'nullable|integer',
            'tien_nghi' => 'nullable|string',
        ]);

        $orm = LoaiPhong::findOrFail($id);
        $orm->ten_loai_phong = $data['ten_loai_phong'];
        if (isset($data['gia'])) {
            $orm->gia = $data['gia'];
        }
        if (isset($data['so_nguoi'])) {
            $orm->so_nguoi = $data['so_nguoi'];
        }
        if (isset($data['tien_nghi'])) {
            $orm->tien_nghi = $data['tien_nghi'];
        }
        $orm->save();

        return redirect()->route('loai-phong');
    }


    /**
     * Display the specified resource.
     */
    public function show(LoaiPhong $loaiPhong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoaiPhong $loaiPhong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoaiPhong $loaiPhong)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoaiPhong $loaiPhong)
    {
        //
    }
}
