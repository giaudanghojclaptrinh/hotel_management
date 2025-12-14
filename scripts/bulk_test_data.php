<?php
/**
 * BULK DATA TESTING SCRIPT
 * Purpose: Add lots of test data to verify system handles large datasets
 * Usage: php scripts/bulk_test_data.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\LoaiPhong;
use App\Models\TienNghi;
use App\Models\Phong;
use App\Models\KhuyenMai;
use App\Models\DatPhong;
use App\Models\ChiTietDatPhong;
use App\Models\HoaDon;
use App\Models\Review;
use App\Models\Feedback;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "\n=== BULK DATA TESTING SCRIPT ===\n";
echo "Starting bulk data insertion...\n\n";

DB::beginTransaction();

try {
    // ==========================================
    // 1. CREATE BULK USERS (20-30 users)
    // ==========================================
    echo "[1/8] Creating 25 users (20 clients + 5 admins)...\n";
    
    $users = [];
    for ($i = 1; $i <= 20; $i++) {
        $users[] = User::create([
            'name' => "Client User $i",
            'email' => "client{$i}@test.com",
            'password' => Hash::make('password123'),
            'sdt' => '090' . str_pad($i, 7, '0', STR_PAD_LEFT),
            'dia_chi' => "123 Nguyen Van Cu, District $i, HCMC",
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
    
    for ($i = 1; $i <= 5; $i++) {
        $users[] = User::create([
            'name' => "Admin User $i",
            'email' => "admin{$i}@test.com",
            'password' => Hash::make('admin123'),
            'sdt' => '091' . str_pad($i, 7, '0', STR_PAD_LEFT),
            'dia_chi' => "456 Le Loi, District $i, HCMC",
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
    echo "✓ Created " . count($users) . " users\n\n";

    // ==========================================
    // 2. CREATE BULK ROOM TYPES (10-15 types)
    // ==========================================
    echo "[2/8] Creating 12 room types...\n";
    
    $tienNghis = TienNghi::all();
    $roomTypeNames = [
        'Standard Single', 'Standard Double', 'Standard Twin',
        'Superior Single', 'Superior Double', 'Superior Twin',
        'Deluxe Single', 'Deluxe Double', 'Deluxe King',
        'Executive Suite', 'Presidential Suite', 'Family Suite'
    ];
    
    $loaiPhongs = [];
    foreach ($roomTypeNames as $index => $name) {
        $loaiPhong = LoaiPhong::create([
            'ten_loai_phong' => $name,
            'gia' => rand(50, 500) * 10000, // 500k - 5 million
            'so_nguoi' => rand(1, 4),
            'dien_tich' => rand(15, 80),
            'hinh_anh' => 'uploads/phongs/default.jpg',
        ]);
        
        // Attach random amenities (3-8 per room type)
        if ($tienNghis->count() > 0) {
            $randomAmenities = $tienNghis->random(rand(3, min(8, $tienNghis->count())))->pluck('id');
            $loaiPhong->tienNghis()->attach($randomAmenities);
        }
        
        $loaiPhongs[] = $loaiPhong;
    }
    echo "✓ Created " . count($loaiPhongs) . " room types\n\n";

    // ==========================================
    // 3. CREATE BULK PHYSICAL ROOMS (40-50 rooms)
    // ==========================================
    echo "[3/8] Creating 45 physical rooms...\n";
    
    $phongs = [];
    $statuses = ['available', 'occupied', 'cleaning', 'maintenance'];
    $roomNumber = 101;
    
    foreach ($loaiPhongs as $loaiPhong) {
        // Each room type gets 3-4 physical rooms
        $roomCount = rand(3, 4);
        for ($i = 0; $i < $roomCount; $i++) {
            $phongs[] = Phong::create([
                'loai_phong_id' => $loaiPhong->id,
                'so_phong' => (string)$roomNumber++,
                'tinh_trang' => $statuses[array_rand($statuses)],
            ]);
        }
    }
    echo "✓ Created " . count($phongs) . " physical rooms\n\n";

    // ==========================================
    // 4. CREATE BULK PROMOTIONS (15-20 promotions)
    // ==========================================
    echo "[4/8] Creating 18 promotions...\n";
    
    $promotionTypes = [
        ['name' => 'New Year 2025', 'percent' => 15],
        ['name' => 'Valentine Special', 'percent' => 20],
        ['name' => 'Summer Holiday', 'percent' => 25],
        ['name' => 'Black Friday', 'percent' => 30],
        ['name' => 'Early Bird Discount', 'percent' => 10],
        ['name' => 'Weekend Getaway', 'percent' => 12],
        ['name' => 'Long Stay Offer', 'percent' => 18],
        ['name' => 'Family Package', 'percent' => 22],
        ['name' => 'Business Traveler', 'percent' => 15],
        ['name' => 'Senior Citizen', 'percent' => 20],
        ['name' => 'Student Discount', 'percent' => 25],
        ['name' => 'Military Discount', 'percent' => 20],
        ['name' => 'Birthday Special', 'percent' => 30],
        ['name' => 'Anniversary Deal', 'percent' => 35],
        ['name' => 'Flash Sale', 'percent' => 40],
        ['name' => 'Loyalty Reward', 'percent' => 15],
        ['name' => 'Corporate Rate', 'percent' => 18],
        ['name' => 'Honeymoon Package', 'percent' => 25],
    ];
    
    $khuyenMais = [];
    foreach ($promotionTypes as $index => $promo) {
        $startDate = now()->addDays(rand(-30, 10));
        $khuyenMais[] = KhuyenMai::create([
            'ma_khuyen_mai' => 'PROMO' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
            'ten_khuyen_mai' => $promo['name'],
            'chiet_khau_phan_tram' => $promo['percent'],
            'so_tien_giam_gia' => 0, // Fixed: Database requires this field (no default value)
            'ngay_bat_dau' => $startDate,
            'ngay_ket_thuc' => $startDate->copy()->addDays(rand(15, 90)),
            'usage_limit' => rand(10, 100),
            'usage_per_user' => rand(1, 5),
            'trang_thai' => 'active',
        ]);
    }
    echo "✓ Created " . count($khuyenMais) . " promotions\n\n";

    // ==========================================
    // 5. CREATE BULK BOOKINGS (50-60 bookings)
    // ==========================================
    echo "[5/8] Creating 55 bookings...\n";
    
    $datPhongs = [];
    $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
    $clientUsers = array_slice($users, 0, 20); // Only client users
    
    for ($i = 0; $i < 55; $i++) {
        $user = $clientUsers[array_rand($clientUsers)];
        $checkin = now()->addDays(rand(-60, 30));
        $checkout = $checkin->copy()->addDays(rand(1, 7));
        $status = $statuses[array_rand($statuses)];
        
        // Random 2-4 rooms per booking
        $selectedPhongs = collect($phongs)->random(rand(2, 4));
        $tongTien = 0;
        
        foreach ($selectedPhongs as $phong) {
            $soNgay = max(1, $checkin->diffInDays($checkout));
            $tongTien += $phong->loaiPhong->gia * $soNgay;
        }
        
        // Apply random promotion (50% chance)
        $khuyenMai = (rand(0, 1) === 1 && count($khuyenMais) > 0) 
            ? $khuyenMais[array_rand($khuyenMais)] 
            : null;
        
        if ($khuyenMai) {
            $tongTien = $tongTien * (1 - $khuyenMai->chiet_khau_phan_tram / 100);
        }
        
        $datPhong = DatPhong::create([
            'user_id' => $user->id,
            'ngay_den' => $checkin,      // Fixed: Database column is 'ngay_den' not 'ngay_nhan_phong'
            'ngay_di' => $checkout,      // Fixed: Database column is 'ngay_di' not 'ngay_tra_phong'
            'tong_tien' => $tongTien,
            'khuyen_mai_id' => $khuyenMai ? $khuyenMai->id : null,
            'trang_thai' => $status,
            'created_at' => now()->subDays(rand(0, 60)),
        ]);
        
        // Create booking details
        foreach ($selectedPhongs as $phong) {
            $soNgay = max(1, $checkin->diffInDays($checkout));
            $donGia = $phong->loaiPhong->gia;
            $thanhTien = $donGia * $soNgay;
            
            ChiTietDatPhong::create([
                'dat_phong_id' => $datPhong->id,
                'phong_id' => $phong->id,
                'loai_phong_id' => $phong->loai_phong_id,
                'so_luong' => $soNgay,      // Fixed: Database uses 'so_luong' (quantity/days)
                'don_gia' => $donGia,       // Fixed: Database uses 'don_gia' (unit price)
                'thanh_tien' => $thanhTien, // Fixed: Database uses 'thanh_tien' (total)
            ]);
        }
        
        // Create invoice for confirmed/completed bookings
        if (in_array($status, ['confirmed', 'completed'])) {
            $maHoaDon = 'HD' . now()->format('Ymd') . str_pad($datPhong->id, 6, '0', STR_PAD_LEFT);
            $subtotal = $tongTien;
            $vatAmount = $subtotal * 0.08; // 8% VAT
            
            HoaDon::create([
                'dat_phong_id' => $datPhong->id,
                'ma_hoa_don' => $maHoaDon,
                'ngay_lap' => now(),
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'tong_tien' => $subtotal + $vatAmount,
                'phuong_thuc_thanh_toan' => ['VNPay', 'Cash', 'Bank Transfer'][array_rand(['VNPay', 'Cash', 'Bank Transfer'])],
                'trang_thai' => 'paid',
            ]);
        }
        
        $datPhongs[] = $datPhong;
    }
    echo "✓ Created " . count($datPhongs) . " bookings\n\n";

    // ==========================================
    // 6. CREATE BULK REVIEWS (40-50 reviews)
    // ==========================================
    echo "[6/8] Creating 45 reviews...\n";
    
    $completedBookings = DatPhong::where('trang_thai', 'completed')->take(45)->get();
    $reviewCount = 0;
    
    foreach ($completedBookings as $booking) {
        // Create rating (one per user per room type)
        foreach ($booking->chiTietDatPhongs as $chiTiet) {
            // Check if rating exists
            $existingRating = Review::where('user_id', $booking->user_id)
                ->where('loai_phong_id', $chiTiet->loai_phong_id)
                ->whereNotNull('rating')  // Fixed: Database uses 'rating' not 'so_sao'
                ->first();
            
            if (!$existingRating) {
                Review::create([
                    'user_id' => $booking->user_id,
                    'loai_phong_id' => $chiTiet->loai_phong_id,
                    'rating' => rand(3, 5),    // Fixed: Database uses 'rating'
                    'comment' => null,          // Fixed: Database uses 'comment'
                ]);
                $reviewCount++;
            }
            
            // Create comment (50% chance, multiple allowed)
            if (rand(0, 1) === 1) {
                $comments = [
                    'Great stay! Very comfortable and clean.',
                    'Staff was very friendly and helpful.',
                    'Room was spacious and well-maintained.',
                    'Excellent value for money.',
                    'Would definitely recommend this hotel.',
                    'Beautiful view from the room.',
                    'Breakfast was delicious.',
                    'Location is perfect for exploring the city.',
                ];
                
                Review::create([
                    'user_id' => $booking->user_id,
                    'loai_phong_id' => $chiTiet->loai_phong_id,
                    'rating' => null,          // Fixed: Null for comment-only reviews
                    'comment' => $comments[array_rand($comments)],  // Fixed: Database uses 'comment'
                ]);
                $reviewCount++;
            }
        }
    }
    echo "✓ Created $reviewCount reviews (ratings + comments)\n\n";

    // ==========================================
    // 7. CREATE BULK FEEDBACK (25-30 feedback)
    // ==========================================
    echo "[7/8] Creating 28 feedback entries...\n";
    
    $feedbackTopics = [
        'Booking Process', 'Room Quality', 'Staff Service', 'Cleanliness',
        'Food & Beverage', 'Facilities', 'Location', 'Value for Money',
        'Noise Level', 'WiFi Quality', 'Parking', 'Check-in/Check-out'
    ];
    
    $feedbackMessages = [
        'The booking process was very smooth and easy to use.',
        'I had some issues with the room temperature control.',
        'Staff was extremely helpful during my stay.',
        'Room could be cleaner, found some dust.',
        'Breakfast buffet had great variety and quality.',
        'Gym facilities are well-maintained.',
        'Location is convenient with easy access to transportation.',
        'Great value for the price paid.',
        'Some noise from the street at night.',
        'WiFi connection was fast and stable.',
        'Parking space is limited during peak hours.',
        'Check-in was quick and efficient.',
    ];
    
    for ($i = 0; $i < 28; $i++) {
        Feedback::create([
            'user_id' => $clientUsers[array_rand($clientUsers)]->id,
            'name' => 'Test User ' . ($i + 1),            // Fixed: Database uses 'name' not 'ho_ten'
            'email' => "feedback{$i}@test.com",
            'message' => $feedbackMessages[array_rand($feedbackMessages)],  // Fixed: Database uses 'message' not 'noi_dung'
            'handled' => (rand(0, 1) === 1),             // Fixed: Database uses boolean 'handled' not 'trang_thai'
            'created_at' => now()->subDays(rand(0, 30)),
        ]);
    }
    echo "✓ Created 28 feedback entries\n\n";

    // ==========================================
    // 8. SUMMARY
    // ==========================================
    echo "[8/8] Summary of created data:\n";
    echo "--------------------------------\n";
    echo "Users:          " . count($users) . " (20 clients + 5 admins)\n";
    echo "Room Types:     " . count($loaiPhongs) . "\n";
    echo "Physical Rooms: " . count($phongs) . "\n";
    echo "Promotions:     " . count($khuyenMais) . "\n";
    echo "Bookings:       " . count($datPhongs) . "\n";
    echo "Reviews:        $reviewCount (ratings + comments)\n";
    echo "Feedback:       28\n";
    echo "--------------------------------\n";

    DB::commit();
    echo "\n✓ SUCCESS! All data committed to database.\n";
    echo "You can now test the system with bulk data!\n\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nAll changes rolled back.\n\n";
}
