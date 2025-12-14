<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DatPhong;
use App\Models\HoaDon;
use Illuminate\Support\Facades\DB;

class UpdateExistingBookingsWithVat extends Seeder
{
    /**
     * Cập nhật các đơn đặt phòng và hóa đơn cũ để thêm VAT 8%
     */
    public function run(): void
    {
        $this->command->info('Đang cập nhật các đơn đặt phòng cũ với VAT 8%...');

        // Cập nhật bảng dat_phongs
        $bookings = DatPhong::whereNull('subtotal')->orWhere('subtotal', 0)->get();
        
        foreach ($bookings as $booking) {
            // Tính subtotal từ tong_tien cũ (giả sử tong_tien cũ đã bao gồm discount nhưng chưa có VAT)
            // subtotal = tong_tien / 1.08 (vì tong_tien = subtotal * 1.08)
            $oldTotal = $booking->tong_tien;
            
            // Giả sử tong_tien cũ chưa có VAT, nên:
            $subtotal = $oldTotal - ($booking->discount_amount ?? 0);
            $vatAmount = $subtotal * 0.08;
            $newTotal = $subtotal + $vatAmount;
            
            $booking->update([
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'tong_tien' => $newTotal,
            ]);
        }

        $this->command->info("Đã cập nhật {$bookings->count()} đơn đặt phòng.");

        // Cập nhật bảng hoa_dons
        $invoices = HoaDon::whereNull('subtotal')->orWhere('subtotal', 0)->get();
        
        foreach ($invoices as $invoice) {
            $booking = $invoice->datPhong;
            if ($booking) {
                $invoice->update([
                    'subtotal' => $booking->subtotal,
                    'vat_amount' => $booking->vat_amount,
                    'tong_tien' => $booking->tong_tien,
                ]);
            }
        }

        $this->command->info("Đã cập nhật {$invoices->count()} hóa đơn.");
        $this->command->info('Hoàn tất!');
    }
}
