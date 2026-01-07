<x-mail::message>
# Daily Sales Report

**Date:** {{ $date->format('F d, Y') }}

## Summary

| Metric | Value |
|:-------|------:|
| Total Orders | {{ $totalOrders }} |
| Total Revenue | ${{ number_format($totalRevenue, 2) }} |

@if(count($topProducts) > 0)
## Top Selling Products

<x-mail::table>
| Product | Qty Sold | Revenue |
|:--------|:--------:|--------:|
@foreach($topProducts as $product)
| {{ $product['name'] }} | {{ $product['quantity'] }} | ${{ number_format($product['revenue'], 2) }} |
@endforeach
</x-mail::table>
@endif

@if($totalOrders > 0)
## Order Details

@foreach($orders as $order)
**Order #{{ $order->order_number }}** - ${{ number_format($order->total, 2) }} ({{ $order->status }})
@endforeach
@else
_No orders were placed on this date._
@endif

<x-mail::button :url="config('app.url') . '/orders'">
View All Orders
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
