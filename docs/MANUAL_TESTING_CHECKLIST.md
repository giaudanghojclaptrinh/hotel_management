# 🧪 MANUAL TESTING CHECKLIST - Hotel Management System

**Tester:** Manual QA  
**Date Started:** December 15, 2025  
**Method:** Thực tế nhập dữ liệu bằng tay, click từng button, test từng form

---

## 🎯 TESTING STRATEGY

✅ **Đã test** | ⚠️ **Found bugs** | ❌ **Chưa test** | 🔄 **Đang test**

---

## 1️⃣ AUTHENTICATION & AUTHORIZATION

### Login
- [ ] Login với email/password đúng → Redirect đúng role (admin/user)
- [ ] Login với email sai → Show error
- [ ] Login với password sai → Show error
- [ ] Login với email chưa verify → Check behavior
- [ ] "Remember me" checkbox → Session persistent
- [ ] "Forgot password" link → Work correctly
- [ ] Google OAuth login → Work if implemented

### Register
- [ ] Register với thông tin hợp lệ → Account created
- [ ] Register với email đã tồn tại → Show error
- [ ] Register với phone đã tồn tại → Show error
- [ ] Register với CCCD đã tồn tại → Show error
- [ ] Password confirmation không match → Show error
- [ ] Validation cho các trường required → Work correctly

### Logout
- [ ] Logout → Clear session, redirect to login

---

## 2️⃣ ADMIN - USER MANAGEMENT

### Danh sách Users
- [ ] Hiển thị đầy đủ users (admin + user)
- [ ] Filter theo role (admin/user) → Work correctly
- [ ] Search theo name/email/phone → Work correctly
- [ ] Pagination → Work correctly

### Thêm User
- [ ] Thêm user mới với đầy đủ thông tin → Success
- [ ] Email duplicate → Show error
- [ ] Phone duplicate → Show error
- [ ] CCCD duplicate → Show error
- [ ] Password < 6 chars → Show error
- [ ] Role mặc định là "user" → Correct

### Sửa User
- ✅ **BUG FOUND & FIXED:** Role không được cập nhật vào database
- [ ] Sửa name → Saved correctly
- [ ] Sửa email → Check unique validation
- [ ] Sửa phone → Check unique validation
- [ ] Sửa CCCD → Check unique validation
- [ ] Đổi role từ user → admin → **NOW FIXED** ✅
- [ ] Đổi role từ admin → user → Test this
- [ ] Đổi password (nhập mới) → Saved & hashed
- [ ] Không đổi password (để trống) → Keep old password
- [ ] Sửa thông tin của chính mình (admin) → Work correctly

### Xóa User
- [ ] Xóa user thường → Success
- [ ] Xóa admin → Bị chặn (có message error)
- [ ] Bulk delete nhiều users → Work correctly
- [ ] Bulk delete có cả admin trong list → Only delete users, skip admins

---

## 3️⃣ ADMIN - ROOM TYPE MANAGEMENT

### Danh sách Loại Phòng
- [ ] Hiển thị đầy đủ room types
- [ ] Sort by created_at → Work correctly
- [ ] Show image, price, capacity correctly

### Thêm Loại Phòng
- [ ] Thêm với đầy đủ thông tin → Success
- [ ] Upload hình ảnh → Saved to storage
- [ ] Price = 0 → Should show error
- [ ] Capacity = 0 → Should show error
- [ ] Tên trùng → Check behavior (có validation?)

### Sửa Loại Phòng
- [ ] Sửa tên → Saved
- [ ] Sửa giá → Saved
- [ ] Sửa sức chứa → Saved
- [ ] Sửa diện tích → Saved
- [ ] Thay đổi hình ảnh → Old image deleted, new saved
- [ ] Update tiện nghi → Saved

### Xóa Loại Phòng
- [ ] Xóa room type không có phòng → Success
- [ ] Xóa room type có phòng đang tồn tại → Check behavior (cascade/prevent?)

---

## 4️⃣ ADMIN - ROOM MANAGEMENT (Phòng vật lý)

