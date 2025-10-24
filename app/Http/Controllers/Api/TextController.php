<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TextRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TextController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TextRecord::with('user');

        // فیلتر بر اساس عنوان
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // فیلتر بر اساس تاریخ
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($records);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $record = TextRecord::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'رکورد متنی با موفقیت ایجاد شد',
            'data' => $record->load('user')
        ], 201);
    }

    public function show(TextRecord $text): JsonResponse
    {
        return response()->json($text->load('user'));
    }

    public function update(Request $request, TextRecord $text): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $text->update($request->only(['title', 'description']));

        return response()->json([
            'message' => 'رکورد متنی با موفقیت به‌روزرسانی شد',
            'data' => $text->load('user')
        ]);
    }

    public function destroy(TextRecord $text): JsonResponse
    {
        $text->delete();

        return response()->json([
            'message' => 'رکورد متنی با موفقیت حذف شد'
        ]);
    }
}
