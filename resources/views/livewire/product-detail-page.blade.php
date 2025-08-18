<div class="w-full max-w-[85rem] py-0 lg:px-10 mx-auto">
    <section class="pt-0 pb-10 overflow-hidden bg-white md:pt-7 font-poppins dark:bg-gray-800">
        <div class="max-w-6xl px-0 py-0 mx-auto lg:py-0 md:px-6">
            <div class="flex flex-wrap">

              <div class="w-full mb-3 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{ ( $product->images != null || $product->images === "[]" ) ? Str::replace('%2F', '/',url('storage', $product->images[0])) : url('storage/food-packaging.png') }}' }">
                
                @auth
                    @if (auth()->user()->is_admin == 1)  
                    {{-- <div class="z-40 relative -mb-7"> --}}
                    <div class="absolute z-40  flex justify-between mt-4 mx-4">
                        <a class="cursor-pointer bg-white p-2 rounded-full"
                            {{-- href="/products" wire:navigate --}}
                            {{-- href="{{ url()->previous() }}" wire:navigate --}}
                            href="javascript:history.back()"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                </svg>
                        </a>   
                    </div>       
                    {{-- </div>        --}}
                    @else                    
                    <div class="absolute z-40 relative -mb-10">
                        <div class="flex justify-between mt-4 mx-4">
                            <a class="cursor-pointer"
                                {{-- href="{{ url()->previous() }}" wire:navigate --}}
                                href="javascript:history.back()" 
                                >
                                    <x-far-arrow-alt-circle-left class="w-5 h-5 text-gray-500 hover:text-blue-500"/>
                            </a>          
                        </div>       
                    </div>                 
                    @endif
                @endauth
                @guest
                <div class="absolute z-40 relative -mb-10">
                    <div class="flex justify-between mt-4 mx-4">
                        <a class="cursor-pointer"
                            {{-- href="{{ url()->previous() }}" wire:navigate --}}
                            href="javascript:history.back()"
                            >
                                <x-far-arrow-alt-circle-left class="w-5 h-5 text-gray-500 hover:text-blue-500"/>
                        </a>          
                    </div>       
                </div>   
                @endguest

                <div class="sticky top-0 z-10 overflow-hidden ">
                    <div class="relative mb-1 lg:h-2/4 ">
                        <img x-bind:src="mainImage" alt="" class="object-cover w-full lg:h-full ">
                    </div>
                    <div class="flex flex-wrap max-xl:flex">

                        @if ($product->images != null || $product->images === "[]")
                            @foreach ($product->images as $image)
                                <div class="w-1/5 p-2" 
                                    x-on:click="mainImage='{{ Str::replace('%2F', '/',url('storage', $image)) }}'">
                                    <img src="{{ Str::replace('%2F', '/',url('storage', $image)) }}" alt="{{ $product->name }}"
                                    onerror="this.src='{{ url('storage/food-packaging.png') }}';"
                                        class="object-cover w-full border-2 border-gray-300 cursor-pointer aspect-square hover:border hover:border-blue-500">
                                </div>
                            @endforeach
                        @else
                            <div class="w-1/5 p-2"
                                x-on:click="mainImage='{{ url('storage/food-packaging.png') }}'">
                                <img src="{{ url('storage/food-packaging.png') }}" alt="produk tanpa gambar"
                                    class="object-cover w-full border-2 border-gray-300 cursor-pointer aspect-square hover:border hover:border-blue-500">
                            </div>
                        @endif

                    </div>
                    {{-- <div class="px-6 pb-6 mt-2 border-t border-gray-300 dark:border-gray-400 ">
                        <div class="flex flex-wrap items-center mt-2">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="w-4 h-4 text-gray-700 dark:text-gray-400 bi bi-truck"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
                                    </path>
                                </svg>
                            </span>
                            <h2 class="text-lg font-bold text-gray-500 dark:text-gray-400">Free Delivery*</h2><span
                                class="text-xs dark:text-gray-400">&ensp; selama promo</span>
                        </div>
                    </div> --}}
                </div>
            </div>

            <div class="w-full px-4 md:px-4 md:w-1/2 ">
                <div class="lg:pl-20">
                    <div class="mb-2 space-y-4">
                        <h2 wire:model="name" class="max-w-xl text-2xl font-bold dark:text-white md:text-4xl">
                            @if (Str::contains($product->variant, $product->name))
                                {{ $product->variant }}
                            @else
                                {{ $product->name }} {{ $product->variant }}
                            @endif
                        </h2>
                            <h6 class="flex items-center flex-nowrap"><span><x-fas-store class="w-5 h-5 text-green-600"/></span>
                                <a wire:navigate href="{{ '/@'.$mitra }}"><span class="ml-2 line-clamp-2 dark:text-gray-400">{{ $branch }}</span></a>
                            </h6>
                        <p
                            class="inline-block w-full py-2 pl-3 text-2xl font-bold text-green-500 bg-gray-100 border-l-4 border-green-500 dark:bg-gray-900 dark:text-gray-200">
                            <span>@currency($product->price)</span>
                            @if ($product->strikethroughprice != null && $product->strikethroughprice > 0)
                                <span
                                    class="text-sm font-normal text-gray-500 line-through dark:text-gray-400">@currency($product->strikethroughprice)</span>
                            @endif
                        </p>

                        @if (count($variants) > 1)
                            <div>
                                <span class="px-2 py-2 text-xs font-bold text-black">Variant</span>
                                @foreach ($variants as $variant)
                                    @php
                                        if ($variant->id == $product->id) {
                                            $bgwarna = 'bg-green-400';
                                        } else {
                                            $bgwarna = 'bg-green-100';
                                        }
                                    @endphp
                                    <div class="inline-block mt-4 mr-1">
                                        <a wire:navigate wire:key="{{ $variant->id }}"
                                            class="text-xs {{ $bgwarna }} border border-green-400 rounded-md hover:bg-blue-500 hover:border-blue-500 hover:text-white px-3 py-2"
                                            href="/products/{{ $variant->slug }}">{{ $variant->variant }}</a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="dark:text-gray-400">Product has no variants</div>
                        @endif

                        <div class="grid grid-cols-3 divide-x-2 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 rounded-lg divide-gray-50 dark:divide-gray-800">
                            <span class="px-2 py-5 text-center"><x-fas-link class="w-5 h-5 mx-auto text-blue-500"/>
                                <div class="line-clamp-2">{{ $product->brand->name }}</div>
                            </span>
                            <span class="px-2 py-5 text-center "><x-fas-star class="w-5 h-5 mx-auto text-yellow-400"/>
                                <div class="line-clamp-2">Rating {{ $product->rating }}/5</div>
                            </span>
                            <span class="px-2 py-5 text-center"><x-fas-tag class="w-5 h-5 mx-auto text-red-400"/>
                                <div class="line-clamp-2">{{ $product->category->name }}</div>
                            </span>
                        </div>
                    </div>

                    <div class="justify-between hidden pt-4 mt-5 mb-5 border-t md:flex">
                        <div for=""
                            class="w-20 pb-1 text-xl font-semibold text-gray-700 border-b border-blue-300 text-nowrap dark:border-gray-600 dark:text-gray-400">
                            Quantity</div>
                        <div class="relative flex flex-row w-32 h-10 bg-transparent rounded-lg">

                            <button 
                            {{-- onclick="decrementValue()" --}} 
                            x-on:click="$wire.quantity--"
                            {{-- wire:click.prevent='decreaseQty' --}}
                                class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-100 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                                <span class="m-auto text-2xl font-thin">-</span>
                            </button>

                            <input wire:keyup.enter='addToCart({{ $product->id }}); soundBeep.play();' autofocus
                                required type="numeric" wire:model='quantity' value="" min=1 id="number"
                                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md">

                            <button 
                            {{-- onclick="incrementValue()" --}} 
                            x-on:click="$wire.quantity++"
                            {{-- wire:click.prevent='increaseQty' --}}
                                class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                                <span class="m-auto text-2xl font-thin">+</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex-wrap items-center hidden gap-4 md:flex">

                        {{-- @php
                  $boughtqty = $orderitem->where('product_id', $product->id)->sum('p_quantity');
                  $soldqty = $orderitem->where('product_id', $product->id)->sum('quantity');
                  $stock = $boughtqty - $soldqty;
                @endphp --}}

                        {{-- @if ($stock >= 1 && $product->in_stock == 1) --}}
                        @if ($product->in_stock == 1)
                            <button id='addToCartButton' wire:click.prevent='addToCart({{ $product->id }}); soundBeep.play();'
                                class="w-full p-4 rounded-md lg:full bg-gradient-to-r from-green-600 to-yellow-400 dark:text-gray-200 text-gray-50 hover:from-green-700 hover:to-green-700">
                                <span wire:loading.remove wire:target='addToCart({{ $product->id }})'>Add to cart <i
                                        class="fa fa-shopping-bag scale-110 relative left-[4px] bottom-[2px]"
                                        aria-hidden="true"></i></span><span wire:loading
                                    wire:target='addToCart({{ $product->id }})'>Adding...</span>
                            </button>
                        @else
                            <button
                                class="w-full p-4 bg-gray-500 rounded-md lg:full dark:text-gray-200 text-gray-50 hover:bg-green-400 dark:bg-blue-500 dark:hover:bg-blue-700">
                                <span>Habis</span>
                            </button>
                        @endif
                    </div>

                    <div class="mt-3">
                        @if ($product->contain != '')
                            @php
                                $contains = Str::of($product->contain)->explode(',');
                            @endphp
                            <p class="text-gray-700 dark:text-gray-400">
                                @foreach ($contains as $contain)
                                    <div class="inline-block px-2 m-1 bg-gray-200 rounded-md">
                                        {{ $contain }}
                                    </div>
                                @endforeach
                            </p>
                        @endif
                    </div>

                 
                </div>
            </div>

            <div class="w-full sm:px-10 px-5 ">
                {{-- <span>Stock {{ $stock }}</span> --}}
                @if (isset($product->description))    
                <div class="mt-3 [&>ul]:list-disc [&>ul]:ml-5 dark:text-gray-400">
                    <p class="max-w-md text-gray-700 dark:text-gray-400">
                        {!! Str::markdown(str($product->description)->sanitizeHtml()) !!}
                    </p>
                </div>
                @endif

                @if ($product->tags != '')
                    @php
                        $tags = Str::of($product->tags)->explode(',');
                    @endphp
                    <p class="mt-3 text-gray-700 dark:text-gray-400">
                        @foreach ($tags as $tag)
                            <div class="inline-block px-2 m-1 bg-green-300 rounded-md">
                                #{{ $tag }}
                            </div>
                        @endforeach
                    </p>
                @endif
            </div>

            @if ($product->embed_videos)
            @php
                $videos = Str::of($product->embed_videos)->explode(',');
            @endphp
            <div class="w-full sm:px-10 px-3 pt-5 text-center">
               <div id="embed-videos" class="owl-carousel owl-theme""> 
                   @foreach ($videos as $video)
                   <div class="item px-5">
                       <iframe class="w-full aspect-video" src="{{ $video }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
