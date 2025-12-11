document.addEventListener('DOMContentLoaded', () => {
    console.log('Rooms Logic Loaded');

    // ==========================================
    // 1. XỬ LÝ BỘ LỌC MOBILE (Sidebar Filter Toggle)
    // ==========================================
    // Logic này hỗ trợ nút "Bộ lọc tìm kiếm" trên mobile để hiện sidebar
    const mobileFilterBtn = document.querySelector('.mobile-filter-btn');
    const sidebarFilter = document.querySelector('.sidebar-filter');

    if (mobileFilterBtn && sidebarFilter) {
        mobileFilterBtn.addEventListener('click', () => {
            sidebarFilter.classList.toggle('active');

            // Cuộn lên đầu lọc để user thấy
            if (sidebarFilter.classList.contains('active')) {
                sidebarFilter.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // ==========================================
    // 2. LOGIC DATEPICKER (Trang Chi tiết phòng)
    // ==========================================
    // Tự động cập nhật min-date của ngày trả phòng dựa trên ngày nhận phòng
    const checkinInput = document.getElementById('checkin_date');
    const checkoutInput = document.getElementById('checkout_date');
    const bookingForm = document.getElementById('booking-date-form');

    if (checkinInput && checkoutInput) {
        checkinInput.addEventListener('change', function() {
            if (this.value) {
                const checkinDate = new Date(this.value);
                // Ngày trả phòng ít nhất phải sau ngày nhận phòng 1 ngày
                checkinDate.setDate(checkinDate.getDate() + 1);

                const minCheckout = checkinDate.toISOString().split('T')[0];
                checkoutInput.min = minCheckout;

                // Nếu ngày checkout hiện tại ko hợp lệ thì reset
                if (checkoutInput.value && checkoutInput.value <= this.value) {
                    checkoutInput.value = minCheckout;
                }
            }
        });

        // Validate form trước khi submit
        if (bookingForm) {
            bookingForm.addEventListener('submit', function(e) {
                const checkin = checkinInput.value;
                const checkout = checkoutInput.value;

                if (!checkin || !checkout) {
                    e.preventDefault();
                    alert('Vui lòng chọn đầy đủ ngày nhận và trả phòng!');
                    return;
                }

                if (new Date(checkin) >= new Date(checkout)) {
                    e.preventDefault();
                    alert('Ngày trả phòng phải lớn hơn ngày nhận phòng ít nhất 1 ngày!');
                }
            });
        }
    }
});