### Danh sách Phòng
- [ ] Hiển thị đầy đủ rooms với room type
- [ ] Filter theo loại phòng → Work correctly
- [ ] Filter theo trạng thái (available/occupied/maintenance) → Work correctly
- [ ] Search theo số phòng → Work correctly
- [ ] Bulk delete → Work correctly

### Thêm Phòng
- [ ] Thêm phòng mới → Success
- [ ] Số phòng trùng → Should show error (unique)
- [ ] Loại phòng invalid → Show error
- [ ] Trạng thái mặc định → "available"

### Sửa Phòng
- [ ] Đổi số phòng → Check unique validation
- [ ] Đổi loại phòng → Saved
- [ ] Đổi trạng thái → Saved
- [ ] Đổi phòng có booking active sang maintenance → Check behavior

### Xóa Phòng
- [ ] Xóa phòng không có booking → Success
- [ ] Xóa phòng có booking → Check behavior (prevent/cascade?)

---

## 5️⃣ ADMIN - PROMOTION MANAGEMENT

### Danh sách Khuyến Mãi
- [ ] Hiển thị đầy đủ promotions
- [ ] Show usage count/limit correctly
- [ ] Filter expired/active → Work if implemented
- [ ] Bulk delete → Work correctly

### Thêm Khuyến Mãi
- [ ] Thêm promo với % discount → Success
- [ ] Thêm promo với fixed amount → Success
- [ ] Mã code trùng → Show error (unique)
- [ ] Ngày bắt đầu > ngày kết thúc → Should show error
- [ ] % discount > 100 → Should show error
- [ ] Discount < 0 → Should show error
- [ ] Usage limit = 0 → Check behavior
- [ ] Usage per user > usage limit → Should show error

### Sửa Khuyến Mãi
- [ ] Sửa tên → Saved
- [ ] Sửa mã code → Check unique
- [ ] Sửa discount value → Saved
- [ ] Thay đổi loại discount (% ↔ fixed) → Saved correctly
- [ ] Sửa ngày → Validate start < end
- [ ] Sửa usage limit → Saved
- [ ] Giảm usage limit < used_count hiện tại → Check behavior

### Xóa Khuyến Mãi
- [ ] Xóa promo chưa dùng → Success
- [ ] Xóa promo đã có người dùng → Check behavior (cascade? set null?)

---

## 6️⃣ ADMIN - BOOKING MANAGEMENT

### Danh sách Đặt Phòng
- [ ] Hiển thị đầy đủ bookings
- [ ] Filter theo status (pending/confirmed/cancelled/completed) → Work
- [ ] Filter theo payment status → Work
- [ ] Filter theo date range → Work if implemented
- [ ] Search theo mã booking/user → Work
- [ ] View chi tiết booking → Show full info
- [ ] View thông tin khách hàng → Show correctly

### Duyệt Đơn (Approve)
- [ ] Duyệt booking pending → Status = confirmed
- [ ] Duyệt booking đã duyệt → Check behavior (prevent/allow?)
- [ ] Phòng không còn available → Should show error
- [ ] Notification gửi đến user → Check

### Hủy Đơn (Cancel)
- [ ] Admin hủy booking → Status = cancelled
- [ ] Nhập lý do hủy → Saved to cancel_reason
- [ ] Hủy booking đã thanh toán → Check refund logic
- [ ] Invoice status update → Check
- [ ] Notification gửi đến user → Check

### Sửa Booking (nếu có)
- [ ] Đổi ngày check-in/out → Validate availability
- [ ] Đổi phòng → Check availability
- [ ] Đổi promotion → Recalculate price

### Xóa Booking
- [ ] Xóa booking cancelled → Success
- [ ] Xóa booking confirmed → Check behavior (prevent?)
- [ ] Bulk delete → Work correctly
- [ ] Trash feature → Work if implemented

### Báo cáo Doanh Thu
- [ ] View revenue report → Calculate correctly
- [ ] Filter by date range → Work
- [ ] Export Excel/PDF → Work if implemented

---

## 7️⃣ ADMIN - INVOICE MANAGEMENT

