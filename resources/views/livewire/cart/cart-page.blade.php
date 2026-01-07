<div>
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if($items->isEmpty())
        <!-- Empty Cart -->
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
            <p class="mt-2 text-sm text-gray-500">Start shopping to add items to your cart.</p>
            <a href="{{ route('products') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700" wire:navigate>
                Browse Products
            </a>
        </div>
    @else
        <!-- Cart Items -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach($items as $item)
                    <li class="p-6" wire:key="cart-item-{{ $item->id }}">
                        <div class="flex items-center">
                            <!-- Product Image Placeholder -->
                            <div class="flex-shrink-0 w-20 h-20 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>

                            <!-- Product Details -->
                            <div class="ml-6 flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>

                                @if($item->product->stock_quantity < 10)
                                    <p class="mt-1 text-xs text-orange-500">Only {{ $item->product->stock_quantity }} left in stock</p>
                                @endif
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-3">
                                <button
                                    wire:click="decrementQuantity({{ $item->id }})"
                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>

                                <span class="w-12 text-center font-medium text-gray-900">{{ $item->quantity }}</span>

                                <button
                                    wire:click="incrementQuantity({{ $item->id }})"
                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600"
                                    @disabled($item->quantity >= $item->product->stock_quantity)
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Subtotal -->
                            <div class="ml-6 text-right">
                                <p class="text-lg font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>

                            <!-- Remove Button -->
                            <button
                                wire:click="removeItem({{ $item->id }})"
                                wire:confirm="Are you sure you want to remove this item?"
                                class="ml-6 text-red-500 hover:text-red-700"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Cart Summary -->
            <div class="bg-gray-50 p-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">{{ $items->sum('quantity') }} items</p>
                        <p class="text-2xl font-bold text-gray-900">Total: ${{ number_format($total, 2) }}</p>
                    </div>

                    <a
                        href="{{ route('checkout') }}"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                        wire:navigate
                    >
                        Proceed to Checkout
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
