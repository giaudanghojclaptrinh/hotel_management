# Hướng Dẫn Test Chức Năng Remember Me

## Chuẩn Bị
1. Đảm bảo database đã có session table
2. Clear cache: `php artisan config:clear`
3. Khởi động server: `php artisan serve`

---

## Test Case 1: Đăng Nhập VỚI "Ghi Nhớ Đăng Nhập"

### Bước thực hiện:
1. Mở browser (ví dụ: Chrome)
2. Truy cập trang login: `http://localhost:8000/login`
3. Nhập thông tin:
   - Email: `user@example.com` (hoặc email test của bạn)
   - Password: `password`
   - **✅ Tích checkbox "Ghi nhớ đăng nhập (30 ngày)"**
4. Click "ĐĂNG NHẬP NGAY"

### Kiểm tra:
1. **Kiểm tra Cookie:**
   - Mở Developer Tools (F12) → Application → Cookies
   - Tìm cookie: `remember_web_[hash]`
   - Verify: 
     - Cookie tồn tại ✅
     - Expiry: ~30 ngày từ bây giờ ✅
     - HttpOnly: true ✅

2. **Kiểm tra Database:**
   ```sql
   SELECT id, name, email, remember_token 
   FROM users 
   WHERE email = 'user@example.com';
   ```
   - Verify: `remember_token` có giá trị (60 ký tự) ✅

3. **Test Remember:**
   - Đóng **hoàn toàn** trình duyệt (không chỉ tab)
   - Mở lại trình duyệt
   - Truy cập: `http://localhost:8000`
   - **Kết quả mong đợi:** Tự động đăng nhập, không cần nhập lại thông tin ✅

---

## Test Case 2: Đăng Nhập KHÔNG "Ghi Nhớ Đăng Nhập"

### Bước thực hiện:
1. Mở browser
2. Truy cập trang login
3. Nhập thông tin:
   - Email: `user@example.com`
   - Password: `password`
   - **❌ KHÔNG tích checkbox "Ghi nhớ đăng nhập"**
4. Click "ĐĂNG NHẬP NGAY"

### Kiểm tra:
1. **Kiểm tra Cookie:**
   - Mở Developer Tools → Application → Cookies
   - Verify: Cookie `remember_web_[hash]` KHÔNG tồn tại ❌

2. **Kiểm tra Database:**
   ```sql
   SELECT id, name, email, remember_token 
   FROM users 
   WHERE email = 'user@example.com';
   ```
   - Verify: `remember_token` = NULL hoặc empty ✅

3. **Test Session:**
   - Đóng trình duyệt
   - Mở lại trình duyệt
   - Truy cập: `http://localhost:8000`
   - **Kết quả mong đợi:** Phải đăng nhập lại ✅

---

## Test Case 3: Đăng Xuất (Logout)

### Bước thực hiện:
1. Đăng nhập với Remember Me (như Test Case 1)
2. Click vào avatar/menu user
3. Click "Đăng xuất"

### Kiểm tra:
1. **Kiểm tra Cookie:**
   - Cookie `remember_web_[hash]` bị xóa ✅

2. **Kiểm tra Database:**
   ```sql
   SELECT id, name, email, remember_token 
   FROM users 
   WHERE email = 'user@example.com';
   ```
   - Verify: `remember_token` = NULL ✅

3. **Test Re-login:**
   - Truy cập lại website
   - **Kết quả mong đợi:** Phải đăng nhập lại ✅

---

## Test Case 4: Remember Token trên Multiple Devices

### Bước thực hiện:
1. **Device 1 (Chrome):**
   - Đăng nhập với Remember Me
   - Lưu remember_token: copy từ database
   
2. **Device 2 (Firefox):**
   - Đăng nhập với Remember Me
   - Lưu remember_token: copy từ database

### Kiểm tra:
1. Remember token trên Chrome ≠ Remember token trên Firefox ✅
2. Cả 2 device đều có thể tự động đăng nhập ✅
3. Logout ở Chrome → Firefox vẫn đăng nhập ✅
4. Logout ở Firefox → Chrome vẫn đăng nhập ✅

