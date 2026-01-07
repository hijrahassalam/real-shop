<?php

namespace App\Observers;

use App\Jobs\SendLowStockNotification;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Check if stock_quantity was changed
        if ($product->wasChanged('stock_quantity')) {
            $oldStock = $product->getOriginal('stock_quantity');
            $newStock = $product->stock_quantity;
            $threshold = $product->low_stock_threshold;

            // Only trigger if stock dropped from above threshold to at/below threshold
            if ($oldStock > $threshold && $newStock <= $threshold) {
                SendLowStockNotification::dispatch($product);
            }
        }
    }
}
