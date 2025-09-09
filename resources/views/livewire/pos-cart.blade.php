<div class="container mx-auto p-4 flex gap-4">

    <div class="w-1/3 bg-white p-6 rounded-lg shadow-md">
        <div class="space-y-4" x-data="{ cartItems: @entangle('cartItems').live }">
            @if(count($cartItems) > 0)
            <button wire:click="refreshPage" class="w-full mx-auto -mb-5"><span>Clear Cart</span></button>
            <ul class="divide-y divide-gray-200">
                @foreach ($cartItems as $item)
                <li class="py-4 block items-start sm:items-center">
                    <div class="flex flex-nowrap justify-between items-center">
                        <span class="font-medium text-gray-900">{{ $item['name'] }} {{ $item['variant'] }}</span>
                        <span class="text-sm text-gray-500">
                            {{ $item['weight']* $item['quantity'] }}kg Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            {{-- Rp{{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }} --}}
                        </span>
                    </div>
                    <div class="flex items-center mt-2 sm:mt-0 gap-2 justify-between">
                        <div>
                            <input type="number" wire:change="updateCartItem({{ $item['id'] }}, 'quantity', $event.target.value)"
                            value="{{ $item['quantity'] }}" min="1"
                            class="w-20 h-7 text-center border rounded-md">
                            <input type="number" wire:change="updateCartItem({{ $item['id'] }}, 'price', $event.target.value)"
                            value="{{ $item['price'] }}"
                            class="w-30 h-7 text-center border rounded-md">
                        </div>
                        <button wire:click="removeItem({{ $item['id'] }})"
                                class="text-red-500 hover:text-red-700 hover:bg-red-300 hover:rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                        </button>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="mt-4 pt-4 border-t-2 border-dashed border-gray-300">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total: {{ $this->Weighttotal }}kg</span>
                    <span>Rp{{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>
            @else
            <p class="text-center text-gray-500">Keranjang kosong.</p>
            @endif
        </div>
        @if(count($cartItems) > 0)<button wire:click="saveCart" class="w-full mx-auto -mb-5 text-white bg-blue-500 hover:bg-blue-700 rounded-lg py-1 mt-2">CO</button>@endif
    </div>

    
    <div class="w-2/3 rounded-lg">
        <input type="text" wire:model.live="search" placeholder="Cari produk..."
            class="w-full p-2 mb-4 border rounded-md">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($this->filteredProducts as $product)
            <div wire:click="addToCart({{ $product['id'] }})"
                class="bg-white p-4 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                @if ($product['images'] != null || $product['images'] === "[]")
                    <img src="{{ Str::replace('%2F', '/',url('storage', $product['images'][0])) }}"
                    onerror="this.src='{{ url('storage/food-packaging.png') }}';"    
                    alt="{{ $product['name'] }}"
                        class="object-cover w-full mx-auto aspect-square rounded-md">
                @else
                    <img src="{{ url('storage/food-packaging.png') }}"
                        alt="{{ $product['name'] }}"
                        class="object-cover w-full mx-auto aspect-square rounded-md">
                @endif
                <h3 class="font-semibold text-lg">{{ $product['name'] }} {{ $product['variant'] }}</h3>
                <p class="text-gray-600">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const posComponent = Livewire.find('{{ $this->getId() }}');

            // Memuat data dari localStorage saat halaman dimuat
            posComponent.on('loadCart', () => {
                const storedCart = localStorage.getItem('pos_cart');
                if (storedCart) {
                    const parsedCart = JSON.parse(storedCart);
                    posComponent.set('cartItems', parsedCart);
                }
            });

            // Menyimpan data ke localStorage
            posComponent.on('saveCart', (event) => {
                localStorage.setItem('pos_cart', JSON.stringify(event.items));
            });
        });
    </script>
</div>