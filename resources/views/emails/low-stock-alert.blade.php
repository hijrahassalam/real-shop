<x-mail::message>
# Low Stock Alert

The following product is running low on stock and needs to be restocked:

**Product:** {{ $product->name }}

**Current Stock:** {{ $product->stock_quantity }} units

**Threshold:** {{ $product->low_stock_threshold }} units

**Price:** ${{ number_format($product->price, 2) }}

Please restock this product as soon as possible to avoid stockouts.

<x-mail::button :url="config('app.url') . '/products'">
View Products
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
