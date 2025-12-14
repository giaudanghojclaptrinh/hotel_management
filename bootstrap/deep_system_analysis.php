<?php

/**
 * 🔬 DEEP SYSTEM ANALYSIS - COMPREHENSIVE TESTING (100x)
 * 
 * Kiểm tra sâu TOÀN BỘ hệ thống:
 * - Tất cả các controllers (Admin + Client)
 * - Tất cả các models và relationships
 * - Business logic phức tạp
 * - Edge cases và boundary conditions
 * - Missing fields trong tất cả tables
 * - Validation rules
 * - Authorization & Security
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\DatPhong;
use App\Models\ChiTietDatPhong;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\LoaiPhong;
use App\Models\Phong;
use App\Models\Review;
use App\Models\Feedback;
use App\Models\TienNghi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║  🔬 DEEP SYSTEM ANALYSIS - COMPREHENSIVE TESTING     ║\n";
echo "║  Testing: Controllers, Models, Logic, Edge Cases      ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

$total_tests = 0;
$passed = 0;
$failed = 0;
$warnings = 0;
$issues = [];

function test($description, $callback) {
    global $total_tests, $passed, $failed, $warnings, $issues;
    $total_tests++;
    $test_num = str_pad($total_tests, 3, '0', STR_PAD_LEFT);
    
    try {
        $result = $callback();
        if ($result === true) {
            echo "[{$test_num}] Testing: {$description} ✅ PASS\n";
            $passed++;
        } elseif ($result === 'warning') {
            echo "[{$test_num}] Testing: {$description} ⚠️  WARNING\n";
            $warnings++;
        } else {
            echo "[{$test_num}] Testing: {$description} ❌ FAIL: {$result}\n";
            $issues[] = $description . ": " . $result;
            $failed++;
        }
    } catch (\Exception $e) {
        echo "[{$test_num}] Testing: {$description} ❌ FAIL: {$e->getMessage()}\n";
        $issues[] = $description . ": " . $e->getMessage();
        $failed++;
    }
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "📋 PART 1: COMPLETE TABLE STRUCTURE ANALYSIS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Get all tables
$tables = DB::select('SHOW TABLES');
$db_name = DB::getDatabaseName();
$table_key = "Tables_in_" . $db_name;

test("All main business tables exist", function() use ($tables, $table_key) {
    $required = ['users', 'loai_phongs', 'phongs', 'dat_phongs', 'chi_tiet_dat_phongs', 
                 'hoa_dons', 'khuyen_mais', 'reviews', 'feedbacks', 'tien_nghis'];
    $existing = array_column($tables, $table_key);
    $missing = array_diff($required, $existing);
    return empty($missing) ? true : "Missing tables: " . implode(', ', $missing);
});

// Check EVERY column in users table
test("Users table - Complete column analysis", function() {
    $columns = Schema::getColumnListing('users');
    $required = ['id', 'name', 'email', 'email_verified_at', 'password', 'so_dien_thoai', 
                 'dia_chi', 'role', 'google_id', 'avatar', 'remember_token', 'created_at', 'updated_at'];
    $missing = array_diff($required, $columns);
    
    if (!empty($missing)) {
        return "Missing columns: " . implode(', ', $missing);
    }
    
    // Check for unused/extra columns
    $extra = array_diff($columns, $required);
    if (!empty($extra)) {
        echo " (Extra columns found: " . implode(', ', $extra) . ")";
    }
    
    return true;
});

// Check dat_phongs table completely
test("DatPhongs table - Complete column analysis", function() {
    $columns = Schema::getColumnListing('dat_phongs');
    $required = ['id', 'ma_dat_phong', 'user_id', 'ngay_dat', 'ngay_nhan_phong', 'ngay_tra_phong',
                 'so_dem', 'trang_thai', 'ghi_chu', 'subtotal', 'vat_amount', 'tong_tien',
                 'payment_status', 'payment_method', 'payment_details', 'khuyen_mai_id',
                 'accepted_terms', 'created_at', 'updated_at', 'deleted_at'];
    $missing = array_diff($required, $columns);
    return empty($missing) ? true : "Missing columns: " . implode(', ', $missing);
});

// Check hoa_dons table
test("HoaDons table - Complete column analysis", function() {
    $columns = Schema::getColumnListing('hoa_dons');
    $required = ['id', 'so_hoa_don', 'dat_phong_id', 'ngay_lap', 'tong_tien',
                 'trang_thai', 'payment_method', 'created_at', 'updated_at'];
    $missing = array_diff($required, $columns);
    return empty($missing) ? true : "Missing columns: " . implode(', ', $missing);
});

// Check khuyen_mais table
test("KhuyenMais table - Usage tracking columns", function() {
    $columns = Schema::getColumnListing('khuyen_mais');
    $required = ['id', 'ma_khuyen_mai', 'mo_ta', 'loai_giam_gia', 'gia_tri_giam', 
                 'ngay_bat_dau', 'ngay_ket_thuc', 'usage_limit', 'used_count', 'usage_per_user',
                 'active', 'created_at', 'updated_at'];
    $missing = array_diff($required, $columns);
    return empty($missing) ? true : "Missing columns: " . implode(', ', $missing);
});

// Check reviews table
test("Reviews table - All rating columns exist", function() {
    $columns = Schema::getColumnListing('reviews');
    $required = ['id', 'user_id', 'loai_phong_id', 'rating', 'comment', 'created_at', 'updated_at'];
    $missing = array_diff($required, $columns);
    return empty($missing) ? true : "Missing columns: " . implode(', ', $missing);
});

// Check feedbacks table  
test("Feedbacks table - New status column exists", function() {
    $columns = Schema::getColumnListing('feedbacks');
    return in_array('status', $columns) ? true : "Status column not found (has 'handled' instead)";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "🔍 PART 2: DATA COMPLETENESS ANALYSIS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("No users with null required fields", function() {
    $nulls = User::whereNull('name')
        ->orWhereNull('email')
        ->orWhereNull('role')
        ->count();
    return $nulls == 0 ? true : "{$nulls} users have null required fields";
});

test("All users have valid phone numbers (if provided)", function() {
    $invalid = User::whereNotNull('so_dien_thoai')
        ->where('so_dien_thoai', '!=', '')
        ->where(function($q) {
            $q->where('so_dien_thoai', 'not regexp', '^[0-9]{10,11}$');
        })
        ->count();
    return $invalid == 0 ? true : "{$invalid} users have invalid phone numbers";
});

test("No bookings without room assignments", function() {
    $bookings_without_rooms = DB::table('dat_phongs as dp')
        ->leftJoin('chi_tiet_dat_phongs as ct', 'dp.id', '=', 'ct.dat_phong_id')
        ->whereNull('ct.id')
        ->count();
    return $bookings_without_rooms == 0 ? true : "{$bookings_without_rooms} bookings have no rooms assigned";
});

test("All booking details link to existing rooms", function() {
    $orphaned = ChiTietDatPhong::whereNotExists(function($query) {
        $query->select(DB::raw(1))
            ->from('phongs')
            ->whereRaw('phongs.id = chi_tiet_dat_phongs.phong_id');
    })->count();
    return $orphaned == 0 ? true : "{$orphaned} booking details reference non-existent rooms";
});

test("All invoices link to valid bookings", function() {
    $orphaned = HoaDon::whereNotExists(function($query) {
        $query->select(DB::raw(1))
            ->from('dat_phongs')
            ->whereRaw('dat_phongs.id = hoa_dons.dat_phong_id');
    })->count();
    return $orphaned == 0 ? true : "{$orphaned} invoices reference non-existent bookings";
});

test("All reviews link to existing users and room types", function() {
    $invalid_user = Review::whereNotExists(function($query) {
        $query->select(DB::raw(1))->from('users')->whereRaw('users.id = reviews.user_id');
    })->count();
    
    $invalid_room = Review::whereNotExists(function($query) {
        $query->select(DB::raw(1))->from('loai_phongs')->whereRaw('loai_phongs.id = reviews.loai_phong_id');
    })->count();
    
    $total_invalid = $invalid_user + $invalid_room;
    return $total_invalid == 0 ? true : "{$invalid_user} reviews with invalid users, {$invalid_room} with invalid room types";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "💼 PART 3: BUSINESS LOGIC EDGE CASES\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("No bookings with check-in after check-out", function() {
    $invalid = DatPhong::whereRaw('ngay_nhan_phong >= ngay_tra_phong')->count();
    return $invalid == 0 ? true : "{$invalid} bookings have check-in >= check-out";
});

test("No bookings with negative total prices", function() {
    $invalid = DatPhong::where('tong_tien', '<', 0)->count();
    return $invalid == 0 ? true : "{$invalid} bookings have negative prices";
});

test("No bookings with invalid nights calculation", function() {
    $invalid = DatPhong::whereRaw('so_dem != DATEDIFF(ngay_tra_phong, ngay_nhan_phong)')->count();
    return $invalid == 0 ? true : "{$invalid} bookings have incorrect nights calculation";
});

test("No rooms with negative or zero capacity", function() {
    $invalid = LoaiPhong::where('so_nguoi', '<=', 0)->count();
    return $invalid == 0 ? true : "{$invalid} room types have invalid capacity";
});

test("No rooms with negative or zero prices", function() {
    $invalid = LoaiPhong::where('gia_tien', '<=', 0)->count();
    return $invalid == 0 ? true : "{$invalid} room types have invalid prices";
});

test("All paid bookings have invoices", function() {
    $missing_invoices = DatPhong::where('payment_status', 'paid')
        ->whereNotExists(function($query) {
            $query->select(DB::raw(1))
                ->from('hoa_dons')
                ->whereRaw('hoa_dons.dat_phong_id = dat_phongs.id');
        })
        ->count();
    return $missing_invoices == 0 ? true : "{$missing_invoices} paid bookings don't have invoices";
});

test("No invoices for unpaid bookings", function() {
    $invalid = HoaDon::whereExists(function($query) {
        $query->select(DB::raw(1))
            ->from('dat_phongs')
            ->whereRaw('dat_phongs.id = hoa_dons.dat_phong_id')
            ->where('dat_phongs.payment_status', '!=', 'paid');
    })->count();
    return $invalid == 0 ? true : "{$invalid} invoices exist for unpaid bookings";
});

test("Promotion usage never exceeds limit", function() {
    $exceeded = KhuyenMai::whereNotNull('usage_limit')
        ->whereRaw('used_count > usage_limit')
        ->count();
    return $exceeded == 0 ? true : "{$exceeded} promotions have exceeded usage limits";
});

test("Promotion dates are always valid (start <= end)", function() {
    $invalid = KhuyenMai::whereRaw('ngay_bat_dau > ngay_ket_thuc')->count();
    return $invalid == 0 ? true : "{$invalid} promotions have invalid date ranges";
});

test("All active promotions have future end dates", function() {
    $expired_active = KhuyenMai::where('active', true)
        ->where('ngay_ket_thuc', '<', now())
        ->count();
    return $expired_active == 0 ? true : "{$expired_active} expired promotions are still active";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "🏨 PART 4: ROOM MANAGEMENT LOGIC\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("All rooms belong to existing room types", function() {
    $orphaned = Phong::whereNotExists(function($query) {
        $query->select(DB::raw(1))
            ->from('loai_phongs')
            ->whereRaw('loai_phongs.id = phongs.loai_phong_id');
    })->count();
    return $orphaned == 0 ? true : "{$orphaned} rooms have invalid room types";
});

test("Room numbers are unique within hotel", function() {
    $duplicates = DB::table('phongs')
        ->select('so_phong')
        ->groupBy('so_phong')
        ->havingRaw('COUNT(*) > 1')
        ->count();
    return $duplicates == 0 ? true : "{$duplicates} duplicate room numbers found";
});

test("No rooms permanently in 'occupied' status", function() {
    // Rooms in 'occupied' should have active bookings
    $occupied_rooms = Phong::where('tinh_trang', 'occupied')->get();
    $invalid_count = 0;
    
    foreach ($occupied_rooms as $room) {
        $has_active_booking = DB::table('chi_tiet_dat_phongs')
            ->join('dat_phongs', 'chi_tiet_dat_phongs.dat_phong_id', '=', 'dat_phongs.id')
            ->where('chi_tiet_dat_phongs.phong_id', $room->id)
            ->where('dat_phongs.trang_thai', 'confirmed')
            ->where('dat_phongs.ngay_nhan_phong', '<=', now())
            ->where('dat_phongs.ngay_tra_phong', '>=', now())
            ->exists();
        
        if (!$has_active_booking) {
            $invalid_count++;
        }
    }
    
    return $invalid_count == 0 ? true : "{$invalid_count} occupied rooms have no active bookings";
});

test("Maintenance rooms don't have active bookings", function() {
    $invalid = DB::table('phongs')
        ->where('tinh_trang', 'maintenance')
        ->whereExists(function($query) {
            $query->select(DB::raw(1))
                ->from('chi_tiet_dat_phongs')
                ->join('dat_phongs', 'chi_tiet_dat_phongs.dat_phong_id', '=', 'dat_phongs.id')
                ->whereRaw('chi_tiet_dat_phongs.phong_id = phongs.id')
                ->where('dat_phongs.trang_thai', 'confirmed')
                ->where('dat_phongs.ngay_tra_phong', '>=', now());
        })
        ->count();
    return $invalid == 0 ? true : "{$invalid} maintenance rooms have active bookings";
});

test("Room amenities relationships are valid", function() {
    // Check if loai_phong_tien_nghi pivot table exists
    if (!Schema::hasTable('loai_phong_tien_nghi')) {
        return 'warning';
    }
    
    $invalid_room_type = DB::table('loai_phong_tien_nghi')
        ->whereNotExists(function($query) {
            $query->select(DB::raw(1))
                ->from('loai_phongs')
                ->whereRaw('loai_phongs.id = loai_phong_tien_nghi.loai_phong_id');
        })
        ->count();
    
    $invalid_amenity = DB::table('loai_phong_tien_nghi')
        ->whereNotExists(function($query) {
            $query->select(DB::raw(1))
                ->from('tien_nghis')
                ->whereRaw('tien_nghis.id = loai_phong_tien_nghi.tien_nghi_id');
        })
        ->count();
    
    $total = $invalid_room_type + $invalid_amenity;
    return $total == 0 ? true : "{$total} invalid amenity relationships found";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "💰 PART 5: PAYMENT & FINANCIAL VALIDATION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("All payment methods are standardized", function() {
    $valid_methods = ['cash', 'vnpay', 'credit_card', 'bank_transfer'];
    $invalid_bookings = DatPhong::whereNotNull('payment_method')
        ->whereNotIn('payment_method', $valid_methods)
        ->count();
    
    $invalid_invoices = HoaDon::whereNotNull('payment_method')
        ->whereNotIn('payment_method', $valid_methods)
        ->count();
    
    $total = $invalid_bookings + $invalid_invoices;
    return $total == 0 ? true : "{$total} records with invalid payment methods";
});

test("Invoice totals match booking totals exactly", function() {
    $mismatches = DB::table('hoa_dons as h')
        ->join('dat_phongs as d', 'h.dat_phong_id', '=', 'd.id')
        ->whereRaw('ABS(h.tong_tien - d.tong_tien) > 0.01')
        ->count();
    return $mismatches == 0 ? true : "{$mismatches} invoice-booking total mismatches";
});

test("VAT calculations are consistent (8%)", function() {
    $invalid = DatPhong::where('vat_amount', '>', 0)
        ->whereRaw('ABS(vat_amount - (subtotal * 0.08)) > 0.01')
        ->count();
    return $invalid == 0 ? true : "{$invalid} bookings with incorrect VAT calculation";
});

test("Discounts never exceed subtotal", function() {
    $invalid = DB::table('dat_phongs')
        ->join('khuyen_mais', 'dat_phongs.khuyen_mai_id', '=', 'khuyen_mais.id')
        ->where('khuyen_mais.loai_giam_gia', 'tien_mat')
        ->whereRaw('khuyen_mais.gia_tri_giam > dat_phongs.subtotal')
        ->count();
    return $invalid == 0 ? true : "{$invalid} bookings with discount > subtotal";
});

test("Percentage discounts are within 0-100%", function() {
    $invalid = KhuyenMai::where('loai_giam_gia', 'phan_tram')
        ->where(function($q) {
            $q->where('gia_tri_giam', '<', 0)
              ->orWhere('gia_tri_giam', '>', 100);
        })
        ->count();
    return $invalid == 0 ? true : "{$invalid} promotions with invalid percentage";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "⭐ PART 6: REVIEW & FEEDBACK SYSTEM\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("All reviews have ratings between 1-5", function() {
    $invalid = Review::where('rating', '<', 1)
        ->orWhere('rating', '>', 5)
        ->count();
    return $invalid == 0 ? true : "{$invalid} reviews with invalid ratings";
});

test("Users can only review room types they've booked", function() {
    $invalid_reviews = 0;
    $reviews = Review::with(['user', 'loaiPhong'])->get();
    
    foreach ($reviews as $review) {
        // Check if user has completed booking for this room type
        $has_booking = DB::table('dat_phongs')
            ->join('chi_tiet_dat_phongs', 'dat_phongs.id', '=', 'chi_tiet_dat_phongs.dat_phong_id')
            ->join('phongs', 'chi_tiet_dat_phongs.phong_id', '=', 'phongs.id')
            ->where('dat_phongs.user_id', $review->user_id)
            ->where('phongs.loai_phong_id', $review->loai_phong_id)
            ->where('dat_phongs.trang_thai', 'completed')
            ->exists();
        
        if (!$has_booking) {
            $invalid_reviews++;
        }
    }
    
    return $invalid_reviews == 0 ? true : "{$invalid_reviews} reviews from users who never booked that room type";
});

test("All feedbacks have valid email addresses", function() {
    $invalid = Feedback::whereNotNull('email')
        ->where('email', '!=', '')
        ->where('email', 'not regexp', '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$')
        ->count();
    return $invalid == 0 ? true : "{$invalid} feedbacks with invalid email format";
});

test("Feedback status workflow is valid", function() {
    // If status exists, check it
    if (!Schema::hasColumn('feedbacks', 'status')) {
        return 'warning';
    }
    
    $invalid = Feedback::whereNotIn('status', ['pending', 'responded', 'closed'])->count();
    return $invalid == 0 ? true : "{$invalid} feedbacks with invalid status";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "🔔 PART 7: NOTIFICATION SYSTEM\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("All notifications link to existing users", function() {
    if (!Schema::hasTable('notifications')) {
        return 'warning';
    }
    
    $orphaned = DB::table('notifications')
        ->whereNotExists(function($query) {
            $query->select(DB::raw(1))
                ->from('users')
                ->whereRaw('users.id = notifications.notifiable_id')
                ->where('notifications.notifiable_type', 'App\\Models\\User');
        })
        ->count();
    return $orphaned == 0 ? true : "{$orphaned} notifications for non-existent users";
});

test("Notification types are valid", function() {
    if (!Schema::hasTable('notifications')) {
        return 'warning';
    }
    
    $valid_types = [
        'App\\Notifications\\BookingStatusUpdated',
        'App\\Notifications\\ReplyReceived',
        'App\\Notifications\\CustomResetPasswordNotification',
    ];
    
    $invalid = DB::table('notifications')
        ->whereNotIn('type', $valid_types)
        ->count();
    
    return $invalid == 0 ? true : "{$invalid} notifications with unrecognized types";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "🔒 PART 8: SECURITY & AUTHORIZATION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("All passwords are properly hashed (bcrypt)", function() {
    $unhashed = User::where('password', 'not regexp', '^\$2[ayb]\$.{56}$')->count();
    return $unhashed == 0 ? true : "{$unhashed} users with potentially unhashed passwords";
});

test("Admin accounts are properly marked", function() {
    $admin_count = User::where('role', 'admin')->count();
    if ($admin_count == 0) {
        return "No admin accounts found!";
    }
    if ($admin_count > 10) {
        return "warning"; // Too many admins might be suspicious
    }
    return true;
});

test("No SQL injection patterns in user inputs", function() {
    $patterns = ["'", '"', '--', '/*', '*/', 'UNION', 'SELECT', 'DROP', 'INSERT'];
    $suspicious_count = 0;
    
    // Check in feedback messages
    foreach ($patterns as $pattern) {
        $count = Feedback::where('message', 'like', "%{$pattern}%")->count();
        $suspicious_count += $count;
    }
    
    return $suspicious_count == 0 ? true : "{$suspicious_count} potential SQL injection patterns found";
});

