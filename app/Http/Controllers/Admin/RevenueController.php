<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RevenueReportExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RevenueController extends Controller
{
    private const MAX_DAYS_FOR_DAILY_CHART = 120;

    private const MAX_POINTS_DAILY = 60;

    private const MAX_POINTS_MONTHLY = 36;

    public function index(Request $request)
    {
        $startDate = $request->filled('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : now()->subDays(29)->startOfDay();

        $endDate = $request->filled('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : now()->endOfDay();

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        $groupBy = $request->input('group_by', 'day');
        if (! in_array($groupBy, ['day', 'month'], true)) {
            $groupBy = 'day';
        }

        $groupByNotice = null;
        $daysInRange = $startDate->diffInDays($endDate) + 1;

        if ($groupBy === 'day' && $daysInRange > self::MAX_DAYS_FOR_DAILY_CHART) {
            $groupBy = 'month';
            $groupByNotice = 'Khoang thoi gian qua dai nen he thong tu dong chuyen bieu do sang che do theo thang de tranh qua tai giao dien.';
        }

        $ordersInRange = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $completedOrders = $ordersInRange->where('status', 'completed');

        $totalOrders = $ordersInRange->count();
        $completedOrdersCount = $completedOrders->count();
        $cancelledOrdersCount = $ordersInRange->where('status', 'cancelled')->count();
        $totalRevenue = (float) $completedOrders->sum('total_amount');
        $averageOrderValue = $completedOrdersCount > 0 ? $totalRevenue / $completedOrdersCount : 0;

        $chartData = $this->buildRevenueChartData($completedOrders, $startDate, $endDate, $groupBy);

        $statusChartLabels = ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'];
        $statusChartValues = collect($statusChartLabels)
            ->map(fn ($status) => $ordersInRange->where('status', $status)->count())
            ->all();

        $recentOrders = $ordersInRange->take(10);

        return view('admin.revenue.index', [
            'dateFrom' => $startDate->format('Y-m-d'),
            'dateTo' => $endDate->format('Y-m-d'),
            'groupBy' => $groupBy,
            'groupByNotice' => $groupByNotice,
            'totalOrders' => $totalOrders,
            'completedOrdersCount' => $completedOrdersCount,
            'cancelledOrdersCount' => $cancelledOrdersCount,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $averageOrderValue,
            'chartLabels' => $chartData['labels'],
            'chartValues' => $chartData['values'],
            'statusChartLabels' => $statusChartLabels,
            'statusChartValues' => $statusChartValues,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function export(Request $request)
    {
        $startDate = $request->filled('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : now()->subDays(29)->startOfDay();

        $endDate = $request->filled('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : now()->endOfDay();

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        $orders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'bao-cao-doanh-thu-'.$startDate->format('Ymd').'-'.$endDate->format('Ymd').'.xlsx';

        return Excel::download(new RevenueReportExport($orders), $fileName);
    }

    private function buildRevenueChartData($completedOrders, Carbon $startDate, Carbon $endDate, string $groupBy): array
    {
        $labels = [];
        $values = [];

        if ($groupBy === 'month') {
            $period = CarbonPeriod::create(
                $startDate->copy()->startOfMonth(),
                '1 month',
                $endDate->copy()->startOfMonth()
            );

            $mapped = $completedOrders
                ->groupBy(fn ($order) => Carbon::parse($order->created_at)->format('Y-m'))
                ->map(fn ($orders) => (float) $orders->sum('total_amount'));

            foreach ($period as $month) {
                $key = $month->format('Y-m');
                $labels[] = $month->format('m/Y');
                $values[] = round((float) ($mapped[$key] ?? 0), 2);
            }
        } else {
            $period = CarbonPeriod::create($startDate->copy()->startOfDay(), '1 day', $endDate->copy()->startOfDay());

            $mapped = $completedOrders
                ->groupBy(fn ($order) => Carbon::parse($order->created_at)->format('Y-m-d'))
                ->map(fn ($orders) => (float) $orders->sum('total_amount'));

            foreach ($period as $day) {
                $key = $day->format('Y-m-d');
                $labels[] = $day->format('d/m');
                $values[] = round((float) ($mapped[$key] ?? 0), 2);
            }
        }

        $maxPoints = $groupBy === 'day' ? self::MAX_POINTS_DAILY : self::MAX_POINTS_MONTHLY;

        if (count($labels) > $maxPoints) {
            [$labels, $values] = $this->compressSeries($labels, $values, $maxPoints);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function compressSeries(array $labels, array $values, int $maxPoints): array
    {
        $total = count($labels);

        if ($total <= $maxPoints || $maxPoints <= 0) {
            return [$labels, $values];
        }

        $step = (int) ceil($total / $maxPoints);
        $newLabels = [];
        $newValues = [];

        for ($i = 0; $i < $total; $i += $step) {
            $labelChunk = array_slice($labels, $i, $step);
            $valueChunk = array_slice($values, $i, $step);

            if (empty($valueChunk)) {
                continue;
            }

            $newLabels[] = reset($labelChunk).' - '.end($labelChunk);
            $newValues[] = round(array_sum($valueChunk), 2);
        }

        return [$newLabels, $newValues];
    }
}
