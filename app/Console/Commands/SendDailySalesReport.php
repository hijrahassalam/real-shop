<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-sales {--date= : The date to generate report for (Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report to admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday();

        $this->info("Generating sales report for {$date->format('Y-m-d')}...");

        // Get orders for the specified date
        $orders = Order::with('items.product')
            ->whereDate('created_at', $date)
            ->get();

        $reportData = [
            'date' => $date,
            'totalOrders' => $orders->count(),
            'totalRevenue' => $orders->sum('total'),
            'orders' => $orders,
            'topProducts' => $this->getTopProducts($orders),
        ];

        if ($orders->isEmpty()) {
            $this->warn("No orders found for {$date->format('Y-m-d')}");
        }

        // Send email to admin
        $adminEmail = config('mail.admin_email', 'admin@example.com');
        Mail::to($adminEmail)->send(new DailySalesReport($reportData));

        $this->info("Daily sales report sent to {$adminEmail}");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Date', $date->format('Y-m-d')],
                ['Total Orders', $reportData['totalOrders']],
                ['Total Revenue', '$' . number_format($reportData['totalRevenue'], 2)],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Get top selling products from orders.
     */
    private function getTopProducts($orders): array
    {
        $products = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $productId = $item->product_id;
                if (!isset($products[$productId])) {
                    $products[$productId] = [
                        'name' => $item->product->name ?? 'Unknown Product',
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                $products[$productId]['quantity'] += $item->quantity;
                $products[$productId]['revenue'] += $item->quantity * $item->price;
            }
        }

        // Sort by quantity sold
        usort($products, fn($a, $b) => $b['quantity'] - $a['quantity']);

        return array_slice($products, 0, 5);
    }
}
