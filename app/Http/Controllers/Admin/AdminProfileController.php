<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileAudit;

class AdminProfileController extends Controller
{
    // Show edit form for current admin
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    // Update admin profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // compute changes for audit
        $changes = [];
        if ($user->name !== $data['name']) $changes['name'] = ['old' => $user->name, 'new' => $data['name']];
        if ($user->email !== $data['email']) $changes['email'] = ['old' => $user->email, 'new' => $data['email']];

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $changes['password'] = ['old' => '*****', 'new' => '*****'];
        }

        $user->save();

        // record audit only if changes exist
        if (!empty($changes)) {
            ProfileAudit::create([
                'user_id' => $user->id,
                'changes' => $changes,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'Cập nhật hồ sơ thành công');
    }
}
