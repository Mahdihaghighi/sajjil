<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function financial(Request $request): JsonResponse
    {
        $query = FinancialRecord::with('user');

        // فیلتر بر اساس نوع
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // فیلتر بر اساس تاریخ
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // فیلتر بر اساس عنوان
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // فیلتر بر اساس کاربر
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        // محاسبه آمار
        $totalIncome = $records->where('type', 'income')->sum('amount');
        $totalExpense = $records->where('type', 'expense')->sum('amount');
        $netAmount = $totalIncome - $totalExpense;

        // آمار بر اساس نوع
        $incomeCount = $records->where('type', 'income')->count();
        $expenseCount = $records->where('type', 'expense')->count();

        // آمار بر اساس تاریخ (آخرین 30 روز)
        $last30Days = $records->where('created_at', '>=', now()->subDays(30));
        $last30DaysIncome = $last30Days->where('type', 'income')->sum('amount');
        $last30DaysExpense = $last30Days->where('type', 'expense')->sum('amount');

        return response()->json([
            'records' => $records,
            'summary' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'net_amount' => $netAmount,
                'income_count' => $incomeCount,
                'expense_count' => $expenseCount,
                'last_30_days_income' => $last30DaysIncome,
                'last_30_days_expense' => $last30DaysExpense,
            ],
            'chart_data' => [
                'daily' => $this->getDailyChartData($records),
                'monthly' => $this->getMonthlyChartData($records),
            ]
        ]);
    }

    private function getDailyChartData($records)
    {
        $dailyData = $records->groupBy(function ($record) {
            return $record->created_at->format('Y-m-d');
        })->map(function ($dayRecords) {
            return [
                'income' => $dayRecords->where('type', 'income')->sum('amount'),
                'expense' => $dayRecords->where('type', 'expense')->sum('amount'),
            ];
        });

        return $dailyData;
    }

    private function getMonthlyChartData($records)
    {
        $monthlyData = $records->groupBy(function ($record) {
            return $record->created_at->format('Y-m');
        })->map(function ($monthRecords) {
            return [
                'income' => $monthRecords->where('type', 'income')->sum('amount'),
                'expense' => $monthRecords->where('type', 'expense')->sum('amount'),
            ];
        });

        return $monthlyData;
    }
}
