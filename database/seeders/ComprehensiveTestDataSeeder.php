<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\LoaiPhong;
use App\Models\Phong;
use App\Models\KhuyenMai;
use App\Models\DatPhong;
use App\Models\ChiTietDatPhong;
use App\Models\HoaDon;
use App\Models\Review;
use App\Models\Feedback;
use App\Models\TienNghi;

class ComprehensiveTestDataSeeder extends Seeder
{
    private $images = [
        '1765392691_standard.png',
        '1765392704_Deluxe.png',
        '1765392715_suiteGiaDinh.png',
        '1765392724_LuxuryBienCa.png',
    ];

    public function run()
    {
        echo "🚀 BẮT ĐẦU SEED DỮ LIỆU COMPREHENSIVE TEST\n";
        echo "=========================================\n\n";

        DB::beginTransaction();
        try {
            // 1. Tạo nhiều users (khách hàng)
            $this->seedUsers();
            
            // 2. Cập nhật/tạo loại phòng với mô tả chi tiết
            $this->seedRoomTypes();
            
            // 3. Tạo nhiều phòng vật lý
            $this->seedRooms();
            
            // 4. Tạo mã khuyến mãi đa dạng
            $this->seedPromotions();
            
            // 5. Tạo booking history (đã hoàn thành)
            $this->seedCompletedBookings();
            
            // 6. Tạo booking hiện tại (pending, confirmed)
            $this->seedActiveBookings();

            DB::commit();
            
            echo "\n✅ HOÀN TẤT SEED DỮ LIỆU!\n";
            echo "=========================================\n";
            $this->printSummary();
            
        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ LỖI: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString() . "\n";
        }
    }

