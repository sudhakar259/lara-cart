<?php

namespace App\Console\Commands;

use App\Jobs\LowStockNotificationJob;
use App\Models\Product;
use Illuminate\Console\Command;

class SendLowStockNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'low-stock:notify {threshold : The stock quantity threshold for notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send low stock notifications for products below the specified threshold';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = (int) $this->argument('threshold');

        if ($threshold <= 0) {
            $this->error('Threshold must be a positive integer.');
            return Command::FAILURE;
        }

        $lowStockProducts = Product::where('stock_quantity', '<=', $threshold)->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('No products are below the specified threshold.');
            return Command::SUCCESS;
        }

        foreach ($lowStockProducts as $product) {
            LowStockNotificationJob::dispatch($product);
            $this->info("Notification queued for product: {$product->name} (Stock: {$product->stock_quantity})");
        }

        $this->info("Total notifications queued: {$lowStockProducts->count()}");

        return Command::SUCCESS;
    }
}

