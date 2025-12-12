Asset cleanup summary

Mục tiêu
- Gom tất cả CSS/JS client vào 2 manifest: `resources/css/client/app.css` và `resources/js/client/app.js`.
- Comment (không xóa) các include per-view đã thừa để dễ khôi phục.

Files changed (views where per-view @vite includes were commented)
- resources/views/auth/register.blade.php
  - Commented `@vite(['resources/css/register.css'])` and `@vite(['resources/js/register.js'])` — vì đã import vào `app.css` / `app.js`.
- resources/views/auth/login.blade.php
  - Commented `@vite(['resources/css/login.css'])` and the `@push('scripts')` block that included `resources/js/login.js`.
- resources/views/auth/passwords/reset.blade.php
  - Commented `@vite(['resources/css/password.css', 'resources/js/password.js'])`.
- resources/views/auth/passwords/email.blade.php
  - Commented `@vite(['resources/css/password.css', 'resources/js/password.js'])`.
- resources/views/auth/passwords/confirm.blade.php
  - Commented `@vite(['resources/css/password.css', 'resources/js/password.js'])`.
- resources/views/client/booking/history.blade.php
  - Commented the duplicate `home.css` include earlier and left only `history.css` (kept comment explaining rationale).

Centralized manifest files added
- resources/css/client/app.css — imports per-page CSS via CSS `@import`.
- resources/js/client/app.js — imports per-page JS modules via ES imports.

Layout update
- resources/views/layouts/app.blade.php
  - Replaced multiple per-page entries in the `@vite([...])` call with:
    - `'resources/js/tailwind-config.js'`
    - `'resources/css/client/app.css'`
    - `'resources/js/client/app.js'`
  - This reduces duplicate loads and makes maintenance easier.

How to revert or customize
- To exclude a CSS/JS from the global bundle (load only on specific page):
  1. Remove its `@import` (CSS) or `import` (JS) from the manifest files.
  2. Uncomment the per-view `@vite([...])` call in that view file (see commented lines in views).

Notes / Caveats
- Vite will still build all referenced assets; after these changes run your usual build/dev commands:

  npm run dev
  npm run build

- The CSS `@import` paths assume the current structure; if you reorganize files, update imports accordingly.
- The JS manifest imports expect the referenced JS files to use ES module exports or side-effect initialization. If a script assumes it is loaded only on a specific page and references DOM elements immediately, you may see errors when it runs on pages without those elements. In that case move initialization behind DOM checks or load that script only on specific pages.

If bạn muốn, mình có thể tiếp tục:
- Bổ sung một file CSS nhỏ để style pagination (ví dụ `resources/css/client/pagination.css`) và import vào `app.css`.
- Chạy một lần `npm run build` hoặc `npm run dev` (cần môi trường Node trên máy bạn) để kiểm tra bundle.
- Tách một số script khỏi `app.js` nếu gặp lỗi side-effect trên trang không có DOM element tương ứng.