---

## Test Case 5: Session Timeout (Không có Remember Me)

### Bước thực hiện:
1. Đăng nhập KHÔNG tick Remember Me
2. Chờ **2 giờ** không tương tác với website
   - (Hoặc set `SESSION_LIFETIME=1` trong .env để test nhanh)

### Kiểm tra:
1. Sau 2 giờ, reload trang
2. **Kết quả mong đợi:** Bị redirect về trang login ✅

---

## Test Case 6: Remember Token Expiration

### Bước thực hiện:
1. Đăng nhập với Remember Me
2. **Giả lập:** Update expiration trong database
   ```sql
   -- Set cookie expired (trong session table nếu dùng database driver)
   UPDATE sessions 
   SET last_activity = last_activity - 43200 
   WHERE user_id = [your_user_id];
   ```
3. Đóng và mở lại browser
4. Truy cập website

### Kiểm tra:
- **Kết quả mong đợi:** Phải đăng nhập lại (token hết hạn) ✅

---

## Debugging Commands

### Clear Everything:
```bash
# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Clear application cache
php artisan cache:clear

# Restart queue workers (nếu có)
php artisan queue:restart
```

### Database Queries:
```sql
-- Xem tất cả users và remember tokens
SELECT id, name, email, remember_token, created_at, updated_at 
FROM users;

-- Xem sessions đang active
SELECT * FROM sessions 
WHERE user_id IS NOT NULL;

-- Clear tất cả sessions
DELETE FROM sessions;
```

### Browser DevTools:
```javascript
// Xem tất cả cookies
document.cookie

// Xóa tất cả cookies
document.cookie.split(";").forEach(function(c) { 
    document.cookie = c.replace(/^ +/, "")
        .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
});

// Xem localStorage
console.log(localStorage);

// Xem sessionStorage
console.log(sessionStorage);
```

---

## Expected Results Summary

| Test Case | Checkbox Remember Me | Cookie Exists | Database Token | Auto Login After Browser Close |
|-----------|---------------------|---------------|----------------|-------------------------------|
| Test 1    | ✅ Tích             | ✅ Có         | ✅ Có giá trị  | ✅ Có                         |
| Test 2    | ❌ Không tích       | ❌ Không      | ❌ NULL        | ❌ Không                      |
| Test 3    | ✅ → Logout         | ❌ Bị xóa     | ❌ NULL        | ❌ Không                      |
| Test 4    | ✅ 2 devices        | ✅ Có         | ✅ Khác nhau   | ✅ Có (cả 2)                  |
| Test 5    | ❌ + Timeout        | ❌ Không      | ❌ NULL        | ❌ Không                      |
| Test 6    | ✅ + Expired        | ❌ Hết hạn    | ✅ Có nhưng cũ | ❌ Không                      |

---

## Troubleshooting

### Vấn đề: Cookie không được set
**Giải pháp:**
1. Kiểm tra `APP_KEY` trong .env
2. Run: `php artisan key:generate`
3. Clear browser cache
4. Thử incognito mode

### Vấn đề: Remember Me không hoạt động
**Giải pháp:**
1. Verify database có cột `remember_token`:
   ```sql
   DESCRIBE users;
   ```
2. Clear config:
   ```bash
   php artisan config:clear
   ```
3. Check session driver đang dùng gì

### Vấn đề: Logout nhưng vẫn đăng nhập
**Giải pháp:**
1. Check code logout có xóa remember token không
2. Clear tất cả cookies trong browser
3. Xóa session trong database

---

## Production Checklist

Trước khi deploy lên production:
- [ ] `SESSION_DRIVER=database` (hoặc redis)
- [ ] `SESSION_LIFETIME=43200` (30 ngày)
- [ ] `SESSION_EXPIRE_ON_CLOSE=false`
- [ ] `SESSION_SECURE_COOKIE=true` (với HTTPS)
- [ ] `SESSION_HTTP_ONLY=true`
- [ ] Setup cron job để cleanup sessions:
  ```bash
  php artisan session:gc
  ```

---

Ngày cập nhật: 15/12/2025
