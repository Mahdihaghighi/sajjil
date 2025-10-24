<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use App\Models\TextRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SuggestionController extends Controller
{
    public function titles(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:3',
            'type' => 'required|in:financial,text'
        ]);

        $query = $request->input('query');
        $type = $request->input('type');

        if ($type === 'financial') {
            $suggestions = FinancialRecord::where('title', 'like', '%' . $query . '%')
                ->select('title')
                ->distinct()
                ->limit(3)
                ->get()
                ->pluck('title');
        } else {
            $suggestions = TextRecord::where('title', 'like', '%' . $query . '%')
                ->select('title')
                ->distinct()
                ->limit(3)
                ->get()
                ->pluck('title');
        }

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }
}
