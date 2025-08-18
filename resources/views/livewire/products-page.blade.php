<div class="w-full max-w-[85rem] sm:py-6 pt-3 px-2 md:px-6 lg:px-8 mx-auto">

    {{-- <p>The current URL is: {{url()->full()}}</p> --}}
    {{-- @if (url()->full() === 'http://ecommerce.test/products?selected_categories%5B0%5D=2') bg-green-300 @else --}}
    
    <div class="px-0 mb-3">
        <div class="items-center justify-between my-2 bg-transparent sm:flex  ">

            <div class="justify-start w-full">
                <h3 class="text-xl font-bold sm:text-left sm:mb-0 sm:ml-2 mb-4 text-center dark:text-white">PRODUCTS</h3>
            </div>

            <div class="flex justify-end w-full flex-nowrap">

                <div class="relative block">
                    <span class="sr-only">Search</span>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2 dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" fill="currentColor"
                                height="20" viewBox="0 0 30 30">
                                <path
                                d="M 13 3 C 7.4889971 3 3 7.4889971 3 13 C 3 18.511003 7.4889971 23 13 23 C 15.396508 23 17.597385 22.148986 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148986 17.597385 23 15.396508 23 13 C 23 7.4889971 18.511003 3 13 3 z M 13 5 C 17.430123 5 21 8.5698774 21 13 C 21 17.430123 17.430123 21 13 21 C 8.5698774 21 5 17.430123 5 13 C 5 8.5698774 8.5698774 5 13 5 z">
                            </path>
                        </svg>
                    </span>
                    <input wire:model.live="cari"
                    class="block sm:w-full w-[calc(100vw-54px)] py-2 pr-20 text-sm dark:text-white bg-white border border-slate-200 dark:border-slate-700 dark:bg-neutral-900 rounded-l-lg placeholder:italic placeholder:text-slate-400 pl-9 focus:outline-none focus:border-green-400 focus:ring-green-400 focus:ring-1"
                    placeholder="Cari..." type="text" name="search" />
                </div>

                <button type="button" class="flex cursor-pointer items-center gap-2 py-[0.5rem] px-2 text-gray-900 bg-white dark:bg-neutral-900 transition hover:border-green-400 dark:hover:border-green-400 rounded-r-lg border dark:border border-slate-200 dark:border-slate-700 focus:outline-none focus:border-green-400 focus:ring-green-400 focus:ring-1 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-offcanvas-right" data-hs-overlay="#hs-offcanvas-right">
                    <x-fas-sliders class="w-5 h-5 mx-auto text-blue-500"/>
                </button>

                <div wire:ignore.self id="hs-offcanvas-right" class="hs-overlay [--body-scroll:true] [--overlay-backdrop:false] hs-overlay-open:translate-x-0 hidden translate-x-full fixed top-0 end-0 transition-all duration-300 transform h-full max-w-xs w-full z-80 bg-white border-s border-gray-200 dark:bg-neutral-800 dark:border-neutral-700" 
                role="dialog" tabindex="-1" aria-labelledby="hs-offcanvas-right-label">
                    <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
                      <h3 id="hs-offcanvas-right-label" class="font-bold text-gray-800 dark:text-white">
                        Filter Products
                      </h3>
                      <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-offcanvas-right">
                        <span class="sr-only">Close</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M18 6 6 18"></path>
                          <path d="m6 6 12 12"></path>
                        </svg>
                      </button>
                    </div>
                    <nav class="h-full p-4 pb-16 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                        <div>
                            <div class="w-full my-2 bg-white border border-gray-200 rounded">
                                <header class="flex items-center justify-between p-4 border-b border-gray-200">
                                    <span class="text-sm font-bold text-gray-700"> Categories </span>
                                </header>
                                <ul
                                    class="inline-flex flex-wrap p-4 py-1">
                                    @foreach ($categories as $category)
                                    @php
                                    if (Auth::check()) { 
                                        $productcount = $productcek->where('branch_id', Auth::user()->branch_id)->where('category_id', $category->id)->count();
                                        if  ( $productcount > 0 ) {
                                        $showCat = '';
                                        } else {
                                        $showCat = 'hidden';
                                        }                    
                                    } else {
                                        $showCat = '';
                                    }
                                    @endphp                            
                                        <li wire:key="{{ $category->id }}" class="{{ $showCat }} mt-1 mb-1 mr-1">
                                            <input type="checkbox" wire:model.live="selected_categories"
                                                id="{{ $category->slug }}" value="{{ $category->id }}"
                                                class="hidden peer">
                                            <label for="{{ $category->slug }}"
                                                class=" items-center pb-1 px-1.5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                                                <span class="text-sm">{{ $category->name }}</span>
                                            </label>
                                            </input>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
        
                            <div class="w-full my-2 bg-white border border-gray-200 rounded">
                                <header class="flex items-center justify-between p-4 border-b border-gray-200">
                                    <span class="text-sm font-bold text-gray-700"> Brand </span>
                                </header>
                                <ul
                                    class="inline-flex flex-wrap p-4 py-1">
                                    @foreach ($brands as $brand)
                                    @php
                                    if (Auth::check()) { 
                                        $productcount = $productcek->where('branch_id', Auth::user()->branch_id)->where('brand_id', $brand->id)->count();
                                        if  ( $productcount > 0 ) {
                                        $showBrn = '';
                                        } else {
                                        $showBrn = 'hidden';
                                        }
                                    } else {
                                        $showBrn = '';
                                    }
                                    @endphp                            
                                        <li wire:key="{{ $brand->id }}" class="{{ $showBrn }} mt-1 mb-1 mr-1">
                                            <input type="checkbox" wire:model.live="selected_brands"
                                                id="{{ $brand->slug }}" value="{{ $brand->id }}"
                                                class="hidden peer">
                                            <label for="{{ $brand->slug }}"
                                                class=" items-center pb-1 px-1.5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                                                <span class="text-sm">{{ $brand->name }}</span>
                                            </label>
                                            </input>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
        
                            <div class="w-full my-2 bg-white border border-gray-200 rounded">
                                <header class="flex items-center justify-between p-4 border-b border-gray-200">
                                    <span class="text-sm font-bold text-gray-700"> Status </span>
                                </header>
                                <ul class="p-4 space-y-1">
                                    <li>
                                        <label for="featured"
                                            class="flex items-center text-blue-500 dark:text-gray-300">
                                            <input type="checkbox" id="featured"
                                                wire:model.live="featured" value="1"
                                                class="w-4 h-4 mr-2">
                                            <span class="text-sm text-blue-500 dark:text-gray-400">Featured</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label for="promo"
                                            class="flex items-center text-blue-500 dark:text-gray-300">
                                            <input type="checkbox" wire:model.live="promo"
                                                value="1" class="w-4 h-4 mr-2">
                                            <span class="text-sm text-blue-500 dark:text-gray-400">Promo</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
        
                            <div class="w-full my-2 bg-white border border-gray-200 rounded">
                                <header class="flex items-center justify-between p-4 border-b border-gray-200">
                                    <span class="text-sm font-bold text-gray-700"> Order by </span>
                                </header>
                                <ul class="p-4 space-y-1">
                                    <li>
                                        <label for="featured"
                                            class="flex items-center text-blue-500 dark:text-gray-300">
                                            <input type="radio" id="featured"
                                                wire:model.live="sort" 
                                                value="latest"
                                                class="w-4 h-4 mr-2">
                                            <span class="text-sm text-blue-500 dark:text-gray-400">Latest</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label for="promo"
                                            class="flex items-center text-blue-500 dark:text-gray-300">
                                            <input type="radio" 
                                                wire:model.live="sort"
                                                value="price" 
                                                class="w-4 h-4 mr-2">
                                            <span class="text-sm text-blue-500 dark:text-gray-400">Price</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
        
                            <div class="w-full my-2 bg-white border border-gray-200 rounded">
                                <header class="flex items-center justify-between p-4 border-b border-gray-200">
                                    <span class="text-sm font-bold text-gray-700"> Price </span>
                                </header>
                                <ul class="p-4 space-y-1">
        
                                    <div>
                                        <div class="font-semibold text-blue-500">@currency($price_range)</div>
                                        <input type="range" wire:model.live="price_range"
                                            class="w-full h-1 mb-4 bg-blue-100 rounded appearance-none cursor-pointer"
                                            max="10000000" value="10000000" step="1000000">
                                        <div class="flex justify-between ">
                                            <span
                                                class="inline-block text-sm font-bold text-blue-500 ">@currency(1000000)</span>
                                            <span
                                                class="inline-block text-sm font-bold text-blue-500 ">@currency(10000000)</span>
                                        </div>
                                    </div>
        
                                </ul>
                            </div>
                        </div>
                    </nav>
                  </div>
                
                
            </div>
        </div>
    </div>
    

    <section class="mx-auto bg-transparent font-poppins">
            <div class="mt-3 mb-10">

                <div class="w-full">

                    <a wire:navigate href="/products"
                        class="{{ $url == 0 ? ' hidden' : 'flex' }} sticky top-3 text-blue-400 bg-transparent z-10 mb-2 mx-auto justify-center items-center"><span class="bg-white dark:bg-slate-900 px-2 rounded-full">reset</span></a>

                    {{-- Product Card Start --}}

                    <div class="flex flex-wrap items-center justify-center mx-auto">

                        @foreach ($products as $product)
                            <div wire:key="{{ $product->id }}"
                                class="w-1/2 px-1 mb-2 xs:w-1/3 sm:w-1/3 md:w-1/4 lg:w-1/5 md:mb-3 ">
                                <div
                                    class="bg-white border border-gray-300 dark:bg-neutral-900 rounded-xl hover:shadow-md hover:border-gray-400 dark:border-gray-700 dark:hover:border-white">
                                    <div
                                        class="relative {{ $product->is_active == 1 ? 'bg-gray-100 dark:bg-gray-800' : 'bg-gray-400 dark:bg-black' }} rounded-lg scale-90">
                                        <a wire:navigate href="/products/{{ $product->slug }}" class="">
                                            @if ($product->images != null || $product->images === "[]")
                                                <img src="{{ Str::replace('%2F', '/',url('storage', $product->images[0])) }}"
                                                onerror="this.src='{{ url('storage/food-packaging.png') }}';"    
                                                alt="{{ $product->name }}"
                                                    class="object-cover w-full mx-auto rounded-lg aspect-square">
                                            @else
                                                <img src="{{ url('storage/food-packaging.png') }}"
                                                    alt="{{ $product->name }}"
                                                    class="object-cover w-full mx-auto rounded-lg aspect-square">
                                            @endif

                                        </a>
                                    </div>
                                    <div class="px-3 pb-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <h3 class="text-lg font-medium truncate max-lg:text-base dark:text-white">
                                                @if (Str::contains($product->variant, $product->name))
                                                    {{ $product->variant }}
                                                @else
                                                    {{ $product->name }} {{ $product->variant }}
                                                @endif
                                            </h3>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-base max-lg:text-sm"> 
                                                {{-- bisa tambahkan class truncate --}}
                                                <span class="text-green-600 dark:text-lime-400 text-nowrap">
                                                    @if (Str::length($product->price) > 6)
                                                    Rp{{  Number::forHumans($product->price, precision: 2) }}
                                                    @else
                                                    @currency($product->price)
                                                    @endif
                                                </span>
                                                @if ($product->strikethroughprice != null && $product->strikethroughprice > 0)
                                                    <span class="pr-2 text-xs font-normal text-gray-500 line-through text-nowrap dark:text-green-600">
                                                        @if (Str::length($product->strikethroughprice) > 6)
                                                        Rp{{  Number::forHumans($product->strikethroughprice, precision: 2) }}
                                                        @else
                                                        @currency($product->strikethroughprice)
                                                        @endif
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="flex items-center text-nowrap dark:text-white">
                                                <x-fas-star class="size-4 text-yellow-400 mb-0.5 mr-1"/> {{ $product->rating }}</p>
                                        </div>
                                    </div>
                                    <div 
                                        id='addToCartButton'
                                        wire:click.prevent='addToCart({{ $product->id }}); soundBeep.play();'
                                        class="cursor-pointer flex justify-center p-2 border-t border-gray-300 dark:border-gray-700">

                                        {{-- @php
                      $boughtqty = $orderitem->where('product_id', $product->id)->sum('p_quantity');
                      $soldqty = $orderitem->where('product_id', $product->id)->sum('quantity');
                      $stock = $boughtqty - $soldqty;
                  @endphp --}}

                                        {{-- @if ($stock >= 1 && $product->in_stock == 1) --}}
                                        @if ($product->in_stock == 1)
                                            <span 
                                                class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="w-4 h-4 bi bi-cart3 "
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                                    </path>
                                                </svg><span wire:loading.remove
                                                    wire:target='addToCart({{ $product->id }})'>Add to Cart</span><span wire:loading
                                                    wire:target='addToCart({{ $product->id }})'>Adding...</span>
                                            </span>
                                        @else
                                            <a
                                                class="flex items-center space-x-2 text-gray-500 cursor-not-allowed dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-300"><span>Habis</span></a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    {{-- Product Card End --}}

                    <!-- pagination start -->
                    {{-- <style>
                        nav div div p {
                            margin-left: 20px;
                            margin-right: 20px;
                        }
                    </style> --}}
                    <div 
                    {{-- class="flex justify-center mt-6" --}}
                    >
                        {{ $products->links() }}
                    </div>
                    <!-- pagination end -->

                </div>

                {{-- Grid End --}}

            </div>
    </section>

    {{-- <script>
		window.addEventListener('products-page', event => {
		   window.location.reload(false); 
		})
  </script> --}}

    @auth
        @if (auth()->user()->is_admin == 1)
            <audio controls id="sound-beep" src="/storage/audio/beep-barcode-kasir.mp3" preload="auto"
                class="hidden"></audio>
        @endif
    @endauth
    <script>
        const addToCartButton = document.getElementById('addToCartButton');
        const soundBeep = document.getElementById('sound-beep');

        addToCartButton.addEventListener('click', function() {
            // Kode untuk menambahkan item ke keranjang belanja
            // ...

            soundBeep.play();
        });

    </script>

</div>