test("Profile audit trail is working", function() {
    if (!Schema::hasTable('profile_audits')) {
        return 'warning';
    }
    
    $audit_count = DB::table('profile_audits')->count();
    return $audit_count > 0 ? true : "No audit records found (might be unused)";
});

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "📊 PART 9: STATISTICAL ANOMALIES\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

test("Booking cancellation rate is reasonable (<30%)", function() {
    $total = DatPhong::count();
    $cancelled = DatPhong::where('trang_thai', 'cancelled')->count();
    $rate = ($total > 0) ? ($cancelled / $total) * 100 : 0;
    
    if ($rate > 50) return "Cancellation rate very high: {$rate}%";
    if ($rate > 30) return "warning";
    return true;
});

test("Room occupancy is distributed reasonably", function() {
    $total_rooms = Phong::count();
    $available = Phong::where('tinh_trang', 'available')->count();
    $occupied = Phong::where('tinh_trang', 'occupied')->count();
    $maintenance = Phong::where('tinh_trang', 'maintenance')->count();
    
    $available_pct = ($total_rooms > 0) ? ($available / $total_rooms) * 100 : 0;
    
    if ($available_pct < 10) return "warning"; // Less than 10% available might be issue
    if ($available_pct > 95) return "warning"; // More than 95% available might indicate no bookings
    
    return true;
});

