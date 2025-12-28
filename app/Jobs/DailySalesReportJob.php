<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DailySalesReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = now()->toDateString();
        
        // Get today's orders
        $orders = Order::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->with('items.product')
            ->get();

        // Calculate metrics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');
        $itemsSold = $orders->flatMap->items->sum('quantity');

        // Get top products sold today
        $topProducts = Order::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->with('items.product')
            ->get()
            ->flatMap->items
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'product_name' => $product->name,
                    'quantity_sold' => $items->sum('quantity'),
                    'revenue' => $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values()
            ->toArray();

        $reportData = [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'items_sold' => $itemsSold,
            'top_products' => $topProducts,
        ];

        $adminEmail = config('mail.admin_email', 'admin@example.com');
        
        Mail::to($adminEmail)->send(new DailySalesReport($reportData));
    }
}
