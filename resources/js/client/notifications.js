import '../bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    console.log('Notifications Logic Loaded');

    // --- DOM Elements ---
    const selectAllCheckbox = document.getElementById('select-all-notifications');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const checkboxes = document.querySelectorAll('.notification-checkbox');

    // Helper: Cập nhật trạng thái nút xóa
    const updateBulkState = () => {
        const selectedCount = document.querySelectorAll('.notification-checkbox:checked').length;
        if (bulkDeleteBtn) {
            bulkDeleteBtn.disabled = selectedCount === 0;
            // Cập nhật text nút (Optional)
            // bulkDeleteBtn.innerHTML = `<i class="fa-solid fa-trash-can mr-1"></i> Xóa (${selectedCount})`;
        }
    };

    // 1. Logic Chọn tất cả
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            checkboxes.forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkState();
        });
    }

    // 2. Logic checkbox lẻ
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            // Nếu bỏ tick 1 cái thì bỏ tick "Chọn tất cả"
            if (!cb.checked && selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            // Nếu tick hết thì tick "Chọn tất cả"
            const allChecked = Array.from(checkboxes).every(c => c.checked);
            if (allChecked && selectAllCheckbox) {
                selectAllCheckbox.checked = true;
            }
            updateBulkState();
        });
    });

    // 3. Logic Xóa hàng loạt (AJAX)
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', async function() {
            // Lấy danh sách ID
            const selectedIds = Array.from(document.querySelectorAll('.notification-checkbox:checked'))
                .map(cb => cb.dataset.id);

            if (selectedIds.length === 0) return;

            if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} thông báo đã chọn?`)) return;

            // Hiệu ứng loading
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xóa...';

            try {
                // Lấy URL từ data attribute (đã thêm trong blade)
                const deleteUrl = this.dataset.route;

                const response = await axios.post(deleteUrl, {
                    ids: selectedIds
                });

                if (response.data.success) {
                    // Xóa DOM
                    selectedIds.forEach(id => {
                        const item = document.querySelector(`.notify-item[data-id="${id}"]`);
                        if (item) {
                            item.style.opacity = '0';
                            setTimeout(() => item.remove(), 300);
                        }
                    });

                    // Reset trạng thái
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;

                    // Thông báo thành công (Có thể dùng Toast nếu có, ở đây dùng alert đơn giản hoặc reload)
                    // location.reload(); // Reload để cập nhật lại phân trang và số lượng trên header

                    // Cập nhật lại UI nếu không reload
                    setTimeout(updateBulkState, 300);

                } else {
                    alert(response.data.message || 'Có lỗi xảy ra.');
                }
            } catch (error) {
                console.error(error);
                alert('Lỗi kết nối server.');
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
                // Nếu xóa hết trang hiện tại, nên reload
                if (document.querySelectorAll('.notify-item').length === selectedIds.length) {
                    window.location.reload();
                }
            }
        });
    }
});