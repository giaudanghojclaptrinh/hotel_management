<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class QuanTriVienController extends Controller
{
    public function getDanhSach()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.quan_tri_vien.danh_sach', compact('admins'));
    }

    public function getThem()
    {
        return view('admin.quan_tri_vien.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15|unique:users,phone',
            'cccd' => 'nullable|string|max:20|unique:users,cccd',
            'password' => 'required|string|min:6',
        ]);

        $orm = new User();
        $orm->name = $data['name'];
        $orm->email = $data['email'];
        $orm->phone = $data['phone'] ?? null;
        $orm->cccd = $data['cccd'] ?? null;
        $orm->role = 'admin';
        $orm->password = $data['password'];
        $orm->save();
        return redirect()->route('quan-tri-vien');
    }

    public function getSua($id)
    {
        $admin = User::findOrFail($id);
        return view('admin.quan_tri_vien.sua', compact('admin'));
    }

    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => ['required','email', Rule::unique('users','email')->ignore($id)],
            'phone' => ['nullable','string','max:15', Rule::unique('users','phone')->ignore($id)],
            'cccd' => ['nullable','string','max:20', Rule::unique('users','cccd')->ignore($id)],
            'password' => 'nullable|string|min:6',
        ]);

        $orm = User::findOrFail($id);
        $orm->name = $data['name'];
        $orm->email = $data['email'];
        $orm->phone = $data['phone'] ?? $orm->phone;
        $orm->cccd = $data['cccd'] ?? $orm->cccd;
        if (!empty($data['password'])) {
            $orm->password = $data['password'];
        }
        $orm->save();
        return redirect()->route('quan-tri-vien');
    }

    public function getXoa($id)
    {
        $orm = User::findOrFail($id);
        $orm->delete();
        return redirect()->route('quan-tri-vien');
    }
}
