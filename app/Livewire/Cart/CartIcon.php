<?php

namespace App\Livewire\Cart;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartIcon extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount(): void
    {
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            $this->count = $cart ? $cart->items->sum('quantity') : 0;
        } else {
            $this->count = 0;
        }
    }

    public function render()
    {
        return view('livewire.cart.cart-icon');
    }
}
