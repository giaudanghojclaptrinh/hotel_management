# Hệ Thống Ghi Nhớ Đăng Nhập (Remember Me)

## Tổng Quan
Đã cập nhật và tối ưu hóa chức năng "Ghi nhớ đăng nhập" cho hệ thống, cho phép người dùng duy trì trạng thái đăng nhập trong 30 ngày.

## Ngày Cập Nhật
15/12/2025

---

## 1. Cách Hoạt Động

### Khi Người Dùng KHÔNG tích "Ghi nhớ đăng nhập":
- ✅ Session sẽ hết hạn sau **2 giờ** không hoạt động
- ✅ Hoặc khi đóng trình duyệt (nếu cấu hình `SESSION_EXPIRE_ON_CLOSE=true`)

### Khi Người Dùng TÍCH "Ghi nhớ đăng nhập":
- ✅ Laravel tạo một **remember_token** lưu trong database
- ✅ Cookie `remember_web_[hash]` được lưu trên máy client
- ✅ Token tồn tại trong **43200 phút (30 ngày)**
- ✅ Người dùng không cần đăng nhập lại trong 30 ngày
- ✅ Ngay cả khi đóng trình duyệt, vẫn giữ đăng nhập

---

## 2. Các File Đã Cập Nhật

### A. LoginController
**File:** `app/Http/Controllers/Auth/LoginController.php`

#### Thay đổi:
```php
/**
 * Attempt to log the user into the application.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return bool
 */
protected function attemptLogin(Request $request)
{
    // Kiểm tra xem user có chọn "Remember Me" không
    // Nếu có, cookie sẽ tồn tại trong 43200 phút (30 ngày)
    // Nếu không, session sẽ hết hạn khi đóng browser
    return $this->guard()->attempt(
        $this->credentials($request),
        $request->filled('remember')
    );
}
```

#### Giải thích:
- `$request->filled('remember')` kiểm tra xem checkbox có được tích không
- `attempt()` method của Laravel Auth tự động xử lý remember token
- Nếu `true`: tạo remember token và lưu vào database + cookie
- Nếu `false`: chỉ tạo session thông thường

---

### B. File .env
**File:** `.env`

#### Cấu hình mới:
```dotenv
SESSION_DRIVER=database
SESSION_LIFETIME=43200          # 30 ngày (30 * 24 * 60 = 43200 phút)
SESSION_EXPIRE_ON_CLOSE=false   # Không tự động hết hạn khi đóng browser
```

#### Giải thích:
- `SESSION_DRIVER=database`: Lưu session vào database thay vì file (tốt hơn cho production)
- `SESSION_LIFETIME=43200`: Session tồn tại 30 ngày
- `SESSION_EXPIRE_ON_CLOSE=false`: Session không tự động xóa khi đóng browser

---

### C. Config Session
**File:** `config/session.php`

#### Cập nhật:
```php
/*
| Session Lifetime
|
| Default: 120 minutes (2 hours)
| With Remember Me: 43200 minutes (30 days)
*/

'lifetime' => (int) env('SESSION_LIFETIME', 43200),

'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
```

---

### D. Login View
**File:** `resources/views/auth/login.blade.php`

#### Cập nhật:
```blade
<div class="checkbox-wrapper">
    <input id="remember" name="remember" type="checkbox" 
           {{ old('remember') ? 'checked' : '' }} 
           class="checkbox-custom" 
           title="Giữ bạn đăng nhập trong 30 ngày">
    <label for="remember" class="checkbox-label" 
           title="Giữ bạn đăng nhập trong 30 ngày">
        Ghi nhớ đăng nhập (30 ngày)
    </label>
</div>
```

#### Cải tiến:
- ✅ Hiển thị rõ thời gian ghi nhớ: "(30 ngày)"
- ✅ Thêm tooltip khi hover: "Giữ bạn đăng nhập trong 30 ngày"
- ✅ Giữ trạng thái checkbox nếu form bị lỗi (`old('remember')`)

---

## 3. Cơ Chế Kỹ Thuật

### A. Database Schema
**Table: users**
```php
$table->rememberToken(); // Tạo cột 'remember_token' VARCHAR(100)
```

### B. User Model
**File:** `app/Models/User.php`
```php
protected $hidden = [
    'password',
    'remember_token',  // Ẩn khi serialize
];
```

### C. Cookie
Laravel tự động tạo cookie với tên: `remember_web_[hash]`
- **Thời gian sống:** 43200 phút (30 ngày)
- **HttpOnly:** true (Bảo mật, không thể truy cập qua JavaScript)
- **Encrypted:** true (Mã hóa tự động bởi Laravel)

---

## 4. Flow Hoạt Động

### Khi Đăng Nhập (với Remember Me):
```
1. User nhập email/password + tích checkbox "Ghi nhớ đăng nhập"
2. LoginController::attemptLogin() được gọi
3. Laravel kiểm tra thông tin đăng nhập
4. Nếu hợp lệ:
   a. Tạo session bình thường
   b. Generate random remember_token (60 ký tự)
   c. Lưu remember_token vào database (users.remember_token)
   d. Tạo cookie "remember_web_[hash]" với giá trị remember_token (encrypted)
   e. Gửi cookie về client browser
5. Chuyển hướng về trang home/dashboard
```

### Khi Truy Cập Lại (sau khi đóng browser):
```
1. User truy cập website (session đã hết hoặc không tồn tại)
2. Laravel Middleware kiểm tra cookie "remember_web_[hash]"
3. Nếu cookie tồn tại:
   a. Decrypt cookie để lấy remember_token
   b. Tìm user trong database với remember_token này
   c. Nếu tìm thấy:
      - Tạo session mới cho user
      - User tự động đăng nhập
   d. Nếu không tìm thấy:
      - Yêu cầu đăng nhập lại
4. User được đăng nhập tự động, không cần nhập lại thông tin
```

