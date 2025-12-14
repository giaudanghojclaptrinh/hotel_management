<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Mail\FeedbackReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $feedback = Feedback::create($data);

        // Send mail to configured admin email (ni emvui2233@gmail.com)
        try {
            Mail::to(config('mail.admin_address', 'niemvui2233@gmail.com'))
                ->send(new FeedbackReceived($feedback));
        } catch (\Exception $e) {
            // swallow - still store feedback
        }

        // If AJAX/JSON request, return JSON so client JS can handle it
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Cảm ơn phản hồi của bạn. Chúng tôi sẽ liên hệ sớm.']);
        }

        return back()->with('success', 'Cảm ơn phản hồi của bạn. Chúng tôi sẽ liên hệ sớm.');
    }

    // Admin: list feedbacks
    public function index()
    {
        $this->authorize('viewAny', Feedback::class);
        $feedbacks = Feedback::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    // Admin: show single
    public function show(Feedback $feedback)
    {
        $this->authorize('view', $feedback);
        return view('admin.feedbacks.show', compact('feedback'));
    }

    // Admin: mark handled
    public function markHandled(Feedback $feedback)
    {
        $this->authorize('update', $feedback);
        $feedback->update(['handled' => true, 'handled_at' => now()]);
        return back()->with('success', 'Đã đánh dấu đã xử lý.');
    }

    // Admin: bulk delete selected feedbacks
    public function bulkDelete(Request $request)
    {
        $this->authorize('viewAny', Feedback::class);

        $ids = $request->input('selected', []);
        if (!is_array($ids) || empty($ids)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Vui lòng chọn phản hồi để xóa.'], 422);
            }
            return back()->with('error', 'Vui lòng chọn phản hồi để xóa.');
        }

        Feedback::whereIn('id', $ids)->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Đã xóa phản hồi đã chọn.']);
        }

        return back()->with('success', 'Đã xóa phản hồi đã chọn.');
    }
}