### Danh sách Hóa Đơn
- [ ] Hiển thị đầy đủ invoices
- [ ] Filter theo status → Work
- [ ] Filter theo payment method → Work
- [ ] Search theo mã hóa đơn → Work

### View Hóa Đơn
- [ ] View invoice detail → Show full info
- [ ] Invoice number unique → Check
- [ ] Total = booking total → Verify
- [ ] VAT calculation (8%) → Correct
- [ ] Print invoice → CSS OK

### Sửa Hóa Đơn (nếu có)
- [ ] Đổi status → Saved
- [ ] Đổi payment method → Saved
- [ ] Sửa total → Check if allowed

### Xóa Hóa Đơn
- [ ] Xóa invoice → Check behavior

---

## 8️⃣ ADMIN - FEEDBACK MANAGEMENT

### Danh sách Feedbacks
- [ ] Hiển thị đầy đủ feedbacks
- [ ] Filter theo status (pending/responded/closed) → Work
- [ ] Mark as handled → Status updated
- [ ] Bulk delete → Work

### View Feedback Detail
- [ ] View full message → Display correctly
- [ ] User info shown → Correct
- [ ] Email valid → Check format

### Reply Feedback (nếu có)
- [ ] Admin reply → Email sent to user
- [ ] Status = responded → Updated

---

## 9️⃣ ADMIN - AMENITY MANAGEMENT

### Danh sách Tiện Nghi
- [ ] Hiển thị đầy đủ amenities
- [ ] Bulk delete → Work

### Thêm Tiện Nghi
- [ ] Thêm mới → Success
- [ ] Tên trùng → Check validation
- [ ] Icon/image upload → Work

### Sửa Tiện Nghi
- [ ] Sửa tên → Saved
- [ ] Sửa icon → Saved

### Xóa Tiện Nghi
- [ ] Xóa amenity đang dùng bởi room types → Check behavior

---

## 🔟 CLIENT - HOMEPAGE

### Display
- [ ] Hero section hiển thị đẹp
- [ ] Featured rooms hiển thị đúng
- [ ] Promotions hiển thị (nếu có)
- [ ] Reviews/testimonials hiển thị
- [ ] Contact info hiển thị

### Navigation
- [ ] Menu navigation → All links work
- [ ] Search rooms → Redirect correctly
- [ ] View promotions → Work
- [ ] Login/Register buttons → Work
- [ ] Responsive mobile → Check

---

## 1️⃣1️⃣ CLIENT - ROOM LISTING & DETAIL

### Danh sách Phòng
- [ ] Hiển thị đầy đủ room types
- [ ] Filter theo giá → Work
- [ ] Filter theo sức chứa → Work
- [ ] Filter theo tiện nghi → Work if implemented
- [ ] Sort by price → Work

### Chi tiết Phòng
- [ ] View room detail → Full info shown
- [ ] Image gallery → Work correctly
- [ ] Price displayed → Correct
- [ ] Amenities list → Displayed
- [ ] Reviews hiển thị → Show ratings & comments
- [ ] "Đặt ngay" button → Redirect to booking

---

## 1️⃣2️⃣ CLIENT - BOOKING PROCESS

### Tìm kiếm & Chọn phòng
- [ ] Nhập check-in/out dates → Validation work
- [ ] Check-in date < today → Show error ✅ (Already fixed)
- [ ] Check-out <= check-in → Show error ✅ (Already fixed)
- [ ] Select room type → Show available rooms
- [ ] No rooms available → Show message

