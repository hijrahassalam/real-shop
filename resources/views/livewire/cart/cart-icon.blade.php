<div class="relative mr-2">
    <a href="{{ route('cart') }}" class="inline-flex items-center p-2 text-gray-500 hover:text-gray-700" wire:navigate>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
    </a>
    @if($count > 0)
        <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-indigo-600 rounded-full">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</div>
