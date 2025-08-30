<div class="w-full h-screen mx-auto bg-slate-200"
 {{-- onclick="full_screen_on()" --}}
>

    <section class="rounded-lg font-poppins">
            <div class="flex flex-wrap">

                {{-- Grid Start --}}
                <div class="absolute right-0 w-2/3">
                    <div class="top-0 z-10 px-3 bg-slate-200 dark:bg-slate-700">
                        <div class="items-center justify-between py-2 xs:flex xs:flex-row-reverse ">

                                <div class="flex justify-between mb-3 ml-3 sm:ml-0 xs:mb-0">


                                    <div class="xs:hidden flex mx-auto sm:ml-3 text-nowrap hs-dropdown">
                                    <button id="hs-dropdown-with-title-second" type="button" class="inline-flex items-center px-1 text-sm font-medium text-md text-gray-800 font-alkatra hover:text-lime-600 focus:text-lime-600 hs-dropdown-toggle focus:outline-none disabled:opacity-50 disabled dark:text-white" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        TegarJaya
                                    </button>

                                    <div class="z-50 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-title-second">
                                        <div class="p-1 space-y-0.5">
                                          <span class="block px-3 pt-2 pb-1 text-xs font-medium text-gray-400 uppercase dark:text-neutral-500">
                                            Halaman
                                          </span>
                                          <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/">
                                            <x-fas-home class="text-gray-500 size-4 dark:group-hover:text-white"/>
                                            Beranda
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

                                    <div class="flex mx-auto sm:ml-3 text-nowrap hs-dropdown">
                                        <button id="hs-dropdown-with-title" type="button" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md shadow-sm hover:bg-blue-500 hover:text-white focus:bg-blue-500 focus:text-white hs-dropdown-toggle gap-x-2 focus:outline-none disabled:opacity-50 disabled dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            <x-fas-envelope class="size-4"/> Orders
                                        </button>
    
                                        <div class="z-50 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-title">
                                              <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 rounded-t-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/my-orders">
                                                <x-fas-envelope class="text-gray-500 size-4 dark:group-hover:text-white"/>
                                                Pesanan
                                              </a>
                                              <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/payments">
                                                <x-fas-money-bill-wave class="text-gray-500 size-4 dark:group-hover:text-white"/>
                                                Pembayaran
                                              </a>
                                              <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 rounded-b-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/items">
                                                <x-fas-cube class="text-gray-500 size-4 dark:group-hover:text-white"/>
                                                Barang Terjual
                                              </a>
                                          </div>

                                          
                                    </div>

                                </div>
                            

                                <div class="flex flex-nowrap">

                                    <label class="relative block">
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
                                        {{-- autofocus="" --}}
                                        class="block xs:w-full w-[calc(100vw-33vw-4rem)] py-2 pr-20 text-sm bg-white dark:text-white dark:bg-neutral-900 border rounded-l-lg placeholder:italic placeholder:text-slate-400 border-slate-200 dark:border-slate-700 pl-9 focus:outline-none focus:border-green-400 focus:ring-green-400 focus:ring-1"
                                        placeholder="Cari..." type="text" name="search" />
                                    </label>
                    
                                    <button type="button" class="flex cursor-pointer items-center gap-2 py-[0.5rem] px-2 text-gray-900 bg-white border border-slate-200 dark:border-slate-700 dark:bg-neutral-900 transition hover:border-green-400 dark:hover:border-green-400 rounded-r-lg border dark:border border-gray-800 dark:border-gray-500 focus:outline-none focus:border-green-400 focus:ring-green-400 focus:ring-1 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-offcanvas-right" data-hs-overlay="#hs-offcanvas-right">
                                        <x-fas-sliders class="w-5 h-5 mx-auto text-blue-500"/>
                                    </button>
                    
                                        <div wire:ignore.self id="hs-offcanvas-right" class="hs-overlay [--body-scroll:true] [--overlay-backdrop:false] [--auto-close:false] hs-overlay-open:translate-x-0 hidden translate-x-full fixed top-0 end-0 transition-all duration-300 transform h-full max-w-xs w-full z-80 bg-white border-s border-gray-200 dark:bg-neutral-800 dark:border-neutral-700" 
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

                                <div class="hidden mr-3 md:flex hs-dropdown">
                                    <button id="hs-dropdown-with-title" type="button" class="inline-flex items-center px-1 text-sm font-medium text-md text-gray-800 font-alkatra hover:text-lime-600 focus:text-lime-600 hs-dropdown-toggle focus:outline-none disabled:opacity-50 disabled dark:text-white" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        TegarJaya
                                    </button>

                                    <div class="z-50 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-title">
                                        <div class="p-1 space-y-0.5">
                                          <span class="block px-3 pt-2 pb-1 text-xs font-medium text-gray-400 uppercase dark:text-neutral-500">
                                            Halaman
                                          </span>
                                          <a wire:navigate class="group flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white dark:focus:bg-neutral-700" href="/">
                                            <x-fas-home class="text-gray-500 size-4 dark:group-hover:text-white"/>
                                            Beranda
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

                        </div>
                    </div>

                    <button wire:click='resetProductsTile()' class="cursor-pointer w-full sticky top-0 z-10 {{ $url == 0 ? ' hidden' : 'flex' }} py-1 pb-2 mx-auto justify-center items-center bg-slate-200 dark:bg-slate-700 dark:text-gray-400">
                        reset
                    </button>

                    {{-- Product Card Start --}}

                    <div class="flex flex-wrap items-center justify-center mx-auto pb-2 px-0.5 bg-slate-200 dark:bg-slate-700">

                        @foreach ($products as $product)
                            <div wire:key="{{ $product->id }}"
                                class="w-1/2 px-0 mb-0 xs:w-1/3 sm:w-1/3 md:w-1/4 lg:w-1/5">
                                <div
                                    class="bg-white border-2 dark:bg-slate-900 group hover:bg-gray-800 dark:hover:bg-white focus:bg-gray-800 border-slate-200 dark:border-slate-700">
                                    <div aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal" data-hs-overlay="#hs-focus-management-modal"
                                        wire:click="cartEditModal({{ $product }})"
                                        onClick="showModalPro()" 
                                        class="relative {{ $product->is_active == 1 ? 'bg-gray-100 dark:bg-gray-800' : 'bg-gray-400' }} cursor-pointer scale-90">
                                            @if ($product->images != null || $product->images === "[]")
                                                <img src="{{ Str::replace('%2F', '/',url('storage', $product->images[0])) }}"
                                                onerror="this.src='{{ url('storage/food-packaging.png') }}';"    
                                                alt="{{ $product->name }}"
                                                    class="object-cover w-full mx-auto aspect-square">
                                            @else
                                                <img src="{{ url('storage/food-packaging.png') }}"
                                                    alt="{{ $product->name }}"
                                                    class="object-cover w-full mx-auto aspect-square">
                                            @endif
                                    </div>
                                    <div class="px-3 pb-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <h3 class="text-sm font-medium truncate dark:text-white group-hover:text-white dark:group-hover:text-black group-focus:text-white max-lg:text-sm">
                                                @if (Str::contains($product->variant, $product->name))
                                                    {{ $product->variant }}
                                                @else
                                                    {{ $product->name }} {{ $product->variant }}
                                                @endif
                                            </h3>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm max-lg:sm">
                                                <span
                                                    class="text-green-600 dark:text-lime-300 group-hover:text-lime-400 dark:group-hover:text-green-600 group-focus:text-lime-400 text-nowrap">
                                                    @if (Str::length($product->price) > 6)
                                                    Rp{{  Number::forHumans($product->price, precision: 2) }}
                                                    @else
                                                    @currency($product->price)
                                                    @endif
                                                    </span>
                                                {{-- @if ($product->strikethroughprice != null && $product->strikethroughprice >= 0)
                                                    <span
                                                        class="pr-2 text-xs font-normal text-gray-500 line-through dark:text-green-600">@currency($product->strikethroughprice)</span>
                                                @endif --}}
                                                </p>
                                                @php
                                                    $boughtqty = $orderitem->where('branch_id', auth()->user()->branch_id)->where('product_id', $product->id)->sum('p_quantity');
                                                    $soldqty = $orderitem->where('branch_id', auth()->user()->branch_id)->where('product_id', $product->id)->sum('quantity');
                                                    $stock = $boughtqty - $soldqty;
                                                @endphp
                                                <span class="ms-auto py-0.25 px-0.75 rounded-md text-xs font-normal text-gray-500 dark:text-gray-500 border border-gray-500">
                                                    {{ $stock }}
                                                </span>
                                                
                                            {{-- <span class="text-xs text-nowrap"><i class="text-yellow-400 fa fa-star" aria-hidden="true"></i> {{ $product->rating }}</span> --}}
                                        </div>
                                    </div>
                                    <div class="flex justify-center p-1 border-t border-gray-300 dark:border-gray-700">



                                        {{-- @if ($stock >= 1 && $product->in_stock == 1) --}}
                                        @if ($product->in_stock == 1)
                                            <span aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal" data-hs-overlay="#hs-focus-management-modal"
                                                wire:click="cartEditModal({{ $product }})"
                                                onClick="showModalPro()" 
                                                class="flex items-center text-gray-500 cursor-pointer dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-300">
                                                <span wire:loading wire:target='addToCart({{ $product->id }})'>...</span>
                                                @if ($cartcek->where('branch_id', auth()->user()->branch_id)->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                                                    <span class="w-full mr-2" wire:loading.remove wire:target='addToCart({{ $product->id }})'><x-fas-chevron-left class="w-5 h-5 mx-auto text-green-500 hover:text-red-400"/></span>
                                                    <span class="w-full px-5 bg-blue-200 rounded-md dark:text-blue-600" wire:loading.remove wire:target='addToCart({{ $product->id }})'>{{ $cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') }}</span>
                                                    <span class="w-full ml-2" wire:loading.remove wire:target='addToCart({{ $product->id }})'><x-fas-chevron-right class="w-5 h-5 mx-auto text-green-500 hover:text-blue-500"/></span>
                                                @else
                                                    <span class="flex items-center dark:group-hover:text-blue-500 flex-nowrap" wire:loading.remove wire:target='addToCart({{ $product->id }})'>
                                                        +Cart
                                                    </span>
                                                @endif 
                                                <span wire:loading wire:target='addToCart({{ $product->id }})'>...</span>
                                            </span>
                                        @else
                                            <a class="flex items-center space-x-2 text-gray-500 cursor-not-allowed dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-300"><span>Habis</span></a>
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
                    class="mx-6"
                    >
                        {{ $products->links() }}
                    </div>
                    <!-- pagination end -->

                    @include('livewire.pos-edit-page')


                </div>
                {{-- Grid End --}}

                {{-- LIST ITEM START --}}
                <div id="ListItem" class="fixed left-0 w-1/3 h-screen p-2 overflow-auto bg-white dark:bg-slate-800">
                      
                       <div class="pb-28">
                            <table class="w-full">
                                <div class="w-full"><input type="text"
                                    wire:model.live="kodeproduk"  id="BarcodeBar"
                                        onmouseenter="this.focus()"
                                        placeholder="Enter Barcode..."
                                        {{-- wire:keyup.enter='addToCartWithCode(); setTimeout(scrollBottom, 5000); soundBeep.play(); resetBarcodeBar();' --}}
                                        wire:keyup.enter='addToCartWithCode(); setTimeout(scrollBottom, 5000); resetBarcodeBar();'
                                        class="w-full pl-2 pr-2 mb-2 bg-slate-200 dark:bg-slate-700 border border-white dark:border-slate-800"
                                        ></div>
                                <body class="w-full" >
                                    @forelse ($cart_items as $item)
                                    <tr class="text-xs font-normal border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-500 bg-gray-50 lg:text-sm" wire:key='{{ $item['id'] }}' wire:loading.class="bg-red-300" wire:loading.class.remove="bg-gray-100" wire:target='removeItem({{ $item['product_id'] }})'>
                                        @php
                                            $panjangNama = $item['name'].$item['name'];
                                        @endphp
                                        <td>
                                            <div class="absolute w-[80%] sm:w-full pl-2 
                                            {{ Str::length($panjangNama) > 27 ? 'xs:-mt-6 -mt-8' : '-mt-5' }} 
                                             text-wrap sm:mt-0 sm:relative">
                                                <a wire:navigate href="/products/{{ $item['slug'] }}" class="hover:text-blue-500">{{ $loop->iteration }}.
                                                    @if (Str::contains($item['variant'], $item['name']))
                                                    {{ $item['variant'] }}
                                                    @else
                                                    {{ $item['name'] }} {{ $item['variant'] }}
                                                    @endif
                                                </a>
                                            </div>
                                        </td>
                                        <td aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal" data-hs-overlay="#hs-focus-management-modal"
                                            wire:click="cartListEditModal({{ $item }})"
                                            onClick="showModalPro()" 
                                        class="
                                        {{ Str::length($panjangNama) > 27 ? 'xs:pt-8 pt-12' : 'pt-5' }} 
                                            sm:pt-0 cursor-pointer hover:bg-gray-300" 
                                            >
                                            <div class="w-10 p-1 text-right">
                                                    <b>{{ $item['quantity'] }}</b>
                                                
                                            </div>
                                        </td>
                                        <td class="
                                        {{ Str::length($panjangNama) > 27 ? 'xs:pt-8 pt-12' : 'pt-5' }} 
                                            sm:pt-0"><div class="p-1 text-end">@formatNumber($item['total_amount'])</div></td>
                                        <td wire:click='removeItem({{ $item['product_id'] }})' class="text-center cursor-pointer hover:bg-red-400 group">
                                            <span class="p-1" wire:loading.remove wire:target='removeItem({{ $item['product_id'] }})'>X</span>
                                            <span class="py-1" wire:loading wire:target='removeItem({{ $item['product_id'] }})'>
                                                <div class="mt-1 animate-spin inline-block size-3 border-[2px] border-current border-t-transparent text-red-500 rounded-full dark:text-red-400 group-hover:text-white dark:group-hover:text-white" role="status" aria-label="loading">
                                                    <span class="sr-only">Loading...</span>
                                                  </div>
                                            </span>
                                        </td>
                                    </tr>

                                    @empty
                                    <div colspan="5" class="py-4 text-xl font-semibold text-center text-slate-500">Troli kosong</div>
                                    @endforelse
                                    
                                </body>
                            </table>
                            @if ($cartcek->where('branch_id', auth()->user()->branch_id)->where('created_by', auth()->user()->id)->count() > 0)
                            <div class="w-full pt-5 mx-auto text-center">
                                <button wire:dblclick='clearItemByBranch({{ Auth::user()->branch_id }})'  
                                    class="text-sm font-semibold text-red-500 underline cursor-pointer underline-offset-2">
                                    <span class="p-1" wire:loading.remove wire:target='clearItemByBranch({{ Auth::user()->branch_id }})'>Clear</span>
                                    <span class="p-1" wire:loading wire:target='clearItemByBranch({{ Auth::user()->branch_id }})'>
                                        <div class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-red-500 rounded-full dark:text-red-400" role="status" aria-label="loading">
                                            <span class="sr-only">Loading...</span>
                                          </div>
                                    </span>
                                </button>
                            </div>
                            @endif
                      </div>
                  
                      <div class="fixed left-0 w-1/3 px-2 py-2 mt-2 -mb-2 bg-white dark:bg-slate-800 bottom-2">
                        <div class="flex justify-between w-full dark:text-white">
                            <div>
                                W: {{ $cartcek->where('branch_id', auth()->user()->branch_id)->where('created_by', auth()->user()->id)->sum('total_weight') }} kg
                            </div>
                            <div class="font-bold text-end">TOTAL</div>
                        </div>
                        <div class="flex justify-between w-full mb-2 dark:text-white">
                            <div>
                                Qty: {{ $cartcek->where('branch_id', auth()->user()->branch_id)->where('created_by', auth()->user()->id)->sum('quantity') }}
                            </div>
                            <div class="font-bold text-end">@formatNumber($grand_total)</div>
                        </div>

                        <div class="flex justify-between w-full">
                            <a >
                                <button 
                                wire:click='refreshPage();' 
                                class="items-center px-2 py-2 text-sm text-center text-white bg-yellow-500 hover:bg-yellow-600">
                                    <x-fas-arrows-rotate wire:loading.remove wire:target='refreshPage();' wire:loading.class="hidden" wire:loading.class.remove="relative" class="relative w-5 h-5 mx-auto text-white"/>
                                    <div wire:loading wire:target='refreshPage();' wire:loading.class="relative" wire:loading.class.remove="hidden" class="mx-auto -mb-[0.3rem] hidden animate-spin w-5 h-5 text-white dark:text-orange-500">
                                        <x-fas-arrows-rotate class=""/>
                                      </div>
                                </button>
                            </a>
                            <a href="/checkout?branch_id={{ auth()->user()->branch_id }}&shipping_method=self_pickup&sales_type=self_pickup&payment_method=cash&rekening=KAS+KASIR&date_order={{ date('Y') }}-{{ date('m') }}-{{ date('d') }}T{{ date('H') }}%3A{{ date('i') }}"
                                class="items-center w-full pt-2 pb-0 text-sm text-center text-white bg-blue-500 hover:bg-blue-600">
                                <button class="hidden mx-auto sm:flex">
                                    CHECKOUT
                                </button>
                                <button class="text-center sm:hidden">
                                    CO
                                </button>
                            </a>
                            <a class="items-center px-2 pt-2 pb-0 text-sm text-center text-white bg-green-500 hover:bg-green-600">
                                <button onclick="toggle_full_screen();">
                                    <x-fas-expand-arrows-alt id="lyrpenuhpos" class="w-5 h-5 mx-auto text-white"/>
                                    <x-fas-compress-arrows-alt id="lyrpenuhpostutup" class="hidden w-5 h-5 mx-auto text-white"/>
                                </button>
                            </a>
                        </div>

                      </div>

                </div>
                {{-- LIST ITEM END --}}

            </div>
    </section>

    {{-- @auth
        @if (auth()->user()->is_admin == 1)
            <audio controls id="sound-beep" src="/storage/audio/beep-barcode-kasir.mp3" preload="auto"
                class="hidden"></audio>
        @endif
    @endauth --}}
    <script>
        const addToCartButton = document.getElementById('addToCartButton');
        // const soundBeep = document.getElementById('sound-beep');
        let elemListItem = document.getElementById('ListItem');
        
        addToCartButton.addEventListener('click', function() {
            setTimeout(scrollBottom, 5000);
        });
        
        function resetBarcodeBar() {
            document.getElementById('BarcodeBar').value = "";
        }; 
        
        function scrollBottom() {
            elemListItem.scrollTop = elemListItem.scrollHeight;
        }; 
        
        function modalClose() {
            document.getElementById("hs-focus-management-modal-close").click();
        }; 

        function showModalPro() {
            let IDnya = "modalProd";
            let ProdID = document.getElementById(IDnya);
            ProdID.click();
        };

    //  function full_screen_on()
    //     {
    //         if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen))
    //         {
    //             if (document.documentElement.requestFullScreen){
    //                 document.documentElement.requestFullScreen();
    //             }
    //             else if (document.documentElement.mozRequestFullScreen){ /* Firefox */
    //                 document.documentElement.mozRequestFullScreen();
    //             }
    //             else if (document.documentElement.webkitRequestFullScreen){   /* Chrome, Safari & Opera */
    //                 document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
    //             }
    //             else if (document.msRequestFullscreen){ /* IE/Edge */
    //                 document.documentElement.msRequestFullscreen();
    //             }
    //         }
    //     }


     function toggle_full_screen()
        {
            if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen))
            {
                if (document.documentElement.requestFullScreen){
                    document.documentElement.requestFullScreen();
                   document.getElementById("lyrpenuhpos").classList.add("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.remove("hidden");
                }
                else if (document.documentElement.mozRequestFullScreen){ /* Firefox */
                    document.documentElement.mozRequestFullScreen();
                   document.getElementById("lyrpenuhpos").classList.add("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.remove("hidden");
                }
                else if (document.documentElement.webkitRequestFullScreen){   /* Chrome, Safari & Opera */
                    document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                   document.getElementById("lyrpenuhpos").classList.add("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.remove("hidden");
                }
                else if (document.msRequestFullscreen){ /* IE/Edge */
                    document.documentElement.msRequestFullscreen();
                   document.getElementById("lyrpenuhpos").classList.add("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.remove("hidden");
                }
            }
            else
            {
                if (document.cancelFullScreen){
                    document.cancelFullScreen();
                   document.getElementById("lyrpenuhpos").classList.remove("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.add("hidden");
                }
                else if (document.mozCancelFullScreen){ /* Firefox */
                    document.mozCancelFullScreen();
                   document.getElementById("lyrpenuhpos").classList.remove("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.add("hidden");
                }
                else if (document.webkitCancelFullScreen){   /* Chrome, Safari and Opera */
                    document.webkitCancelFullScreen();
                   document.getElementById("lyrpenuhpos").classList.remove("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.add("hidden");
                }
                else if (document.msExitFullscreen){ /* IE/Edge */
                    document.msExitFullscreen();
                   document.getElementById("lyrpenuhpos").classList.remove("hidden");
                   document.getElementById("lyrpenuhpostutup").classList.add("hidden");
                }
            }
        };

        // window.addEventListener('pos-page', event => {
		//    window.location.reload(false); 
		// });

    </script>

</div>
