<?php

namespace App\Http\Controllers;

use App\Models\Phong;
use Illuminate\Http\Request;

class PhongController extends Controller
{
    
    public function getDanhSach()
    {
        $phongs = Phong::all();
        return view('phong.danh_sach', compact('phongs'));
    }

    public function getThem()
    {
        return view('phong.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'ten_phong' => 'required|string|max:150',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = new Phong();
        $orm->ten_phong = $data['ten_phong'];
        $orm->loai_phong_id = $data['loai_phong_id'];
        $orm->trang_thai = $data['trang_thai'] ?? null;
        $orm->save();
        return redirect()->route('phong');
    }

    public function getSua($id)
    {
        $phongs = Phong::findOrFail($id);
        return view('phong.sua', compact('phongs'));
    }
    
    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'ten_phong' => 'required|string|max:150',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'trang_thai' => 'nullable|string|max:50',
        ]);

        $orm = Phong::findOrFail($id);
        $orm->ten_phong = $data['ten_phong'];
        $orm->loai_phong_id = $data['loai_phong_id'];
        $orm->trang_thai = $data['trang_thai'] ?? $orm->trang_thai;
        $orm->save();
        return redirect()->route('phong');
    }

    public function getXoa($id)
    {
        $orm = Phong::findOrFail($id);
        $orm->delete();
        return redirect()->route('phong');
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
