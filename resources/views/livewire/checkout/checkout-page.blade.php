<div>
    @if($items->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500">Your cart is empty.</p>
            <a href="{{ route('products') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800" wire:navigate>
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                
                <ul class="divide-y divide-gray-200">
                    @foreach($items as $item)
                        <li class="py-4 flex justify-between" wire:key="checkout-item-{{ $item->id }}">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                            </div>
                            <p class="font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-indigo-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Place Order -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Complete Your Order</h3>
                
                <p class="text-sm text-gray-500 mb-6">
                    Click the button below to confirm your order. Your items will be reserved and your cart will be cleared.
                </p>

                <button
                    wire:click="placeOrder"
                    wire:loading.attr="disabled"
                    class="w-full py-3 px-4 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="placeOrder">
                        Place Order - ${{ number_format($total, 2) }}
                    </span>
                    <span wire:loading wire:target="placeOrder">
                        Processing...
                    </span>
                </button>

                <a href="{{ route('cart') }}" class="mt-4 block text-center text-sm text-gray-500 hover:text-gray-700" wire:navigate>
                    ← Back to Cart
                </a>
            </div>
        </div>
    @endif
</div>
