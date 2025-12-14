<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Phong;
use App\Models\KhuyenMai;
use App\Models\DatPhong;
use App\Models\Review;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

echo "🔍 INVESTIGATING DATA ISSUES\n\n";

// 1. Rooms with invalid status
echo "1. Rooms with invalid status:\n";
$rooms = Phong::whereNotIn('tinh_trang', ['available', 'occupied', 'maintenance', 'cleaning'])->get();
foreach ($rooms as $r) {
    echo "  - Room {$r->so_phong} (ID: {$r->id}): status='{$r->tinh_trang}'\n";
}

// 2. Promotions with invalid dates
echo "\n2. Promotions with invalid dates:\n";
$promos = KhuyenMai::whereRaw('ngay_bat_dau > ngay_ket_thuc')->get();
foreach ($promos as $p) {
    echo "  - {$p->ma_khuyen_mai} (ID: {$p->id}): {$p->ngay_bat_dau} to {$p->ngay_ket_thuc}\n";
}

// 3. Bookings with invalid payment_status
echo "\n3. Bookings with invalid payment_status:\n";
$bookings = DatPhong::whereNotIn('payment_status', ['paid', 'unpaid', 'refunded'])->get();
foreach ($bookings as $b) {
    echo "  - Booking {$b->id}: payment_status='{$b->payment_status}'\n";
}

// 4. Booking with incorrect total
echo "\n4. Bookings with incorrect total (subtotal + VAT != total):\n";
$wrong_totals = DatPhong::whereRaw('ABS((subtotal + vat_amount) - tong_tien) > 0.1')->get();
foreach ($wrong_totals as $b) {
    $expected = $b->subtotal + $b->vat_amount;
    echo "  - Booking {$b->id}: Expected " . number_format($expected) . ", Got " . number_format($b->tong_tien) . "\n";
}

// 5. Double bookings
echo "\n5. Rooms with overlapping bookings:\n";
$overlaps = DB::select("
    SELECT p.id, p.so_phong, 
           d1.id as booking1, d1.ngay_den as d1_checkin, d1.ngay_di as d1_checkout,
           d2.id as booking2, d2.ngay_den as d2_checkin, d2.ngay_di as d2_checkout
    FROM phongs p
    JOIN chi_tiet_dat_phongs c1 ON p.id = c1.phong_id
    JOIN dat_phongs d1 ON c1.dat_phong_id = d1.id
    JOIN chi_tiet_dat_phongs c2 ON p.id = c2.phong_id
    JOIN dat_phongs d2 ON c2.dat_phong_id = d2.id
    WHERE d1.id < d2.id
    AND d1.trang_thai IN ('confirmed', 'pending', 'awaiting_payment')
    AND d2.trang_thai IN ('confirmed', 'pending', 'awaiting_payment')
    AND d1.ngay_den < d2.ngay_di
    AND d1.ngay_di > d2.ngay_den
    LIMIT 5
");
foreach ($overlaps as $o) {
    echo "  - Room {$o->so_phong}: Booking #{$o->booking1} ({$o->d1_checkin} to {$o->d1_checkout}) overlaps Booking #{$o->booking2} ({$o->d2_checkin} to {$o->d2_checkout})\n";
}

// 6. Reviews with invalid ratings
echo "\n6. Reviews with invalid ratings:\n";
$reviews = Review::where('rating', '<', 1)->orWhere('rating', '>', 5)->get();
foreach ($reviews as $r) {
    echo "  - Review {$r->id} by User {$r->user_id}: rating={$r->rating}\n";
}

// 7. Check feedbacks table structure
echo "\n7. Feedbacks table structure:\n";
$columns = DB::select("SHOW COLUMNS FROM feedbacks");
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}

echo "\n✅ Investigation complete!\n";