    private function seedUsers()
    {
        echo "👥 Tạo Users (Khách hàng)...\n";
        
        $users = [
            [
                'name' => 'Nguyễn Văn An',
                'email' => 'nguyenvanan@gmail.com',
                'phone' => '0901234567',
            ],
            [
                'name' => 'Trần Thị Bình',
                'email' => 'tranbinhtt@gmail.com',
                'phone' => '0912345678',
            ],
            [
                'name' => 'Lê Minh Châu',
                'email' => 'leminhchau@gmail.com',
                'phone' => '0923456789',
            ],
            [
                'name' => 'Phạm Hoài Dung',
                'email' => 'phamhoadung@gmail.com',
                'phone' => '0934567890',
            ],
            [
                'name' => 'Hoàng Văn Em',
                'email' => 'hoangvanem@gmail.com',
                'phone' => '0945678901',
            ],
            [
                'name' => 'Đỗ Thị Phương',
                'email' => 'dothiphuong@gmail.com',
                'phone' => '0956789012',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'role' => 'user',
                ])
            );
            echo "  ✓ {$user->name} ({$user->email})\n";
        }
        
        echo "\n";
    }

    private function seedRoomTypes()
    {
        echo "🏨 Cập nhật Loại Phòng với mô tả chi tiết...\n";
        
        $roomTypes = [
            [
                'ten_loai_phong' => 'Phòng Standard',
                'gia' => 500000,
                'so_nguoi' => 2,
                'dien_tich' => 25,
                'tien_nghi' => 'WiFi, Điều hòa, TV LCD 32", Tủ lạnh mini, Bàn làm việc',
                'hinh_anh' => 'uploads/phongs/' . $this->images[0],
            ],
            [
                'ten_loai_phong' => 'Phòng Deluxe',
                'gia' => 800000,
                'so_nguoi' => 2,
                'dien_tich' => 35,
                'tien_nghi' => 'WiFi, Điều hòa, TV LCD 43", Sofa, Ban công view biển, Bồn tắm, Minibar',
                'hinh_anh' => 'uploads/phongs/' . $this->images[1],
            ],
            [
                'ten_loai_phong' => 'Suite Gia Đình',
                'gia' => 1500000,
                'so_nguoi' => 4,
                'dien_tich' => 55,
                'tien_nghi' => 'WiFi, Điều hòa, 2 Phòng ngủ, Phòng khách 20m², Bếp nhỏ, 2 Nhà tắm, TV 55", PlayStation 5',
                'hinh_anh' => 'uploads/phongs/' . $this->images[2],
            ],
            [
                'ten_loai_phong' => 'Luxury Ocean View',
                'gia' => 2500000,
                'so_nguoi' => 2,
                'dien_tich' => 65,
                'tien_nghi' => 'WiFi, View biển 180°, Giường King California, Jacuzzi, Phòng thay đồ, Ban công 15m², Butler 24/7',
                'hinh_anh' => 'uploads/phongs/' . $this->images[3],
            ],
        ];

        foreach ($roomTypes as $type) {
            $roomType = LoaiPhong::updateOrCreate(
                ['ten_loai_phong' => $type['ten_loai_phong']],
                $type
            );
            echo "  ✓ {$roomType->ten_loai_phong} - " . number_format($roomType->gia) . "đ/đêm\n";
        }
        
        echo "\n";
    }

    private function seedRooms()
    {
        echo "🚪 Tạo Phòng Vật Lý (Physical Rooms)...\n";
        
        $roomTypes = LoaiPhong::all();
        $roomCount = 0;

        foreach ($roomTypes as $index => $type) {
            // Tạo 5-8 phòng cho mỗi loại
            $numRooms = rand(5, 8);
            $floor = ($index + 1) * 10; // Floor 10, 20, 30, 40
            
            for ($i = 1; $i <= $numRooms; $i++) {
                $roomNumber = $floor + $i;
                
                Phong::firstOrCreate(
                    ['so_phong' => (string)$roomNumber],
                    [
                        'loai_phong_id' => $type->id,
                        'tinh_trang' => 'available',
                    ]
                );
                $roomCount++;
            }
            
            echo "  ✓ {$type->ten_loai_phong}: {$numRooms} phòng (tầng " . ($floor/10) . ")\n";
        }
        
        echo "  → Tổng: {$roomCount} phòng\n\n";
    }

    private function seedAmenities()
    {
        echo "🛎️ Tạo Tiện Nghi...\n";
        
        $amenities = [
            ['ten_tien_nghi' => 'WiFi Miễn Phí', 'mo_ta' => 'WiFi tốc độ cao 100Mbps', 'icon' => 'wifi'],
            ['ten_tien_nghi' => 'Điều Hòa', 'mo_ta' => 'Điều hòa 2 chiều Daikin', 'icon' => 'ac_unit'],
            ['ten_tien_nghi' => 'TV Màn Hình Phẳng', 'mo_ta' => 'Smart TV Netflix', 'icon' => 'tv'],
            ['ten_tien_nghi' => 'Minibar', 'mo_ta' => 'Đồ uống miễn phí', 'icon' => 'local_bar'],
            ['ten_tien_nghi' => 'Két An Toàn', 'mo_ta' => 'Két điện tử', 'icon' => 'lock'],
            ['ten_tien_nghi' => 'Bồn Tắm', 'mo_ta' => 'Bồn tắm nằm cao cấp', 'icon' => 'bathtub'],
            ['ten_tien_nghi' => 'Ban Công', 'mo_ta' => 'Ban công view biển', 'icon' => 'balcony'],
            ['ten_tien_nghi' => 'Bàn Làm Việc', 'mo_ta' => 'Bàn làm việc rộng rãi', 'icon' => 'desk'],
        ];

        foreach ($amenities as $amenity) {
            TienNghi::firstOrCreate(
                ['ten_tien_nghi' => $amenity['ten_tien_nghi']],
                $amenity
            );
            echo "  ✓ {$amenity['ten_tien_nghi']}\n";
        }
        
        echo "\n";
    }

    private function seedPromotions()
    {
        echo "🎁 Tạo Mã Khuyến Mãi...\n";
        
        $promotions = [
            [
                'ten_khuyen_mai' => 'Giảm 20% Khách Hàng Mới',
                'ma_khuyen_mai' => 'WELCOME20',
                'chiet_khau_phan_tram' => 20.00,
                'so_tien_giam_gia' => 0,
                'usage_limit' => 100,
                'used_count' => 5,
                'usage_per_user' => 1,
                'ngay_bat_dau' => now()->subDays(10),
                'ngay_ket_thuc' => now()->addDays(20),
            ],
            [
                'ten_khuyen_mai' => 'Flash Sale Cuối Tuần',
                'ma_khuyen_mai' => 'WEEKEND50',
                'chiet_khau_phan_tram' => 0,
                'so_tien_giam_gia' => 500000,
                'usage_limit' => 50,
                'used_count' => 12,
                'usage_per_user' => 1,
                'ngay_bat_dau' => now()->subDays(2),
                'ngay_ket_thuc' => now()->addDays(5),
            ],
            [
                'ten_khuyen_mai' => 'Giảm 15% Thành Viên',
                'ma_khuyen_mai' => 'MEMBER15',
                'chiet_khau_phan_tram' => 15.00,
                'so_tien_giam_gia' => 0,
                'usage_limit' => null, // Không giới hạn
                'used_count' => 23,
                'usage_per_user' => 3,
                'ngay_bat_dau' => now()->subDays(30),
                'ngay_ket_thuc' => now()->addDays(60),
            ],
            [
                'ten_khuyen_mai' => 'Tết 2026 - Giảm Sốc',
                'ma_khuyen_mai' => 'TET2026',
                'chiet_khau_phan_tram' => 25.00,
                'so_tien_giam_gia' => 0,
                'usage_limit' => 200,
                'used_count' => 0,
                'usage_per_user' => 2,
                'ngay_bat_dau' => Carbon::create(2026, 1, 1),
                'ngay_ket_thuc' => Carbon::create(2026, 2, 10),
            ],
            [
                'ten_khuyen_mai' => 'Giảm 300K Đơn Từ 2M',
                'ma_khuyen_mai' => 'SAVE300K',
                'chiet_khau_phan_tram' => 0,
                'so_tien_giam_gia' => 300000,
                'usage_limit' => 30,
                'used_count' => 8,
                'usage_per_user' => 1,
                'ngay_bat_dau' => now()->subDays(5),
                'ngay_ket_thuc' => now()->addDays(25),
            ],
        ];

        foreach ($promotions as $promo) {
            $khuyenMai = KhuyenMai::firstOrCreate(
                ['ma_khuyen_mai' => $promo['ma_khuyen_mai']],
                $promo
            );
            
            $discount = $khuyenMai->chiet_khau_phan_tram > 0 
                ? $khuyenMai->chiet_khau_phan_tram . '%' 
                : number_format($khuyenMai->so_tien_giam_gia) . 'đ';
            
            echo "  ✓ {$promo['ma_khuyen_mai']} - Giảm {$discount} (Đã dùng: {$promo['used_count']}";
            if ($promo['usage_limit']) {
                echo "/{$promo['usage_limit']})\n";
            } else {
                echo ", không giới hạn)\n";
            }
        }
        
        echo "\n";
    }

    private function seedCompletedBookings()
    {
        echo "📝 Tạo Booking Đã Hoàn Thành (Completed)...\n";
        
        $users = User::where('role', 'user')->get();
        $roomTypes = LoaiPhong::all();
        $bookingCount = 0;

        foreach ($users->take(4) as $user) {
            // Mỗi user có 1-2 booking đã hoàn thành
            $numBookings = rand(1, 2);
            
            for ($i = 0; $i < $numBookings; $i++) {
                $roomType = $roomTypes->random();
                $phong = Phong::where('loai_phong_id', $roomType->id)->first();
                
                // Ngày trong quá khứ (đã check-out)
                $ngayDen = now()->subDays(rand(30, 60));
                $ngayDi = $ngayDen->copy()->addDays(rand(2, 5));
                $days = $ngayDen->diffInDays($ngayDi);
                
                $subtotal = $roomType->gia * $days;
                $vatAmount = $subtotal * 0.08;
                $total = $subtotal + $vatAmount;

                $booking = DatPhong::create([
                    'user_id' => $user->id,
                    'ngay_den' => $ngayDen,
                    'ngay_di' => $ngayDi,
                    'subtotal' => $subtotal,
                    'vat_amount' => $vatAmount,
                    'tong_tien' => $total,
                    'trang_thai' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => rand(0, 1) ? 'pay_at_hotel' : 'online',
                    'promotion_code' => null,
                    'discount_amount' => 0,
                    'ghi_chu' => 'Đặt phòng qua website',
                ]);

                ChiTietDatPhong::create([
                    'dat_phong_id' => $booking->id,
                    'loai_phong_id' => $roomType->id,
                    'phong_id' => $phong->id,
                    'so_luong' => 1,
                    'don_gia' => $roomType->gia,
                    'thanh_tien' => $subtotal,
                ]);

                HoaDon::create([
                    'dat_phong_id' => $booking->id,
                    'ma_hoa_don' => 'HD' . now()->timestamp . rand(1000, 9999),
                    'ngay_lap' => $ngayDi,
                    'subtotal' => $subtotal,
                    'vat_amount' => $vatAmount,
                    'tong_tien' => $total,
                    'phuong_thuc_thanh_toan' => $booking->payment_method,
                    'trang_thai' => 'paid',
                ]);

                $bookingCount++;
            }
            
            echo "  ✓ {$user->name}: {$numBookings} booking(s) đã hoàn thành\n";
        }
        
        echo "  → Tổng: {$bookingCount} bookings\n\n";
    }

    private function seedActiveBookings()
    {
        echo "📋 Tạo Booking Hiện Tại (Active)...\n";
        
        $users = User::where('role', 'user')->get();
        $roomTypes = LoaiPhong::all();
        $statuses = ['pending', 'confirmed', 'confirmed', 'awaiting_payment'];
        $bookingCount = 0;

        foreach ($users->take(5) as $user) {
            $roomType = $roomTypes->random();
            $phong = Phong::where('loai_phong_id', $roomType->id)
                ->whereNotIn('id', function($query) {
                    // Tránh phòng đang được đặt
                    $query->select('phong_id')
                        ->from('chi_tiet_dat_phongs')
                        ->whereIn('dat_phong_id', function($subQuery) {
                            $subQuery->select('id')
                                ->from('dat_phongs')
                                ->whereIn('trang_thai', ['pending', 'confirmed', 'awaiting_payment']);
                        });
                })
                ->first();
            
            if (!$phong) continue;
            
            // Ngày trong tương lai
            $ngayDen = now()->addDays(rand(3, 15));
            $ngayDi = $ngayDen->copy()->addDays(rand(2, 5));
            $days = $ngayDen->diffInDays($ngayDi);
            
            // Random có dùng mã giảm giá không
            $usePromo = rand(0, 2) == 0; // 33% chance
            $discountAmount = 0;
            $promoCode = null;
            
            if ($usePromo) {
                $promo = KhuyenMai::where('ngay_ket_thuc', '>=', now())->inRandomOrder()->first();
                if ($promo) {
                    $promoCode = $promo->ma_khuyen_mai;
                    $originalTotal = $roomType->gia * $days;
                    $discountAmount = ($promo->chiet_khau_phan_tram > 0)
                        ? $originalTotal * ($promo->chiet_khau_phan_tram / 100)
                        : $promo->so_tien_giam_gia;
                }
            }
            
            $subtotal = ($roomType->gia * $days) - $discountAmount;
            $vatAmount = $subtotal * 0.08;
            $total = $subtotal + $vatAmount;
            
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = ($status == 'confirmed') ? 'paid' : 'unpaid';

            $booking = DatPhong::create([
                'user_id' => $user->id,
                'ngay_den' => $ngayDen,
                'ngay_di' => $ngayDi,
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'tong_tien' => $total,
                'trang_thai' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => rand(0, 1) ? 'pay_at_hotel' : 'online',
                'promotion_code' => $promoCode,
                'discount_amount' => $discountAmount,
                'ghi_chu' => null,
            ]);

            ChiTietDatPhong::create([
                'dat_phong_id' => $booking->id,
                'loai_phong_id' => $roomType->id,
                'phong_id' => $phong->id,
                'so_luong' => 1,
                'don_gia' => $roomType->gia,
                'thanh_tien' => $roomType->gia * $days,
            ]);

            if ($paymentStatus == 'paid') {
                HoaDon::create([
                    'dat_phong_id' => $booking->id,
                    'ma_hoa_don' => 'HD' . now()->timestamp . rand(1000, 9999),
                    'ngay_lap' => now(),
                    'subtotal' => $subtotal,
                    'vat_amount' => $vatAmount,
                    'tong_tien' => $total,
                    'phuong_thuc_thanh_toan' => $booking->payment_method,
                    'trang_thai' => 'paid',
                ]);
            }

            $bookingCount++;
            $promoInfo = $promoCode ? " (Mã: {$promoCode})" : "";
            echo "  ✓ {$user->name}: {$roomType->ten_loai_phong} - {$status}{$promoInfo}\n";
        }
        
        echo "  → Tổng: {$bookingCount} bookings active\n\n";
    }

    private function seedReviews()
    {
        echo "⭐ Tạo Reviews (Đánh Giá)...\n";
        
        // Lấy users đã có booking completed
        $completedBookings = DatPhong::where('trang_thai', 'completed')->get();
        $reviewCount = 0;

        foreach ($completedBookings->take(6) as $booking) {
            $detail = $booking->chiTietDatPhongs()->first();
            if (!$detail) continue;

            $ratings = [5, 5, 4, 4, 4, 3]; // Mostly positive
            $rating = $ratings[array_rand($ratings)];
            
            $comments = [
                5 => [
                    'Phòng rất đẹp, sạch sẽ, view tuyệt vời! Nhân viên thân thiện, nhiệt tình. Sẽ quay lại!',
                    'Tuyệt vời! Đúng như hình ảnh, thậm chí còn đẹp hơn. Giá cả hợp lý.',
                    'Trải nghiệm tuyệt vời! Phòng sang trọng, tiện nghi đầy đủ. Highly recommended!',
                ],
                4 => [
                    'Phòng đẹp, sạch sẽ. Tuy nhiên wifi hơi chậm. Nhìn chung OK.',
                    'Khá tốt, nhân viên nhiệt tình. Vị trí thuận tiện. Giá hơi cao một chút.',
                    'Phòng rộng rãi, thoáng mát. Ăn sáng ngon. Có thể cải thiện thêm về âm thanh cách âm.',
                ],
                3 => [
                    'Ở được, phòng sạch nhưng không gian hơi nhỏ so với giá tiền.',
                    'Tạm được. Một số tiện nghi đã cũ, cần nâng cấp.',
                ],
            ];

            $review = Review::create([
                'user_id' => $booking->user_id,
                'loai_phong_id' => $detail->loai_phong_id,
                'rating' => $rating,
                'noi_dung' => $comments[$rating][array_rand($comments[$rating])],
            ]);

            $reviewCount++;
            echo "  ✓ {$booking->user->name}: {$rating}⭐ - {$detail->loaiPhong->ten_loai_phong}\n";
        }
        
        echo "  → Tổng: {$reviewCount} reviews\n\n";
    }

    private function seedFeedback()
    {
        echo "💬 Tạo Feedback (Phản Hồi)...\n";
        
        $users = User::where('role', 'user')->get();
        $subjects = [
            'Hỏi về chính sách hủy phòng',
            'Đề xuất thêm dịch vụ spa',
            'Thắc mắc về thanh toán online',
            'Góp ý về dọn phòng',
            'Hỏi về dịch vụ đưa đón sân bay',
        ];
        
        $messages = [
            'Cho em hỏi nếu hủy phòng trước 3 ngày thì có được hoàn tiền không ạ?',
            'Khách sạn nên có thêm dịch vụ spa và massage để khách có thêm lựa chọn thư giãn.',
            'Em thanh toán online bằng VNPay nhưng chưa thấy xác nhận, vui lòng kiểm tra giúp em.',
            'Dịch vụ dọn phòng nên cải thiện hơn, đặc biệt là việc thay khăn tắm hàng ngày.',
            'Khách sạn có dịch vụ đưa đón sân bay không ạ? Giá bao nhiêu?',
        ];

        $feedbackCount = 0;
        foreach ($users->take(5) as $index => $user) {
            $hasReply = rand(0, 1); // 50% có reply
            
            $feedback = Feedback::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'subject' => $subjects[$index],
                'message' => $messages[$index],
                'status' => $hasReply ? 'responded' : 'pending',
            ]);

            if ($hasReply) {
                $adminUser = User::where('role', 'admin')->first();
                $replies = [
                    'Cảm ơn bạn đã phản hồi. Theo chính sách, hủy trước 3 ngày được hoàn 80% tiền đặt cọc.',
                    'Cảm ơn góp ý! Chúng tôi đang cân nhắc thêm dịch vụ spa trong quý tới.',
                    'Đơn của bạn đã được xác nhận. Vui lòng kiểm tra email.',
                    'Xin lỗi vì sự bất tiện. Chúng tôi sẽ nhắc nhở bộ phận housekeeping.',
                    'Có ạ! Dịch vụ đưa đón sân bay 200k/lượt. Vui lòng đặt trước 24h.',
                ];
                
                $feedback->replies()->create([
                    'user_id' => $adminUser->id,
                    'reply' => $replies[$index],
                ]);
            }

            $feedbackCount++;
            $status = $hasReply ? '(Đã trả lời)' : '(Chưa trả lời)';
            echo "  ✓ {$user->name}: {$subjects[$index]} {$status}\n";
        }
        
        echo "  → Tổng: {$feedbackCount} feedbacks\n\n";
    }

    private function printSummary()
    {
        $stats = [
            'Users' => User::where('role', 'user')->count(),
            'Loại Phòng' => LoaiPhong::count(),
            'Phòng Vật Lý' => Phong::count(),
            'Mã Khuyến Mãi' => KhuyenMai::count(),
            'Bookings (Completed)' => DatPhong::where('trang_thai', 'completed')->count(),
            'Bookings (Active)' => DatPhong::whereIn('trang_thai', ['pending', 'confirmed', 'awaiting_payment'])->count(),
            'Reviews' => Review::count(),
            'Feedbacks' => Feedback::count(),
            'Hóa Đơn' => HoaDon::count(),
        ];

        echo "\n📊 THỐNG KÊ DỮ LIỆU:\n";
        foreach ($stats as $label => $count) {
            echo "  • {$label}: {$count}\n";
        }
    }
}
