document.addEventListener('DOMContentLoaded', () => {
    console.log('Invoice Details Script Loaded.');

    const backLink = document.getElementById('back-to-previous');
    const printButton = document.getElementById('print-invoice-btn');

    // 1. Logic cho nút Quay lại (Chỉ cần lắng nghe sự kiện click)
    if (backLink) {
        backLink.addEventListener('click', function(e) {
            e.preventDefault();
            // Sử dụng history.back() để đảm bảo người dùng quay lại trang trước đó
            history.back();
        });
    }

    // 2. Logic cho nút In
    if (printButton) {
        printButton.addEventListener('click', function() {
            // Gọi hàm in của trình duyệt
            window.print();
        });
    }
});