### Form Đặt phòng
- [ ] **BUG TEST:** Checkbox "Tôi đồng ý..." required → ✅ (Fixed Bug #1)
- [ ] User info pre-filled → Correct
- [ ] Select specific rooms → Work
- [ ] Multiple rooms booking → Work

### Áp dụng Khuyến Mãi
- [ ] Nhập mã valid → Discount applied ✅
- [ ] Nhập mã invalid → Show error ✅
- [ ] Mã hết hạn → Show error ✅
- [ ] Mã đã đủ lượt dùng → Show error ✅ (Fixed Bug #4)
- [ ] User đã dùng tối đa → Show error ✅ (Fixed Bug #4)
- [ ] Price recalculation → Correct ✅ (Fixed Bug #3)

### Thanh toán
- [ ] Chọn "Thanh toán khi nhận phòng" → Booking created with unpaid status
- [ ] Chọn "Thanh toán online (VNPay)" → Redirect to VNPay
- [ ] VNPay success → Booking confirmed, invoice created, payment_status = paid
- [ ] VNPay cancel → Booking not created / status = pending
- [ ] **RACE CONDITION TEST:** 2 users book cùng phòng cùng lúc → ✅ (Fixed Bug #5)

### Success Page
- [ ] Show booking confirmation → Mã đặt phòng displayed
- [ ] Show payment info → Correct
- [ ] "Xem chi tiết" button → Redirect to booking detail

---

## 1️⃣3️⃣ CLIENT - PROFILE MANAGEMENT

### View Profile
- [ ] Hiển thị thông tin user → Correct
- [ ] Avatar hiển thị (nếu có) → Work

### Edit Profile
- [ ] Sửa name → Saved
- [ ] Sửa email → Check unique validation
- [ ] Sửa phone → Check unique validation
- [ ] Sửa CCCD → Saved
- [ ] Đổi password → Hashed & saved
- [ ] Upload avatar (nếu có) → Saved

### Profile Audit
- [ ] History changes được log → Check profile_audits table

---

## 1️⃣4️⃣ CLIENT - BOOKING HISTORY

### Danh sách Bookings
- [ ] Hiển thị tất cả bookings của user → Correct
- [ ] Filter theo status → Work
- [ ] View chi tiết booking → Show full info

### Chi tiết Booking
- [ ] Show rooms booked → Correct
- [ ] Show dates, price → Correct
- [ ] Show promotion used → Display
- [ ] Show payment info → Correct

### Cancel Booking
- [ ] User cancel booking pending → Status = cancelled
- [ ] User cancel booking confirmed → Check if allowed
- [ ] Nhập lý do hủy → Saved
- [ ] Notification to admin → Check

### View Invoice
- [ ] View hóa đơn → Full info displayed
- [ ] Print invoice → CSS OK, no header/footer
- [ ] Download PDF (nếu có) → Work

---

## 1️⃣5️⃣ CLIENT - REVIEW SYSTEM

### View Reviews
- [ ] Hiển thị reviews của room type → Correct
- [ ] Average rating calculation → Correct
- [ ] Sort by date → Work

### Add Review
- [ ] User có booking completed → Allowed
- [ ] User chưa booking → Prevented
- [ ] Rating 1-5 stars → Validation work
- [ ] Comment required → Check validation
- [ ] Submit review → Saved correctly

### Edit/Delete Review (nếu có)
- [ ] Edit own review → Saved
- [ ] Delete own review → Removed

---

## 1️⃣6️⃣ CLIENT - NOTIFICATIONS

### Danh sách Notifications
- [ ] Hiển thị all notifications → Correct
- [ ] Unread count badge → Update real-time
- [ ] Mark as read → Status updated

### Notification Types
- [ ] Booking status changed → Received
- [ ] Admin reply feedback → Received
- [ ] Password reset → Received
- [ ] Booking cancelled → Received
- [ ] Payment success → Received

### Actions
- [ ] Click notification → Mark as read
- [ ] Delete notification → Removed
- [ ] Bulk delete → Work
- [ ] "Xóa tất cả đã đọc" → Work

---

## 1️⃣7️⃣ CLIENT - CONTACT & FEEDBACK

### Contact Form
- [ ] Nhập name, email, message → Validation work
- [ ] Submit → Feedback created
- [ ] Email notification to admin → Check

### Feedback Status
- [ ] Admin mark handled → User sees status
- [ ] Admin reply → User receives email notification

---

## 🔒 SECURITY TESTING

### SQL Injection
- [ ] Nhập `' OR '1'='1` vào login → Blocked
- [ ] Nhập SQL commands vào search → Blocked
- [ ] Form inputs với special chars → Escaped correctly

### XSS (Cross-Site Scripting)
- [ ] Nhập `<script>alert('XSS')</script>` vào feedback → Escaped
- [ ] Nhập HTML tags vào name/comment → Escaped

### CSRF Protection
- [ ] Submit form without CSRF token → Blocked
- [ ] Expired CSRF token → Show error

### Authorization
- [ ] User access admin routes → Redirect to login
- [ ] User edit other user's booking → Prevented
- [ ] User delete other user's data → Prevented
- [ ] Admin bypass works correctly → Allow

### File Upload (nếu có)
- [ ] Upload .php file as image → Blocked
- [ ] Upload >2MB file → Blocked
- [ ] Upload invalid format → Blocked

---

## 🎨 UI/UX TESTING

### Responsive Design
- [ ] Desktop (1920x1080) → Layout OK
- [ ] Laptop (1366x768) → Layout OK
- [ ] Tablet (768x1024) → Layout OK
- [ ] Mobile (375x667) → Layout OK

### Browser Compatibility
- [ ] Chrome → Work
- [ ] Firefox → Work
- [ ] Safari → Work
- [ ] Edge → Work

### Performance
- [ ] Page load time < 3s → Check
- [ ] Images optimized → Check
- [ ] No console errors → Check

### Accessibility
- [ ] Form labels → Present
- [ ] Alt text for images → Present
- [ ] Keyboard navigation → Work
- [ ] Color contrast → Sufficient

---

## 📊 DATA VALIDATION TESTING

### Price Calculations
- [ ] Room price × nights = subtotal → Correct ✅
- [ ] Subtotal × 8% = VAT → Correct ✅
- [ ] Subtotal - discount + VAT = total → Correct ✅

### Date Validations
- [ ] Check-in >= today → Enforced ✅
- [ ] Check-out > check-in → Enforced ✅
- [ ] Promotion date range → Valid ✅

### Unique Constraints
- [ ] Email unique → Enforced
- [ ] Phone unique → Enforced
- [ ] CCCD unique → Enforced
- [ ] Room number unique → Enforced
- [ ] Promotion code unique → Enforced
- [ ] Invoice number unique → Enforced

---

## 🚀 INTEGRATION TESTING

### Email System
- [ ] Welcome email after register → Sent
- [ ] Password reset email → Sent
- [ ] Booking confirmation email → Sent
- [ ] Feedback reply email → Sent
- [ ] Notification emails → Sent

### VNPay Integration
- [ ] Payment redirect → Work
- [ ] Payment success callback → Work
- [ ] Payment cancel callback → Work
- [ ] Transaction logging → Work

### Google OAuth (nếu có)
- [ ] Login with Google → Work
- [ ] Register with Google → Work
- [ ] Link Google account → Work

---

## 📝 EDGE CASES & STRESS TESTING

### Concurrent Users
- [ ] 2 users book same room simultaneously → ✅ One blocked (Fixed Bug #5)
- [ ] 10 users browse site → Performance OK
- [ ] Multiple admins edit same data → Check behavior

### Large Data
- [ ] 1000+ bookings → Pagination work
- [ ] 100+ rooms → Performance OK
- [ ] Long text in feedback → Display OK

### Boundary Values
- [ ] Booking 365 days → Allowed?
- [ ] Booking same day check-in/out → Allowed?
- [ ] Price = 999,999,999 → Handle correctly
- [ ] Discount = 100% → Price = 0?

---

## ✅ SUMMARY

### Bugs Found So Far:
1. ✅ **Bug #1:** Terms & Conditions bypass → FIXED
2. ✅ **Bug #2:** Past dates allowed → FIXED
3. ✅ **Bug #3:** Price tampering → FIXED
4. ✅ **Bug #4:** Unlimited promotion usage → FIXED
5. ✅ **Bug #5:** Race condition → FIXED
6. ✅ **Bug #6:** Role không được lưu khi sửa user → FIXED

### Total Tests: ~300+ test cases
### Completed: 6 bugs found and fixed
### Remaining: ~294 test cases to perform manually

---

**Next Steps:**
1. Test từng section theo thứ tự
2. Ghi lại mọi bug phát hiện
3. Fix bugs ngay khi tìm ra
4. Re-test sau khi fix

**Testing Time Estimate:** 4-6 hours for complete manual testing

