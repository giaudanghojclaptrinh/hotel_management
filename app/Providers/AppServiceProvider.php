<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Feedback;
use App\Models\DatPhong;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share unhandled feedback count with admin sidebar
        View::composer('admin.layouts.sidebar', function ($view) {
            // Feedbacks
            try {
                $feedbackCount = Feedback::where('handled', false)->count();
            } catch (\Throwable $e) {
                $feedbackCount = 0;
            }

            // Pending bookings
            try {
                $pendingBookings = DatPhong::where('trang_thai', 'pending')->count();
            } catch (\Throwable $e) {
                $pendingBookings = 0;
            }

            // Provide variables expected by the sidebar view
            $view->with(compact('feedbackCount', 'pendingBookings'));
            // Also keep the old name used for feedbacks
            $view->with('pendingFeedbacksCount', $feedbackCount);
        });
    }
}
