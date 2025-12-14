# 🔍 COMPLETE DATABASE SCHEMA ANALYSIS

## 📊 ACTUAL COLUMN NAMES (tiếng Việt vs Test Script)

### ❌ ISSUES FOUND - Missing/Incorrect Column Names in Test Scripts

#### 1. **USERS Table**
**Actual Columns:**
- `id`, `name`, `email`, `cccd`, `phone`, `username`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`

**Test Script Expected (WRONG):**
- ❌ `so_dien_thoai` → ✅ Should be `phone`
- ❌ `dia_chi` → ⚠️  **MISSING** - No address column
- ❌ `google_id` → ⚠️  **MISSING** - No Google OAuth column
- ❌ `avatar` → ⚠️  **MISSING** - No avatar column
- ✅ `cccd` - EXISTS (ID card number)
- ✅ `username` - EXISTS

**Recommendation:**
- Add migration for `dia_chi` (address) if needed for user profiles
- Add migration for `google_id` and `avatar` if Google OAuth is used
- Update models to use `phone` instead of `so_dien_thoai`

---

#### 2. **DAT_PHONGS Table (Bookings)**
**Actual Columns:**
- `id`, `user_id`, `ngay_den`, `ngay_di`, `subtotal`, `tong_tien`, `trang_thai`, `payment_status`, `payment_method`, `ghi_chu`, `cancel_reason`, `cancelled_at`, `promotion_code`, `discount_amount`, `vat_amount`, `created_at`, `updated_at`

**Test Script Expected (WRONG):**
- ❌ `ma_dat_phong` → ⚠️  **MISSING** - No unique booking code
- ❌ `ngay_dat` → ⚠️  **MISSING** - No booking date (when booking was made)
- ❌ `ngay_nhan_phong` → ✅ Should be `ngay_den` (check-in date)
- ❌ `ngay_tra_phong` → ✅ Should be `ngay_di` (check-out date)
- ❌ `so_dem` → ⚠️  **MISSING** - No nights count (should be calculated)
- ❌ `payment_details` → ⚠️  **MISSING** - No payment details JSON
- ❌ `khuyen_mai_id` → ✅ Should be `promotion_code` (stores code string, not ID)
- ❌ `accepted_terms` → ⚠️  **MISSING** - No terms acceptance flag
- ❌ `deleted_at` → ⚠️  **MISSING** - No soft delete column

**Recommendation:**
- Add `ma_dat_phong` (unique booking code like BK-2025-0001)
- Add `ngay_dat` (booking created date separate from check-in)
- Add `so_dem` (number of nights - for quick queries)
- Add `payment_details` (JSON - for storing VNPay transaction details)
- Consider changing `promotion_code` to `khuyen_mai_id` foreign key (better relational integrity)
- Add `accepted_terms` boolean (legal requirement)
- Add `deleted_at` for soft deletes (trash feature)

---

#### 3. **HOA_DONS Table (Invoices)**
**Actual Columns:**
- `id`, `dat_phong_id`, `ma_hoa_don`, `ngay_lap`, `subtotal`, `vat_amount`, `tong_tien`, `phuong_thuc_thanh_toan`, `trang_thai`, `created_at`, `updated_at`

**Test Script Expected (WRONG):**
- ❌ `so_hoa_don` → ✅ Should be `ma_hoa_don` (invoice number)
- ❌ `payment_method` → ✅ Should be `phuong_thuc_thanh_toan`

**Recommendation:**
- ✅ Schema is mostly correct
- Standardize naming: either all English or all Vietnamese
- Current mix: `ma_hoa_don` (VN) but `payment_method` would be English

---

#### 4. **KHUYEN_MAIS Table (Promotions)**
**Actual Columns:**
- `id`, `ten_khuyen_mai`, `ma_khuyen_mai`, `chiet_khau_phan_tram`, `so_tien_giam_gia`, `usage_limit`, `used_count`, `usage_per_user`, `ngay_bat_dau`, `ngay_ket_thuc`, `created_at`, `updated_at`

**Test Script Expected (WRONG):**
- ❌ `mo_ta` → ⚠️  **MISSING** - No description column
- ❌ `loai_giam_gia` → ✅ Should be determined by: `chiet_khau_phan_tram` > 0 = percentage, `so_tien_giam_gia` > 0 = fixed amount
- ❌ `gia_tri_giam` → ✅ Should be EITHER `chiet_khau_phan_tram` OR `so_tien_giam_gia`
- ❌ `active` → ⚠️  **MISSING** - No active/inactive flag

**Recommendation:**
- Add `mo_ta` (description) - helpful for admin management
- Add `active` boolean - for enabling/disabling without deleting
- Consider adding `loai_giam_gia` enum for clarity instead of checking both columns

---

#### 5. **LOAI_PHONGS Table (Room Types)**
**Actual Columns:**
- `id`, `ten_loai_phong`, `gia`, `so_nguoi`, `dien_tich`, `hinh_anh`, `tien_nghi`, `created_at`, `updated_at`

**Test Script Expected (WRONG):**
- ❌ `gia_tien` → ✅ Should be `gia`

**Recommendation:**
- ✅ Schema is correct
- Consider adding `mo_ta` (description) for SEO and user information

---

## 🚨 CRITICAL MISSING FIELDS SUMMARY

### High Priority (Security & Legal)
1. **dat_phongs.accepted_terms** - Legal requirement for bookings
2. **dat_phongs.ma_dat_phong** - Unique booking reference for customer service
3. **dat_phongs.ngay_dat** - Important for analytics (booking date vs check-in date)
4. **dat_phongs.payment_details** - VNPay transaction tracking
5. **users.dia_chi** - Address needed for invoices and legal compliance

### Medium Priority (Features)
6. **users.google_id** - If Google OAuth is implemented
7. **users.avatar** - User profile pictures
8. **khuyen_mais.mo_ta** - Promotion descriptions for admin
9. **khuyen_mais.active** - Enable/disable promotions
10. **dat_phongs.deleted_at** - Soft delete for trash/recovery feature

### Low Priority (Nice to Have)
11. **dat_phongs.so_dem** - Calculated field for convenience
12. **loai_phongs.mo_ta** - Room type descriptions
13. **khuyen_mais.loai_giam_gia** - Explicit type instead of implicit

---

## 📋 RECOMMENDED MIGRATIONS

### Migration 1: Add user profile fields
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('dia_chi', 500)->nullable()->after('phone');
    $table->string('google_id')->nullable()->after('dia_chi');
    $table->string('avatar', 500)->nullable()->after('google_id');
});
```

