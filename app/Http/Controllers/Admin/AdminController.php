<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Protect admin area by auth + AdminMiddleware
        $this->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
    }

    public function index()
    {
        return view('admin.layouts.dashboard');
    }
}
