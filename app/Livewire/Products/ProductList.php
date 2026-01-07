<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function addToCart(int $productId): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $product = Product::findOrFail($productId);

        if (!$product->hasStock()) {
            session()->flash('error', 'Product is out of stock.');
            return;
        }

        $cart = Auth::user()->getOrCreateCart();
        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            if (!$product->hasStock($cartItem->quantity + 1)) {
                session()->flash('error', 'Not enough stock available.');
                return;
            }
            $cartItem->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('success', 'Product added to cart!');
    }

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.products.product-list', [
            'products' => $products,
        ]);
    }
}
