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

    <!-- Search -->
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search products..."
            class="w-full md:w-1/3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" wire:key="product-{{ $product->id }}">
                <!-- Product Image Placeholder -->
                <div class="h-48 bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 truncate">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>

                        @if ($product->stock_quantity > 0)
                            <span class="text-sm {{ $product->isLowStock() ? 'text-orange-500' : 'text-green-500' }}">
                                {{ $product->stock_quantity }} left
                                @if($product->isLowStock())
                                    ⚠️
                                @endif
                            </span>
                        @else
                            <span class="text-sm text-red-500">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Add to Cart Button -->
                    @auth
                        <button
                            wire:click="addToCart({{ $product->id }})"
                            wire:loading.attr="disabled"
                            wire:target="addToCart({{ $product->id }})"
                            @disabled($product->stock_quantity <= 0)
                            class="mt-4 w-full py-2 px-4 rounded-lg font-medium transition-colors duration-200
                                {{ $product->stock_quantity > 0
                                    ? 'bg-indigo-600 text-white hover:bg-indigo-700'
                                    : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                        >
                            <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                {{ $product->stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}
                            </span>
                            <span wire:loading wire:target="addToCart({{ $product->id }})">
                                Adding...
                            </span>
                        </button>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="mt-4 block w-full py-2 px-4 rounded-lg font-medium text-center bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200"
                        >
                            Login to Buy
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