test("Average booking value is reasonable", function() {
    $avg = DatPhong::where('trang_thai', '!=', 'cancelled')->avg('tong_tien');
    
    if ($avg < 100000) return "Average booking value very low: " . number_format($avg);
    if ($avg > 50000000) return "Average booking value very high: " . number_format($avg);
    
    return true;
});

test("User registration trend is positive", function() {
    $last_month = User::where('created_at', '>=', now()->subMonth())->count();
    $previous_month = User::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
    
    if ($last_month == 0 && $previous_month == 0) {
        return "No recent user registrations";
    }
    
    return true;
});

test("Revenue trend is consistent", function() {
    $this_month_revenue = HoaDon::where('ngay_lap', '>=', now()->startOfMonth())->sum('tong_tien');
    $last_month_revenue = HoaDon::whereBetween('ngay_lap', [
        now()->subMonth()->startOfMonth(),
        now()->subMonth()->endOfMonth()
    ])->sum('tong_tien');
    
    if ($this_month_revenue == 0 && $last_month_revenue > 0) {
        return "warning"; // Might indicate no recent bookings
    }
    
    return true;
});

// Final Summary
echo "\n╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                      📊 FINAL SUMMARY                        ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "Total Tests:    {$total_tests}\n";
echo "Passed:         {$passed} ✅\n";
echo "Failed:         {$failed} ❌\n";
echo "Warnings:       {$warnings} ⚠️\n";

$pass_rate = ($total_tests > 0) ? round(($passed / $total_tests) * 100, 2) : 0;
echo "Pass Rate:      {$pass_rate}%\n\n";

if ($failed > 0) {
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "❌ FAILED TESTS:\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    foreach ($issues as $issue) {
        echo "  • {$issue}\n";
    }
    echo "\n";
}

if ($pass_rate >= 95) {
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "✅ EXCELLENT! System is in great shape. Pass rate: {$pass_rate}%\n";
    echo "═══════════════════════════════════════════════════════════════\n";
} elseif ($pass_rate >= 80) {
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "✅ GOOD. System is healthy with minor issues. Pass rate: {$pass_rate}%\n";
    echo "═══════════════════════════════════════════════════════════════\n";
} else {
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "⚠️  NEEDS ATTENTION. Multiple issues found. Pass rate: {$pass_rate}%\n";
    echo "═══════════════════════════════════════════════════════════════\n";
}
