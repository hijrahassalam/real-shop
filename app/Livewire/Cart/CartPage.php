<?php

namespace App\Livewire\Cart;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartPage extends Component
{
    #[On('cart-updated')]
    public function refresh(): void
    {
        // This will trigger a re-render
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        // Verify ownership
        if ($cartItem->cart->user_id !== Auth::id()) {
            return;
        }

        if ($quantity <= 0) {
            $this->removeItem($cartItemId);
            return;
        }

        if (!$cartItem->product->hasStock($quantity)) {
            session()->flash('error', 'Not enough stock available. Only ' . $cartItem->product->stock_quantity . ' left.');
            return;
        }

        $cartItem->update(['quantity' => $quantity]);
        $this->dispatch('cart-updated');
    }

    public function incrementQuantity(int $cartItemId): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($cartItem->cart->user_id !== Auth::id()) {
            return;
        }

        $newQuantity = $cartItem->quantity + 1;

        if (!$cartItem->product->hasStock($newQuantity)) {
            session()->flash('error', 'Not enough stock available.');
            return;
        }

        $cartItem->increment('quantity');
        $this->dispatch('cart-updated');
    }

    public function decrementQuantity(int $cartItemId): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($cartItem->cart->user_id !== Auth::id()) {
            return;
        }

        if ($cartItem->quantity <= 1) {
            $this->removeItem($cartItemId);
            return;
        }

        $cartItem->decrement('quantity');
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $cartItemId): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($cartItem->cart->user_id !== Auth::id()) {
            return;
        }

        $cartItem->delete();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Item removed from cart.');
    }

    public function render()
    {
        $cart = Auth::user()->cart;
        $items = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $items->sum(fn($item) => $item->price * $item->quantity);

        return view('livewire.cart.cart-page', [
            'items' => $items,
            'total' => $total,
        ]);
    }
}
