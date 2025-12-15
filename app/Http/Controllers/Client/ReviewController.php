<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\LoaiPhong;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReplyReceived;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'parent_id' => 'nullable|exists:reviews,id',
        ]);

        // require either rating or comment for top-level; for replies require comment
        $isReply = !empty($data['parent_id']);

        if ($isReply) {
            if (empty($data['comment'])) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Vui lòng nhập nội dung trả lời.'], 422);
                }
                return back()->withErrors(['review' => 'Vui lòng nhập nội dung trả lời.'])->withInput();
            }
        } else {
            if (empty($data['rating']) && empty($data['comment'])) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Vui lòng cung cấp đánh giá (số sao) hoặc viết bình luận.'], 422);
                }
                return back()->withErrors(['review' => 'Vui lòng cung cấp đánh giá (số sao) hoặc viết bình luận.'])->withInput();
            }
        }

        $data['user_id'] = Auth::id();

        // Normalize rating: if provided and not empty, cast to int; otherwise leave unset
        if (array_key_exists('rating', $data) && $data['rating'] !== null && $data['rating'] !== '') {
            $data['rating'] = (int) $data['rating'];
        } else {
            unset($data['rating']);
        }

        // If this is a reply (parent_id present) -> create reply directly
        if ($isReply) {
            $create = [
                'user_id' => $data['user_id'],
                'loai_phong_id' => $data['loai_phong_id'],
                'comment' => $data['comment'],
                'parent_id' => $data['parent_id'],
                'rating' => 0,
            ];

            try {
                $reply = Review::create($create);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Không thể lưu trả lời, vui lòng thử lại.'], 500);
                }
                return back()->with('error', 'Không thể lưu trả lời, vui lòng thử lại.')->withInput();
            }

            // Notify the original commenter if it's not the same user
            try {
                $parent = Review::find($data['parent_id']);
                if ($parent && $parent->user && $parent->user->id !== $data['user_id']) {
                    $parent->user->notify(new ReplyReceived($reply));
                }
            } catch (\Exception $e) {
                // swallow notification errors
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Đã gửi trả lời.']);
            }
            return back()->with('success', 'Đã gửi trả lời.');
        }

        // TOP-LEVEL HANDLING: separate rating from comments.
        // 1) If rating is provided -> create a single rating record for this user+room only if none exists yet
        if (array_key_exists('rating', $data) && isset($data['rating']) && $data['rating'] > 0) {
            $ratingRecord = Review::where('user_id', $data['user_id'])
                ->where('loai_phong_id', $data['loai_phong_id'])
                ->whereNull('parent_id')
                ->where('rating', '>', 0)
                ->first();

            if (!$ratingRecord) {
                try {
                    Review::create([
                        'user_id' => $data['user_id'],
                        'loai_phong_id' => $data['loai_phong_id'],
                        'rating' => (int) $data['rating'],
                        'comment' => null,
                        'parent_id' => null,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Không thể lưu đánh giá, vui lòng thử lại.'], 500);
                    }
                    return back()->with('error', 'Không thể lưu đánh giá, vui lòng thử lại.')->withInput();
                }
            }

            // If a comment was provided alongside the rating, always create it as a separate top-level comment
            if (!empty($data['comment'])) {
                try {
                    Review::create([
                        'user_id' => $data['user_id'],
                        'loai_phong_id' => $data['loai_phong_id'],
                        'comment' => $data['comment'],
                        'rating' => 0,
                        'parent_id' => null,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // ignore secondary comment creation error
                }
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Cảm ơn bạn đã gửi đánh giá.']);
            }
            return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá.');
        }

        // 2) If only comment provided -> always create a separate top-level comment (rating=0)
        if (!empty($data['comment'])) {
            try {
                Review::create([
                    'user_id' => $data['user_id'],
                    'loai_phong_id' => $data['loai_phong_id'],
                    'comment' => $data['comment'],
                    'rating' => 0,
                    'parent_id' => null,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Không thể lưu bình luận, vui lòng thử lại.'], 500);
                }
                return back()->with('error', 'Không thể lưu bình luận, vui lòng thử lại.')->withInput();
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Cảm ơn bạn đã gửi bình luận.']);
            }
            return back()->with('success', 'Cảm ơn bạn đã gửi bình luận.');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Không có dữ liệu để lưu.'], 422);
        }
        return back();
    }
}
