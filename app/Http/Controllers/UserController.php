<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDanhSach()
    {
        $users = User::all();
        return view('users.danh_sach', compact('users'));
    }

    public function getThem()
    {
        // No data required to show the create form
        return view('users.them');
    }

    public function postThem(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'cccd' => 'required|string|max:20|unique:users,cccd',
            'username' => 'required|string|max:100|unique:users,username',
            'role' => 'required|string|max:50',
            'password' => 'required|string|min:6',
        ]);

        $orm = new User();
        $orm->name = $data['name'];
        $orm->email = $data['email'];
        $orm->phone = $data['phone'];
        $orm->cccd = $data['cccd'];
        $orm->username = $data['username'];
        $orm->role = $data['role'];
        // set raw password; model mutator will hash it
        $orm->password = $data['password'];
        $orm->save();
        return redirect()->route('user');
    }

    public function getSua($id)
    {
        $user = User::findOrFail($id);
        return view('users.sua', compact('user'));
    }

    public function postSua(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users', 'phone')->ignore($id),
            ],
            'cccd' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'cccd')->ignore($id),
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($id),
            ],
            'role' => 'required|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $orm = User::findOrFail($id);
        $orm->name = $data['name'];
        $orm->email = $data['email'];
        $orm->phone = $data['phone'];
        $orm->cccd = $data['cccd'];
        $orm->username = $data['username'];
        $orm->role = $data['role'];
        if (!empty($data['password'])) {
            // model mutator will hash
            $orm->password = $data['password'];
        }
        $orm->save();
        return redirect()->route('user');
    }

    public function getXoa($id)
    {
        $orm = User::findOrFail($id);
        $orm->delete();
        return redirect()->route('user');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
