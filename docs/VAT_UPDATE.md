# Cập nhật tính thuế VAT 8% cho hệ thống đặt phòng

## Tổng quan
Đã bổ sung tính năng thuế VAT 8% vào hệ thống đặt phòng và hóa đơn. VAT được tính trên tổng tiền sau khi trừ giảm giá.

## Công thức tính
```
Giá gốc = Đơn giá × Số đêm
Sau giảm giá (subtotal) = Giá gốc - Giảm giá
VAT (8%) = Subtotal × 0.08
Tổng thanh toán = Subtotal + VAT
```

## Các thay đổi đã thực hiện

### 1. Database (Migration)
**File**: `database/migrations/2025_12_14_add_vat_columns_to_dat_phongs_and_hoa_dons.php`

Thêm 2 cột mới:
- `subtotal`: Tạm tính trước thuế (sau giảm giá)
- `vat_amount`: Số tiền thuế VAT 8%

Áp dụng cho 2 bảng:
- `dat_phongs`
- `hoa_dons`

**Chạy migration**:
```bash
php artisan migrate
```

### 2. Models
**Files đã cập nhật**:
- `app/Models/DatPhong.php`: Thêm `subtotal`, `vat_amount` vào `$fillable`
- `app/Models/HoaDon.php`: Thêm `subtotal`, `vat_amount` vào `$fillable`

### 3. Controllers
**File**: `app/Http/Controllers/Client/BookingController.php`
- Cập nhật hàm `createBooking()`: Tính VAT khi tạo đơn mới
- Cập nhật hàm `create()`: Truyền `vatAmount` và `totalWithVat` xuống view
- Cập nhật hàm `postVnPayStore()`: Lưu VAT vào hóa đơn online

**File**: `app/Http/Controllers/Admin/DatPhongController.php`
- Cập nhật hàm `postThem()`: Tính VAT khi admin tạo đơn
- Cập nhật hàm `getHoaDon()`: Đảm bảo hóa đơn có VAT

### 4. Views (Client)
**File**: `resources/views/client/booking/create.blade.php`
- Thêm dòng hiển thị "Thuế VAT (8%)" trong bảng tổng kết
- Cập nhật `finalTotalText` để bao gồm VAT

**File**: `resources/views/client/booking/invoice.blade.php`
- Hiển thị:
  - Tạm tính (trước giảm giá)
  - Giảm giá (nếu có)
  - Tạm tính (sau giảm giá)
  - Thuế VAT (8%)
  - Tổng thanh toán

### 5. Views (Admin)
**File**: `resources/views/admin/dat_phong/hoa_don_chi_tiet.blade.php`
- Thêm dòng hiển thị:
  - Tạm tính (trước VAT)
  - Thuế VAT (8%)
  - Tổng thanh toán

### 6. JavaScript
**File**: `resources/js/client/booking.js`
- Thêm logic tính VAT động khi áp dụng mã khuyến mãi
- Cập nhật `updateSummary()` để:
  - Tính subtotal sau giảm giá
  - Tính VAT 8% trên subtotal
  - Hiển thị VAT và tổng cuối cùng

**Đã build lại assets**:
```bash
npm run build
```

## Cập nhật dữ liệu cũ (nếu cần)

Nếu hệ thống đã có dữ liệu booking cũ, chạy seeder để cập nhật VAT:

```bash
php artisan db:seed --class=UpdateExistingBookingsWithVat
```

**Lưu ý**: Script này giả định rằng `tong_tien` cũ chưa bao gồm VAT và sẽ tính lại theo công thức:
```
subtotal = tong_tien_cu - discount_amount
vat_amount = subtotal * 0.08
tong_tien_moi = subtotal + vat_amount
```

## Kiểm tra kết quả

### 1. Client: Trang đặt phòng
- Truy cập: `/phong/dat-phong?room_id=1&checkin=...&checkout=...`
- Kiểm tra bảng tóm tắt bên phải có hiển thị:
  - Giá gốc
  - Giảm giá (nếu nhập mã)
  - **Thuế VAT (8%)** ← MỚI
  - Tổng thanh toán

### 2. Client: Hóa đơn
- Truy cập: `/dat-phong/{id}/hoa-don`
- Kiểm tra có hiển thị:
  - Tạm tính (trước/sau giảm giá)
  - **Thuế VAT (8%)** ← MỚI
  - Tổng thanh toán

### 3. Admin: Chi tiết hóa đơn
- Truy cập: `/admin/dat-phong/{id}/hoa-don`
- Kiểm tra phần tổng kết có:
  - Tạm tính (trước VAT)
  - **Thuế VAT (8%)** ← MỚI
  - Tổng thanh toán

## Ví dụ tính toán

### Ví dụ 1: Không có giảm giá
```
Giá phòng: 1.000.000đ × 2 đêm = 2.000.000đ
Giảm giá: 0đ
Subtotal: 2.000.000đ
VAT (8%): 160.000đ
Tổng: 2.160.000đ
```

### Ví dụ 2: Có giảm giá 10%
```
Giá phòng: 1.000.000đ × 2 đêm = 2.000.000đ
Giảm giá (10%): -200.000đ
Subtotal: 1.800.000đ
VAT (8%): 144.000đ
Tổng: 1.944.000đ
```

## Lưu ý quan trọng

1. **VAT được tính TRÊN subtotal (sau giảm giá)**, không phải trên giá gốc
2. **Tất cả đơn mới** sẽ tự động có VAT 8%
3. **Đơn cũ** cần chạy seeder để cập nhật (nếu cần)
4. **API mã khuyến mãi** vẫn trả về `discount_amount` và `final_total`, nhưng JavaScript sẽ tính lại với VAT
5. **Admin tạo đơn** cũng tự động tính VAT

## Troubleshooting

### Vấn đề: Không thấy VAT trên trang đặt phòng
**Giải pháp**:
```bash
npm run build
php artisan cache:clear
php artisan view:clear
```

### Vấn đề: Database lỗi "Unknown column 'subtotal'"
**Giải pháp**:
```bash
php artisan migrate
```

### Vấn đề: Đơn cũ không có VAT
**Giải pháp**:
```bash
php artisan db:seed --class=UpdateExistingBookingsWithVat
```

## Tác giả
Cập nhật bởi: GitHub Copilot
Ngày: 14/12/2025
