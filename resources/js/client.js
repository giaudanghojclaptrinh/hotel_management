import './bootstrap';

// Log để kiểm tra file đã load thành công
console.log('Tài nguyên Khách hàng (Client Assets) đã tải thành công!');

// --- CÁC SCRIPT TÙY CHỈNH CHO CLIENT ---

// 1. Hiệu ứng Header khi cuộn trang (Sticky Header Effect)
// Khi cuộn xuống > 50px, header sẽ đổi màu nền để nội dung dễ đọc hơn
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
        header.classList.add('shadow-md', 'bg-white/95'); // Thêm bóng và nền trắng mờ
        header.classList.remove('bg-transparent'); // Xóa nền trong suốt (nếu có)
    } else {
        header.classList.remove('shadow-md', 'bg-white/95');
        // Nếu trang chủ có banner full màn hình, bạn có thể muốn header trong suốt ở đây
    }
});

// 2. Tự động ẩn thông báo Flash Message sau 5 giây (Hỗ trợ thêm cho AlpineJS)
// Lưu ý: Nếu bạn dùng AlpineJS trong blade (x-init) thì đoạn này có thể không cần thiết, 
// nhưng giữ lại để dự phòng cho các thông báo không dùng Alpine.
document.addEventListener('DOMContentLoaded', () => {
    // Tìm phần tử có id="flash-message" (Bạn cần thêm id này vào thẻ div thông báo trong file layout nếu muốn dùng JS thuần)
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.transition = "opacity 0.5s ease";
            flashMessage.style.opacity = "0";
            setTimeout(() => flashMessage.remove(), 500); // Xóa khỏi DOM sau khi mờ dần
        }, 5000);
    }
});