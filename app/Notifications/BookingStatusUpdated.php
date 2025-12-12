<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\DatPhong;

class BookingStatusUpdated extends Notification
{
    use Queueable;

    protected $booking;
    protected $status;
    protected $message;

    /**
     * Tạo một thể hiện thông báo mới.
     * @param DatPhong $booking Đơn đặt phòng
     * @param string $status Trạng thái mới ('Đã xác nhận', 'Đã hủy', ...)
     * @param string $message Chi tiết thông báo
     */
    public function __construct(DatPhong $booking, $status, $message)
    {
        $this->booking = $booking;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Lấy các kênh phân phối thông báo.
     * Ta chỉ sử dụng kênh database.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Lấy biểu diễn mảng của thông báo (dữ liệu lưu vào cột 'data').
     */
    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'status' => $this->status,
            'message' => $this->message,
            'room_type' => $this->booking->chiTietDatPhongs->first()->loaiPhong->ten_loai_phong ?? 'N/A',
            'icon' => $this->getIcon(),
        ];
    }

    /**
     * Helper để lấy icon dựa trên trạng thái
     */
    protected function getIcon()
    {
        switch ($this->status) {
            case 'Đã xác nhận':
                return 'fa-check-circle text-green-500';
            case 'Đã hủy':
                return 'fa-times-circle text-red-500';
            case 'Thanh toán thành công':
                return 'fa-credit-card text-blue-500';
            default:
                return 'fa-bell text-yellow-500';
        }
    }
}