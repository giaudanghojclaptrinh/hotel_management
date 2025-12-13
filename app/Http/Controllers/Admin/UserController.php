<?php

namespace App\Http\Controllers\Admin; // Namespace chuẩn

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 1. HÀM INDEX (Sửa lỗi Method not exist)
     * Hiển thị danh sách khách hàng (Chỉ lấy Role = user)
     */
    public function getDanhSach()
    {
        $q = request()->query('q');
        $role = request()->query('role');
        // Remove hard-coded role filter so admin can view all users (including admins)
        $query = User::query();
        if ($q) {
            $query->where(function($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
                   ->orWhere('phone', 'like', "%{$q}%");
            });
        }
        if ($role && in_array($role, ['admin', 'user'])) {
            $query->where('role', $role);
        }
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.users.danh_sach', compact('users'));
    }

    /**
     * Hiển thị form thêm mới
     */
    public function getThem()
    {
        return view('admin.users.them');
    }

    /**
     * Xử lý lưu khách hàng mới
     */
    public function postThem(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'cccd' => 'required|string|max:20|unique:users,cccd',
            'password' => 'required|string|min:6',
        ]);

        $orm = new User();
        $orm->name = $data['name'];
        $orm->email = $data['email'];
        $orm->phone = $data['phone'];
        $orm->cccd = $data['cccd'];
        $orm->role = 'user'; // Gán cứng là user
        $orm->password = Hash::make($data['password']);
        $orm->save();

        return redirect()->route('admin.users')->with('success', 'Thêm khách hàng thành công!');
    }

    /**
     * Hiển thị form sửa
     */
    public function getSua($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.sua', compact('user'));
    }

    /**
     * Xử lý cập nhật
     */
    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'phone' => ['required', 'string', 'max:15', Rule::unique('users', 'phone')->ignore($id)],
            'cccd' => ['required', 'string', 'max:20', Rule::unique('users', 'cccd')->ignore($id)],
            'password' => 'nullable|string|min:6',
        ]);

        $orm = User::findOrFail($id);
        $orm->name = $data['name'];
        $orm->email = $data['email'];
        $orm->phone = $data['phone'];
        $orm->cccd = $data['cccd'];
        
        if (!empty($data['password'])) {
            $orm->password = Hash::make($data['password']);
        }
        
        $orm->save();
        
        return redirect()->route('admin.users')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Xóa tài khoản
     */
    public function getXoa($id)
    {
        $orm = User::findOrFail($id);
        
        if ($orm->role === 'admin') {
            return redirect()->route('admin.users')->with('error', 'Không thể xóa Admin!');
        }

        $orm->delete();
        return redirect()->route('admin.users')->with('success', 'Đã xóa khách hàng.');
    }

    // Bulk delete users
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        $ids = $request->ids;
        // Prevent deleting admins
        $deleted = 0;
        foreach ($ids as $id) {
            $u = User::find($id);
            if ($u && $u->role !== 'admin') {
                $u->delete();
                $deleted++;
            }
        }
        return redirect()->route('admin.users')->with('success', "Đã xóa $deleted người dùng.");
    }
}