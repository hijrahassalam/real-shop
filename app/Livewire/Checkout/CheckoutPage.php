<?php

namespace App\Livewire\Checkout;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CheckoutPage extends Component
{
    public function placeOrder(): void
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            $this->redirect(route('cart'));
            return;
        }

        // Validate stock availability
        foreach ($cart->items as $item) {
            if (!$item->product->hasStock($item->quantity)) {
                session()->flash('error', "Not enough stock for {$item->product->name}. Only {$item->product->stock_quantity} available.");
                $this->redirect(route('cart'));
                return;
            }
        }

        try {
            DB::transaction(function () use ($user, $cart) {
                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => Order::generateOrderNumber(),
                    'status' => 'pending',
                    'total' => $cart->total,
                ]);

                // Create order items and decrement stock
                foreach ($cart->items as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);

                    // Decrement stock
                    $item->product->decrementStock($item->quantity);
                }

                // Clear cart
                $cart->items()->delete();
            });

            $this->dispatch('cart-updated');
            session()->flash('success', 'Order placed successfully!');
            $this->redirect(route('orders'));
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to place order. Please try again.');
        }
    }

    public function render()
    {
        $cart = Auth::user()->cart;
        $items = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $items->sum(fn($item) => $item->price * $item->quantity);

        return view('livewire.checkout.checkout-page', [
            'items' => $items,
            'total' => $total,
        ]);
    }
}