---

## 5. Bảo Mật

### A. Remember Token
- ✅ **Random:** 60 ký tự ngẫu nhiên
- ✅ **Unique:** Mỗi user có token riêng
- ✅ **Hashed:** Được mã hóa trong cookie
- ✅ **Updated:** Token mới được tạo mỗi lần đăng nhập

### B. Cookie Security
- ✅ **HttpOnly:** Không thể truy cập qua JavaScript (chống XSS)
- ✅ **Encrypted:** Laravel tự động mã hóa
- ✅ **Secure:** Nên bật HTTPS trong production
- ✅ **SameSite:** Chống CSRF attacks

### C. Session Security
- ✅ **Database Storage:** Session được lưu trong database
- ✅ **Regenerate:** Session ID được tạo mới sau khi đăng nhập
- ✅ **Expiration:** Tự động xóa session hết hạn

---

## 6. Đăng Xuất

### Khi User Logout:
```php
// Laravel tự động thực hiện:
Auth::logout();                          // 1. Xóa session
$request->session()->invalidate();       // 2. Vô hiệu hóa session
$request->session()->regenerateToken();  // 3. Tạo CSRF token mới

// Remember token cũng bị xóa:
$user->remember_token = null;
$user->save();

// Cookie "remember_web_[hash]" bị xóa
```

---

## 7. Testing Checklist

### ✅ Test Cases:

#### Test 1: Đăng nhập với Remember Me
- [ ] Đăng nhập + tích checkbox "Ghi nhớ đăng nhập"
- [ ] Kiểm tra cookie `remember_web_[hash]` có tồn tại không
- [ ] Kiểm tra database: `users.remember_token` có giá trị
- [ ] Đóng trình duyệt và mở lại
- [ ] Truy cập website → Phải tự động đăng nhập

#### Test 2: Đăng nhập không Remember Me
- [ ] Đăng nhập + KHÔNG tích checkbox
- [ ] Kiểm tra cookie `remember_web_[hash]` KHÔNG tồn tại
- [ ] Kiểm tra database: `users.remember_token` = NULL
- [ ] Đóng trình duyệt và mở lại
- [ ] Truy cập website → Phải yêu cầu đăng nhập lại

#### Test 3: Remember Token Expiration
- [ ] Đăng nhập với Remember Me
- [ ] Đợi 30 ngày (hoặc thay đổi thời gian trong config để test)
- [ ] Cookie hết hạn → Phải đăng nhập lại

#### Test 4: Logout
- [ ] Đăng nhập với Remember Me
- [ ] Click Logout
- [ ] Cookie `remember_web_[hash]` bị xóa
- [ ] Database: `users.remember_token` = NULL
- [ ] Truy cập lại → Phải đăng nhập từ đầu

#### Test 5: Multiple Devices
- [ ] Đăng nhập trên Chrome với Remember Me
- [ ] Đăng nhập trên Firefox với Remember Me
- [ ] Cả 2 browser đều giữ đăng nhập
- [ ] Remember token khác nhau trên mỗi device

---

## 8. Best Practices

### Development:
```dotenv
SESSION_LIFETIME=120          # 2 giờ (cho testing)
SESSION_EXPIRE_ON_CLOSE=true  # Tự động logout khi đóng browser
```

### Production:
```dotenv
SESSION_LIFETIME=43200        # 30 ngày
SESSION_EXPIRE_ON_CLOSE=false # Giữ session khi đóng browser
SESSION_SECURE_COOKIE=true    # Chỉ gửi cookie qua HTTPS
```

---

## 9. Troubleshooting

### Vấn đề: Remember Me không hoạt động
**Giải pháp:**
1. Kiểm tra database có cột `remember_token` không
2. Kiểm tra Model User có `use Authenticatable` trait
3. Clear cache: `php artisan config:clear`
4. Xóa session cũ: `php artisan session:clear` (nếu có)

### Vấn đề: Cookie không được set
**Giải pháp:**
1. Kiểm tra `APP_KEY` trong .env đã được set
2. Kiểm tra cookie settings trong `config/session.php`
3. Kiểm tra browser có block cookie không
4. Clear browser cache và cookies

### Vấn đề: Token bị reset liên tục
**Giải pháp:**
1. Đảm bảo không có code nào gọi `Auth::logout()` không mong muốn
2. Kiểm tra middleware có conflict không
3. Kiểm tra không có nhiều instance app chạy đồng thời

---

## 10. Performance Notes

### Database Sessions:
- ✅ **Pros:** 
  - Tốt hơn cho multiple servers
  - Dễ monitor và quản lý
  - Có thể query user sessions
  
- ⚠️ **Cons:**
  - Tăng load database nhẹ
  - Cần cleanup session expired định kỳ

### Cleanup Command:
```bash
# Tự động xóa session hết hạn
php artisan session:gc
```

Nên thêm vào cron job:
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('session:gc')->daily();
}
```

---

## 11. Security Recommendations

### Production Checklist:
- [ ] Bật HTTPS
- [ ] Set `SESSION_SECURE_COOKIE=true`
- [ ] Set `SESSION_HTTP_ONLY=true`
- [ ] Set `SESSION_SAME_SITE=lax`
- [ ] Implement rate limiting cho login
- [ ] Monitor failed login attempts
- [ ] Implement 2FA nếu cần thiết

---

## Tác Giả
GitHub Copilot - Claude Sonnet 4.5

## Phiên Bản
1.0.0 - 15/12/2025
