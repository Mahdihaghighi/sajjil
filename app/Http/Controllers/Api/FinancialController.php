<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FinancialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = FinancialRecord::where('user_id', $request->user()->id);

        // Filter by title if provided
        if ($request->has('title') && $request->title) {
            $query->where('title', $request->title);
        }

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // Sort by amount if provided
        if ($request->has('sort') && $request->sort) {
            if ($request->sort === 'highest') {
                $query->orderBy('amount', 'desc');
            } elseif ($request->sort === 'lowest') {
                $query->orderBy('amount', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $records = $query->paginate($perPage);

        // Get all unique titles for filter
        $allTitles = FinancialRecord::where('user_id', $request->user()->id)
            ->distinct()
            ->pluck('title')
            ->toArray();

        return response()->json([
            'data' => $records->items(),
            'current_page' => $records->currentPage(),
            'last_page' => $records->lastPage(),
            'per_page' => $records->perPage(),
            'total' => $records->total(),
            'has_more' => $records->hasMorePages(),
            'all_titles' => $allTitles
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
        ]);

        $record = FinancialRecord::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'type' => $request->type,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'رکورد مالی با موفقیت ایجاد شد',
            'data' => $record->load('user')
        ], 201);
    }

    public function show(Request $request, FinancialRecord $finance): JsonResponse
    {
        // Debug: Check authentication
        if (!$request->user()) {
            return response()->json(['message' => 'کاربر احراز هویت نشده'], 401);
        }
        
        // Debug: Log user info
        \Log::info('User ID: ' . $request->user()->id);
        \Log::info('Record User ID: ' . $finance->user_id);
        
        // Allow access if record is orphan or belongs to user; otherwise forbid
        if ($finance->user_id !== null && $finance->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'دسترسی غیرمجاز',
                'debug' => [
                    'record_user_id' => $finance->user_id,
                    'current_user_id' => $request->user()->id
                ]
            ], 403);
        }
        
        // Optionally attach orphan records to current user (safe update query)
        if ($finance->user_id === null) {
            FinancialRecord::whereKey($finance->getKey())
                ->update(['user_id' => $request->user()->id]);
            $finance->user_id = $request->user()->id;
        }
        
        return response()->json($finance);
    }

    public function update(Request $request, FinancialRecord $finance): JsonResponse
    {
        // Debug: Check authentication
        if (!$request->user()) {
            return response()->json(['message' => 'کاربر احراز هویت نشده'], 401);
        }
        
        // Forbid only if record belongs to another user; allow if orphan
        if ($finance->user_id !== null && $finance->user_id !== $request->user()->id) {
            return response()->json(['message' => 'دسترسی غیرمجاز'], 403);
        }
        
        // Attach orphan record to current user before update (safe update query)
        if ($finance->user_id === null) {
            FinancialRecord::whereKey($finance->getKey())
                ->update(['user_id' => $request->user()->id]);
            $finance->user_id = $request->user()->id;
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
        ]);

        $finance->update($request->only(['title', 'amount', 'type']));

        return response()->json([
            'message' => 'رکورد مالی با موفقیت به‌روزرسانی شد',
            'data' => $finance
        ]);
    }

    public function destroy(Request $request, FinancialRecord $finance): JsonResponse
    {
        // Debug: Check authentication
        if (!$request->user()) {
            return response()->json(['message' => 'کاربر احراز هویت نشده'], 401);
        }
        
        // Forbid only if record belongs to another user; allow if orphan
        if ($finance->user_id !== null && $finance->user_id !== $request->user()->id) {
            return response()->json(['message' => 'دسترسی غیرمجاز'], 403);
        }
        
        // Attach orphan record to current user before delete (safe update query)
        if ($finance->user_id === null) {
            FinancialRecord::whereKey($finance->getKey())
                ->update(['user_id' => $request->user()->id]);
            $finance->user_id = $request->user()->id;
        }

        $finance->delete();

        return response()->json([
            'message' => 'رکورد مالی با موفقیت حذف شد'
        ]);
    }
}
