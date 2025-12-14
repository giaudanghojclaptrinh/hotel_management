<?php

/**
 * 🔎 INVESTIGATE REMAINING ISSUES
 * 
 * Deep dive into the 17 failed tests from deep_system_analysis.php
 * Provide detailed information and recommended fixes
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║  🔎 INVESTIGATING REMAINING ISSUES                   ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

// ISSUE #1: Paid bookings without invoices
echo "═══════════════════════════════════════════════════════════════\n";
echo "ISSUE #1: Paid bookings don't have invoices\n";
echo "═══════════════════════════════════════════════════════════════\n";

$paid_no_invoice = DB::table('dat_phongs')
    ->leftJoin('hoa_dons', 'dat_phongs.id', '=', 'hoa_dons.dat_phong_id')
    ->where('dat_phongs.payment_status', 'paid')
    ->whereNull('hoa_dons.id')
    ->select('dat_phongs.*')
    ->get();

echo "Found: " . $paid_no_invoice->count() . " paid bookings without invoices\n\n";
foreach ($paid_no_invoice as $booking) {
    echo "  Booking ID: {$booking->id}\n";
    echo "  User ID: {$booking->user_id}\n";
    echo "  Check-in: {$booking->ngay_den}\n";
    echo "  Check-out: {$booking->ngay_di}\n";
    echo "  Total: ₫" . number_format($booking->tong_tien) . "\n";
    echo "  Payment Status: {$booking->payment_status}\n";
    echo "  Payment Method: {$booking->payment_method}\n";
    echo "  Created: {$booking->created_at}\n";
    echo "  ---\n";
}

echo "\n💡 RECOMMENDATION:\n";
echo "  Create invoices for these paid bookings using:\n";
echo "  php bootstrap/create_missing_invoices.php\n\n";

// ISSUE #2: Unpaid bookings WITH invoices
echo "═══════════════════════════════════════════════════════════════\n";
echo "ISSUE #2: Invoices exist for unpaid bookings\n";
echo "═══════════════════════════════════════════════════════════════\n";

$unpaid_with_invoice = DB::table('hoa_dons')
    ->join('dat_phongs', 'hoa_dons.dat_phong_id', '=', 'dat_phongs.id')
    ->where('dat_phongs.payment_status', '!=', 'paid')
    ->select('hoa_dons.*', 'dat_phongs.payment_status', 'dat_phongs.trang_thai')
    ->get();

echo "Found: " . $unpaid_with_invoice->count() . " invoices for unpaid bookings\n\n";
foreach ($unpaid_with_invoice as $invoice) {
    echo "  Invoice ID: {$invoice->id}\n";
    echo "  Invoice Number: {$invoice->ma_hoa_don}\n";
    echo "  Booking ID: {$invoice->dat_phong_id}\n";
    echo "  Invoice Total: ₫" . number_format($invoice->tong_tien) . "\n";
    echo "  Invoice Status: {$invoice->trang_thai}\n";
    echo "  Booking Payment Status: {$invoice->payment_status}\n";
    echo "  Booking Status: {$invoice->trang_thai}\n";
    echo "  ---\n";
}

echo "\n💡 RECOMMENDATION:\n";
echo "  Either:\n";
echo "  1. Delete these invoices (if bookings are actually unpaid)\n";
echo "  2. Update booking payment_status to 'paid' (if invoices are valid)\n\n";

// ISSUE #3: Reviews from users who never booked that room type
echo "═══════════════════════════════════════════════════════════════\n";
echo "ISSUE #3: Reviews from users who never booked that room type\n";
echo "═══════════════════════════════════════════════════════════════\n";

$invalid_reviews = DB::select("
    SELECT r.*, u.name as user_name, lp.ten_loai_phong
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN loai_phongs lp ON r.loai_phong_id = lp.id
    WHERE NOT EXISTS (
        SELECT 1 FROM dat_phongs dp
        JOIN chi_tiet_dat_phongs ct ON dp.id = ct.dat_phong_id
        JOIN phongs p ON ct.phong_id = p.id
        WHERE dp.user_id = r.user_id
        AND p.loai_phong_id = r.loai_phong_id
        AND dp.trang_thai = 'completed'
    )
");

echo "Found: " . count($invalid_reviews) . " invalid reviews\n\n";
foreach ($invalid_reviews as $review) {
    echo "  Review ID: {$review->id}\n";
    echo "  User: {$review->user_name} (ID: {$review->user_id})\n";
    echo "  Room Type: {$review->ten_loai_phong} (ID: {$review->loai_phong_id})\n";
    echo "  Rating: {$review->rating}/5\n";
    echo "  Comment: " . substr($review->comment, 0, 50) . "...\n";
    echo "  ---\n";
}

echo "\n💡 RECOMMENDATION:\n";
echo "  Either:\n";
echo "  1. Delete these invalid reviews\n";
echo "  2. Allow reviews without completed bookings (change business rule)\n";
echo "  3. Keep for testing purposes (test data)\n\n";

// ISSUE #4: Notification types
echo "═══════════════════════════════════════════════════════════════\n";
echo "ISSUE #4: Notifications with unrecognized types\n";
echo "═══════════════════════════════════════════════════════════════\n";

$valid_types = [
    'App\\Notifications\\BookingStatusUpdated',
    'App\\Notifications\\ReplyReceived',
    'App\\Notifications\\CustomResetPasswordNotification',
];

$invalid_notifications = DB::table('notifications')
    ->whereNotIn('type', $valid_types)
    ->get();

echo "Found: " . $invalid_notifications->count() . " notifications with unrecognized types\n\n";
foreach ($invalid_notifications as $notif) {
    echo "  Notification ID: {$notif->id}\n";
    echo "  Type: {$notif->type}\n";
    echo "  User ID: {$notif->notifiable_id}\n";
    echo "  Read: " . ($notif->read_at ? 'Yes' : 'No') . "\n";
    echo "  Created: {$notif->created_at}\n";
    echo "  ---\n";
}

echo "\n💡 RECOMMENDATION:\n";
echo "  Either:\n";
echo "  1. Add new notification types to test validation list\n";
echo "  2. Delete old/invalid notifications\n";
echo "  3. Update test script with all valid notification classes\n\n";

// Get statistics
echo "═══════════════════════════════════════════════════════════════\n";
echo "📊 OVERALL STATISTICS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$stats = [
    'Total Bookings' => DB::table('dat_phongs')->count(),
    'Paid Bookings' => DB::table('dat_phongs')->where('payment_status', 'paid')->count(),
    'Unpaid Bookings' => DB::table('dat_phongs')->where('payment_status', 'unpaid')->count(),
    'Total Invoices' => DB::table('hoa_dons')->count(),
    'Total Reviews' => DB::table('reviews')->count(),
    'Total Notifications' => DB::table('notifications')->count(),
];

foreach ($stats as $label => $value) {
    echo str_pad($label . ':', 25) . $value . "\n";
}

echo "\n";
echo "Paid Bookings Without Invoices: " . $paid_no_invoice->count() . "\n";
echo "Unpaid Bookings With Invoices:  " . $unpaid_with_invoice->count() . "\n";
echo "Invalid Reviews:                " . count($invalid_reviews) . "\n";
echo "Invalid Notifications:          " . $invalid_notifications->count() . "\n";

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "✅ INVESTIGATION COMPLETE\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\nNext steps:\n";
echo "1. Review findings above\n";
echo "2. Run fix scripts for each issue\n";
echo "3. Re-test with deep_system_analysis.php\n\n";