</div>


<div
    class="fixed bottom-0 left-0 z-50 w-full bg-white border-t border-white md:hidden h-14 dark:bg-gray-700 dark:border-gray-600">

    <div class="flex justify-between">
        <div class="relative flex flex-row w-32 h-10 mx-2 my-2 mr-1 bg-transparent rounded-lg">

            <button x-on:click="$wire.quantity--"
            {{-- wire:click.prevent='decreaseQty' --}}
                class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-100 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                <span class="m-auto text-2xl font-thin">-</span>
            </button>

            <input wire:keyup.enter='addToCart({{ $product->id }}); soundBeep.play();' required
                type="number" wire:model='quantity' value="" min=1
                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 border-none focus:ring-0 dark:text-white dark:placeholder-gray-400 dark:bg-gray-900 text-md">

            <button x-on:click="$wire.quantity++"
            {{-- wire:click.prevent='increaseQty' --}}
                class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                <span class="m-auto text-2xl font-thin">+</span>
            </button>
        </div>
        @if ($product->in_stock == 1)
            <button id='addToCartButton' wire:click.prevent='addToCart({{ $product->id }}); soundBeep.play();'
                class="w-full mx-2 my-2 ml-1 rounded-md lg:full bg-gradient-to-r from-green-600 to-yellow-400 dark:text-gray-200 text-gray-50 hover:from-green-700 hover:to-green-700">
                <span wire:loading.remove wire:target='addToCart({{ $product->id }})'>Add to cart <i
                        class="fa fa-shopping-bag scale-110 relative left-[4px] bottom-[2px]"
                        aria-hidden="true"></i></span><span wire:loading
                    wire:target='addToCart({{ $product->id }})'>Adding...</span>
            </button>
        @else
            <button
                class="w-full mx-2 my-2 ml-1 bg-gray-500 rounded-md lg:w-2/5 dark:text-gray-200 text-gray-50 hover:bg-green-400 dark:bg-blue-500 dark:hover:bg-blue-700">
                <span>Habis</span>
            </button>
        @endif
    </div>
</div>

</section>

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

    // auto scroll
    // function pageScroll() {
    //     window.scrollBy(0, 1);
    //     scrolldelay = setTimeout(pageScroll, 50);
    // }

    // pageScroll();

    $('#embed-videos').owlCarousel({
        stagePadding: 5,
        center:true,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        // margin:7,
        // nav:false,
        dots:true,
        loop:true,
        responsive:{
            0:{
                items:1
            },
            500:{
                items:1
            },
            768:{
                items:2
            },
            1000:{
                items:2
            }
        }
})
</script>
</div>
