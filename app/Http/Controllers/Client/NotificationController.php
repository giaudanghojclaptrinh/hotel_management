<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Hiển thị danh sách thông báo của User (chưa đọc & đã đọc).
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Lấy tất cả thông báo, sắp xếp theo thời gian mới nhất
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10);
        
        // Đánh dấu các thông báo CHƯA ĐỌC thành ĐÃ ĐỌC (để lần sau vào trang này không bị đếm lại)
        $user->unreadNotifications->markAsRead();

        return view('client.notifications.index', compact('notifications'));
    }

    /**
     * API: Đánh dấu một thông báo cụ thể là đã đọc (thường dùng cho dropdown).
     */
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            
            if ($notification->unread()) {
                $notification->markAsRead();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Notification not found or unowned.'], 404);
        }
    }

    /**
     * API: Lấy số lượng thông báo chưa đọc (dùng cho icon bell trên header).
     */
    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        $count = Auth::user()->unreadNotifications->count();
        return response()->json(['count' => $count]);
    }

    /**
     * API: Xóa một thông báo cụ thể. (Không dùng cho View hiện tại nhưng vẫn giữ để Route không lỗi)
     */
    public function delete($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        try {
            $deleted = Auth::user()->notifications()->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Thông báo đã được xóa thành công.']);
            }
            
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông báo.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi server khi xóa thông báo.'], 500);
        }
    }
    
    /**
     * API: Xóa nhiều thông báo cụ thể. (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        // Validate rằng ids là một mảng và các phần tử là chuỗi (UUIDs)
        $request->validate(['ids' => 'required|array', 'ids.*' => 'string']);

        try {
            $ids = $request->input('ids');
            $deletedCount = Auth::user()
                                ->notifications()
                                ->whereIn('id', $ids)
                                ->delete();
            
            return response()->json([
                'success' => true, 
                'message' => "Đã xóa thành công {$deletedCount} thông báo.",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi server khi xóa hàng loạt.'], 500);
        }
    }
}