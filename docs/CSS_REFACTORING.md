# CSS REFACTORING DOCUMENTATION

## Problem Statement
All CSS files were being loaded simultaneously in `layouts/app.blade.php` causing:
- CSS variable conflicts (multiple `:root` declarations)
- Layout breaking issues (header/footer missing on non-home pages)
- Performance overhead
- Cascade conflicts between pages

## Solution Implemented

### 1. Created Global Variables File
**File**: `resources/css/client/variables.css` (113 lines)
- Centralized all common CSS variables
- Includes colors, fonts, spacing, shadows
- Dark luxury theme with gold accents (#cda45e)
- Global reset, container, scrollbar styling

### 2. Created Layout CSS/JS Files (NEW!)
**Files**: 
- `resources/css/client/layout.css` (600+ lines)
- `resources/js/client/layout.js` (70+ lines)

**Purpose**: Shared header, footer, and navigation across all pages
**Contains**:
- Header wrapper, logo, navigation menu
- User dropdown and mobile toggle
- Footer sections (brand, links, contact, newsletter)
- Flash messages styling
- Mobile menu responsive layout
- Header sticky scroll behavior
- Mobile menu toggle functionality
- Flash message auto-hide

**Why Separate?**: Header and footer are used on every page, so they must be loaded globally. Previously they were in home.css/home.js causing them to only appear on the home page.

### 3. Updated Layout File
**File**: `resources/views/layouts/app.blade.php`
- Loads globally:
  - `variables.css` - CSS variables
  - `layout.css` - Header/footer/nav CSS
  - `layout.js` - Header/footer/nav JS
  - `tailwind-config.js` - Tailwind configuration
- Added `@stack('styles')` for per-page CSS loading

### 4. Cleaned Individual CSS Files
Removed duplicate `:root` declarations from:
- ✅ `resources/css/client/home.css` - Also removed header/footer CSS (now in layout.css)
- ✅ `resources/css/client/invoice.css`
- ✅ `resources/css/login.css`
- ✅ `resources/css/register.css`
- ✅ `resources/css/password.css`

### 5. Cleaned Individual JS Files
- ✅ `resources/js/client/home.js` - Removed header/footer JS (now in layout.js)
Added `@vite` directives to load page-specific CSS/JS:

#### Auth Pages
- ✅ `auth/login.blade.php` → login.css + login.js
- ✅ `auth/register.blade.php` → register.css + register.js

#### Client Pages
- ✅ `home.blade.php` → home.css + home.js
- ✅ `client/rooms/index.blade.php` → rooms.css + rooms.js
- ✅ `client/rooms/detail.blade.php` → rooms.css + rooms.js
- ✅ `client/profile/edit.blade.php` → profile.css + profile.js
- ✅ `client/booking/create.blade.php` → booking.css + booking.js
- ✅ `client/booking/history.blade.php` → history.css
- ✅ `client/booking/detail.blade.php` → booking-detail.css (already had it)
- ✅ `client/booking/invoice.blade.php` → invoice.css + invoice.js (already had it)
- ✅ `client/about/index.blade.php` → about.css + about.js
- ✅ `client/contact/index.blade.php` → contact.css + contact.js
- ✅ `client/promotions/index.blade.php` → promo.css + promo.js
- ✅ `client/notifications/index.blade.php` → notifications.css + notifications.js

## CSS Variables Available Globally

```css
/* Colors */
--primary-gold: #cda45e;
--primary-hover: #d9b876;
--primary-dark: #8a6d20;
--bg-body: #1a1a1a;
--bg-card: rgba(255, 255, 255, 0.03);
--bg-card-hover: rgba(255, 255, 255, 0.07);
--bg-overlay: rgba(0, 0, 0, 0.7);
--text-main: #ffffff;
--text-muted: #a3a3a3;
--text-dark: #111827;
--border-light: rgba(255, 255, 255, 0.1);
--border-gold: rgba(205, 164, 94, 0.3);

/* Fonts */
--font-serif: 'Playfair Display', serif;
--font-sans: 'Be Vietnam Pro', sans-serif;

/* Layout */
--container-width: 1280px;
--radius-md: 8px;
--radius-lg: 16px;

/* Shadows */
--shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
--shadow-glow: 0 0 20px rgba(205, 164, 94, 0.15);

/* Auth-specific variables */
--primary-color: #cda45e;
--card-bg: rgba(0, 0, 0, 0.6);
--card-border: 1px solid rgba(205, 164, 94, 0.2);
--input-bg: rgba(255, 255, 255, 0.05);
--input-border: 1px solid rgba(255, 255, 255, 0.1);
--input-focus: #cda45e;
--error-color: #ef4444;
--success-bg: rgba(16, 185, 129, 0.2);
--success-text: #34d399;
--transition: all 0.3s ease;
```

## Benefits

1. **No More CSS Conflicts**: Each page loads only its required CSS
2. **Better Performance**: Smaller CSS bundles per page
3. **Easier Maintenance**: Variables centralized in one file
4. **Consistent Theme**: All pages use same color palette
5. **Clean Code**: No duplicate :root declarations

## Usage for New Pages

When creating a new page:

1. Create page-specific CSS file (if needed)
2. Use global variables from `variables.css`
3. Add `@vite` directive in blade template:
   ```php
   @vite(['resources/css/client/your-page.css', 'resources/js/client/your-page.js'])
   ```

## Testing Checklist

- [ ] Home page loads correctly
- [ ] Login/Register pages work
- [ ] Room listing and detail pages display properly
- [ ] Booking flow (create, history, detail, invoice) functions
- [ ] Profile page edits successfully
- [ ] About, Contact, Promotions pages load
- [ ] Notifications page works
- [ ] All buttons and forms are styled correctly
- [ ] Dark theme is consistent across all pages

## Notes

- Alpine.js is still loaded globally via CDN
- Tailwind config is loaded globally
- Individual pages can override global variables if needed
- All CSS files now reference global variables instead of redefining them
