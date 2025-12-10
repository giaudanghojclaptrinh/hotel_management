<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phong;
use App\Models\LoaiPhong;
use Illuminate\Http\Request;

class PhongController extends Controller
{
    
    public function getDanhSach()
    {
        $phongs = Phong::all();
        return view('admin.phong.danh_sach', compact('phongs'));
    }

    public function getThem()
    {
        $loaiPhongs = LoaiPhong::all();
        return view('admin.phong.them', compact('loaiPhongs'));
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'so_phong' => 'required|string|max:150',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'tinh_trang' => 'nullable|string|max:50',
        ]);

        $orm = new Phong();
        $orm->so_phong = $data['so_phong'];
        $orm->loai_phong_id = $data['loai_phong_id'];
        // Use the migration default when no value is provided to avoid inserting explicit NULL
        $orm->tinh_trang = $data['tinh_trang'] ?? 'available';
        $orm->save();
        return redirect()->route('admin.phong');
    }

    public function getSua($id)
    {
        $phong = Phong::findOrFail($id);
        $loaiPhongs = LoaiPhong::all();
        return view('admin.phong.sua', compact('phong', 'loaiPhongs'));
    }
    
    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'so_phong' => 'required|string|max:150',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'tinh_trang' => 'nullable|string|max:50',
        ]);

        $orm = Phong::findOrFail($id);
        $orm->so_phong = $data['so_phong'];
        $orm->loai_phong_id = $data['loai_phong_id'];
        $orm->tinh_trang = $data['tinh_trang'] ?? $orm->tinh_trang;
        $orm->save();
        return redirect()->route('admin.phong');
    }

    public function getXoa($id)
    {
        $orm = Phong::findOrFail($id);
        $orm->delete();
        return redirect()->route('admin.phong');
    }
    /**
     * Display the specified resource.
     */
    public function show(Phong $phong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Phong $phong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phong $phong)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phong $phong)
    {
        //
    }
}