### Migration 2: Add booking essential fields
```php
Schema::table('dat_phongs', function (Blueprint $table) {
    $table->string('ma_dat_phong', 50)->unique()->after('id');
    $table->date('ngay_dat')->after('user_id');
    $table->integer('so_dem')->after('ngay_di');
    $table->json('payment_details')->nullable()->after('payment_method');
    $table->boolean('accepted_terms')->default(false)->after('ghi_chu');
    $table->softDeletes(); // adds deleted_at
});
```

### Migration 3: Add promotion fields
```php
Schema::table('khuyen_mais', function (Blueprint $table) {
    $table->text('mo_ta')->nullable()->after('ten_khuyen_mai');
    $table->enum('loai_giam_gia', ['phan_tram', 'tien_mat'])->after('mo_ta');
    $table->boolean('active')->default(true)->after('ngay_ket_thuc');
});
```

### Migration 4: Change promotion_code to foreign key (Optional)
```php
Schema::table('dat_phongs', function (Blueprint $table) {
    $table->dropColumn('promotion_code');
    $table->unsignedBigInteger('khuyen_mai_id')->nullable()->after('vat_amount');
    $table->foreign('khuyen_mai_id')->references('id')->on('khuyen_mais')->onDelete('set null');
});
```

---

## ✅ WHAT'S WORKING CORRECTLY

1. ✅ **Foreign Keys** - All relationships are properly defined
2. ✅ **VAT Tracking** - subtotal and vat_amount exist
3. ✅ **Payment Tracking** - payment_status and payment_method exist
4. ✅ **Promotion Usage** - usage_limit, used_count, usage_per_user exist
5. ✅ **Cancellation** - cancel_reason and cancelled_at exist
6. ✅ **Discount Tracking** - discount_amount exists
7. ✅ **Unique Constraints** - email, ma_khuyen_mai, ma_hoa_don are unique
8. ✅ **Indexes** - Foreign keys are properly indexed

---

## 🔧 ACTION ITEMS

### Immediate (Fix Test Scripts)
- [ ] Update test scripts to use correct Vietnamese column names
- [ ] Update BookingController to use `ngay_den`/`ngay_di` instead of `ngay_nhan_phong`/`ngay_tra_phong`
- [ ] Update models to use `phone` instead of `so_dien_thoai`
- [ ] Update KhuyenMai validation to check `chiet_khau_phan_tram` and `so_tien_giam_gia`

### Short Term (Add Missing Columns)
- [ ] Create migration for user profile fields
- [ ] Create migration for booking essential fields
- [ ] Create migration for promotion fields
- [ ] Update all controllers and models to use new fields

### Long Term (Refactoring)
- [ ] Consider standardizing naming convention (all EN or all VN)
- [ ] Add comprehensive validation rules for all new fields
- [ ] Update API documentation with correct column names
- [ ] Add database seeders for testing new fields

---

## 📝 COLUMN NAME MAPPING REFERENCE

| Feature | Test Script Used | Actual Column | Status |
|---------|-----------------|---------------|--------|
| User phone | `so_dien_thoai` | `phone` | ❌ Update |
| User address | `dia_chi` | - | ⚠️  Missing |
| User Google ID | `google_id` | - | ⚠️  Missing |
| User avatar | `avatar` | - | ⚠️  Missing |
| Booking code | `ma_dat_phong` | - | ⚠️  Missing |
| Booking date | `ngay_dat` | - | ⚠️  Missing |
| Check-in | `ngay_nhan_phong` | `ngay_den` | ❌ Update |
| Check-out | `ngay_tra_phong` | `ngay_di` | ❌ Update |
| Nights count | `so_dem` | - | ⚠️  Missing |
| Payment details | `payment_details` | - | ⚠️  Missing |
| Promo FK | `khuyen_mai_id` | `promotion_code` | ❌ Different |
| Terms accepted | `accepted_terms` | - | ⚠️  Missing |
| Soft delete | `deleted_at` | - | ⚠️  Missing |
| Invoice number | `so_hoa_don` | `ma_hoa_don` | ❌ Update |
| Invoice payment | `payment_method` | `phuong_thuc_thanh_toan` | ❌ Update |
| Promo desc | `mo_ta` | - | ⚠️  Missing |
| Promo type | `loai_giam_gia` | (implicit) | ⚠️  Missing |
| Promo value | `gia_tri_giam` | `chiet_khau_phan_tram` OR `so_tien_giam_gia` | ❌ Update |
| Promo active | `active` | - | ⚠️  Missing |
| Room price | `gia_tien` | `gia` | ❌ Update |

---

**Last Updated:** 2025-12-14
**Total Missing Fields:** 13
**Total Name Mismatches:** 10
