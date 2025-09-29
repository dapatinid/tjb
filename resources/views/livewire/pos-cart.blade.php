<div class="w-full flex">

    <div class="w-1/3 bg-white px-3 shadow-md">
        <div class="sticky top-1">
            <div class="space-y-4 h-[calc(100vh-7.5rem)] overflow-y-scroll overscroll-y-auto pe-3" style="scrollbar-width: thin; direction: rtl;" x-data="{ cartItems: @entangle('cartItems').live }">
                @if(count($cartItems) > 0)
                
                <ul class="divide-y divide-gray-200" style="direction: ltr;">
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
                                class="w-20 h-7 text-center border-0 outline-0 ring-0">
                                <input type="number" wire:change="updateCartItem({{ $item['id'] }}, 'price', $event.target.value)"
                                value="{{ $item['price'] }}" x-mask:dynamic="$money($input, ',', '.')"
                                class="w-30 h-7 text-center border-0 outline-0 ring-0 arrownyahidden">
                            </div>
                            <button wire:click="removeItem({{ $item['id'] }})"
                                    class="text-red-500 hover:text-red-700 hover:bg-red-300 hover:rounded-full p-1 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                            </button>
                        </div>
                    </li>
                    @endforeach

                    <button wire:dblclick="refreshCart" class="w-full mx-auto mt-5 mb-2 text-red-500 cursor-pointer"><span>Clear Cart</span></button>
                </ul>

                @else
                <p class="text-center text-gray-500 mt-5">~ Troli kosong ~</p>
                @endif
            </div>
            @if(count($cartItems) > 0)
            <div class="mt-4 pt-4 border-t-2 border-dashed border-gray-300">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total: {{ $this->weighttotal }}kg</span>
                    <span>Rp{{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>
            <button wire:click="saveCart" class="w-full mx-auto mb-3 text-white bg-blue-500 hover:bg-blue-700 rounded-lg py-1 mt-2 cursor-pointer">Save & Checkout</button>
            @endif
        </div>
    </div>

    
    <div class="w-2/3 rounded-lg py-4 px-2">
        <div class="flex gap-2">
            <div class="hs-dropdown flex-none">
                <button id="hs-dropdown-with-title" type="button" class="inline-flex items-center p-2 rounded-md text-white dark:text-gray-800 bg-amber-500 hover:bg-amber-300 cursor-pointer hs-dropdown-toggle focus:outline-none disabled:opacity-50 disabled " aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="z-50 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-zinc-50 shadow-md rounded-lg mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-title">
                    <div class="p-1 space-y-0.5">
                        <span class="block px-3 pt-2 pb-1 text-xs font-medium text-gray-400 uppercase dark:text-neutral-500">
                        Halaman
                        </span>
                        <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/">
                        <x-fas-home class="text-gray-500 size-4 dark:group-hover:text-white"/>
                        Beranda
                        </a>
                        <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/pos">
                        <x-fas-cash-register class="text-gray-500 size-4 dark:group-hover:text-white"/>
                        POS (Safety Mode)
                        </a>
                        <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/cart">
                        <x-fas-shopping-cart class="text-gray-500 size-4 dark:group-hover:text-white"/>
                        Troli
                        </a>
                    </div>
                    <div class="p-1 space-y-0.5">
                        <span class="block px-3 pt-2 pb-1 text-xs font-medium text-gray-400 uppercase dark:text-neutral-500">
                        Link
                        </span>
                        <a class="group flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/admin">
                        <x-fas-shield-halved class="text-gray-500 size-4 dark:group-hover:text-white"/>
                        Admin Panel
                        </a>
                        <a class="group flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="https://wa.me/6285950540055">
                        <x-fas-phone-volume class="text-gray-500 size-4 dark:group-hover:text-white"/>
                        Bantuan
                        </a>
                    </div>
                    <div class="p-1 py-3 pr-4 space-y-0.5 flex flex-nowrap items-center justify-between">
                        <span class="block px-3 pt-2 pb-1 text-xs font-medium text-gray-400 uppercase dark:text-neutral-500">
                        Tema
                        </span>
                        <div>
                        <button type="button" class="hs-dark-mode-active:hidden block hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-theme-click-value="dark">
                            <span class="group inline-flex shrink-0 justify-center items-center size-9">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                            </svg>
                            </span>
                        </button>
                        <button type="button" class="hs-dark-mode-active:block hidden hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-gray-500 dark:focus:bg-neutral-800" data-hs-theme-click-value="light">
                            <span class="group inline-flex shrink-0 justify-center items-center size-9">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="4"></circle>
                                <path d="M12 2v2"></path>
                                <path d="M12 20v2"></path>
                                <path d="m4.93 4.93 1.41 1.41"></path>
                                <path d="m17.66 17.66 1.41 1.41"></path>
                                <path d="M2 12h2"></path>
                                <path d="M20 12h2"></path>
                                <path d="m6.34 17.66-1.41 1.41"></path>
                                <path d="m19.07 4.93-1.41 1.41"></path>
                            </svg>
                            </span>
                        </button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="text" wire:model.live="search" placeholder="Cari produk..."
                class="grow w-full p-2 mb-4 border rounded-md">
            <button  type="button" class="flex-none p-2 mb-4 rounded-md text-white dark:text-gray-800 bg-amber-500 hover:bg-amber-300 cursor-pointer ">
                {{-- TOMBOL FULLSCREEN START --}}
                <svg onclick="toggle_full_screen()" id="layarpenuh" class="cursor-pointer hover:text-yellow-500 size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                </svg> 
                <svg onclick="toggle_full_screen()" id="layarpenuhtutup" class="hidden cursor-pointer hover:text-yellow-500 size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9 3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5 5.25 5.25" />
                </svg> 
                <script language="JavaScript">
                    function toggle_full_screen()
                    {
                        if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen))
                        {
                            if (document.documentElement.requestFullScreen){
                                document.documentElement.requestFullScreen();
                                document.getElementById("layarpenuh").classList.add("hidden");
                                document.getElementById("layarpenuhtutup").classList.remove("hidden");
                            }
                            else if (document.documentElement.mozRequestFullScreen){ /* Firefox */
                                document.documentElement.mozRequestFullScreen();
                                document.getElementById("layarpenuh").classList.add("hidden");
                                document.getElementById("layarpenuhtutup").classList.remove("hidden");
                            }
                            else if (document.documentElement.webkitRequestFullScreen){   /* Chrome, Safari & Opera */
                                document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                                document.getElementById("layarpenuh").classList.add("hidden");
                                document.getElementById("layarpenuhtutup").classList.remove("hidden");
                            }
                            else if (document.msRequestFullscreen){ /* IE/Edge */
                                document.documentElement.msRequestFullscreen();
                                document.getElementById("layarpenuh").classList.add("hidden");
                                document.getElementById("layarpenuhtutup").classList.remove("hidden");
                            }
                        }
                        else
                        {
                            if (document.cancelFullScreen){
                                document.cancelFullScreen();
                                document.getElementById("layarpenuh").classList.remove("hidden");
                                document.getElementById("layarpenuhtutup").classList.add("hidden");
                            }
                            else if (document.mozCancelFullScreen){ /* Firefox */
                                document.mozCancelFullScreen();
                                document.getElementById("layarpenuh").classList.remove("hidden");
                                document.getElementById("layarpenuhtutup").classList.add("hidden");
                            }
                            else if (document.webkitCancelFullScreen){   /* Chrome, Safari and Opera */
                                document.webkitCancelFullScreen();
                                document.getElementById("layarpenuh").classList.remove("hidden");
                                document.getElementById("layarpenuhtutup").classList.add("hidden");
                            }
                            else if (document.msExitFullscreen){ /* IE/Edge */
                                document.msExitFullscreen();
                                document.getElementById("layarpenuh").classList.remove("hidden");
                                document.getElementById("layarpenuhtutup").classList.add("hidden");
                            }
                        }
                    }
                </script>
                {{-- TOMBOL FULLSCREEN END --}}
            </button>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
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

            // Menyimpan data ke localStorage
            posComponent.on('resetCart', (event) => {
                localStorage.setItem('pos_cart', []);
            });
        });
    </script>

    <style>
        /* Chrome, Safari, Edge, Opera */
        .arrownyahidden::-webkit-outer-spin-button,
        .arrownyahidden::-webkit-inner-spin-button{
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        .arrownyahidden{
        -moz-appearance: textfield;
        }
    </style>
</div>