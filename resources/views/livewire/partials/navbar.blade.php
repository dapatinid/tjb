<div class="flex justify-center">

  <header class="flex-wrap sm:justify-start sm:flex-nowrap w-full bg-amber-600 text-sm py-3 dark:bg-neutral-800
    {{ request()->is('cart') ? ' hidden' : 'flex' }} 
    {{ request()->is('checkout') ? ' hidden' : 'flex' }}
    {{ request()->is('my-account') ? ' hidden' : 'flex' }}
    {{ request()->is('pos') ? ' hidden' : 'flex' }}
    {{ request()->is('laba-rugi') ? ' hidden' : 'flex' }}
    {{ request()->is('laba-rugi-all') ? ' hidden' : 'flex' }}
    {{ request()->is('neraca') ? ' hidden' : 'flex' }}
    {{ request()->is('neraca-all') ? ' hidden' : 'flex' }}
     {{-- {{ dd(request()->fullUrl()) }} --}}
    {{ Str::of(request()->fullUrl())->contains('products/') ? ' hidden' : 'flex' }}
      ">
    <nav class="max-w-[85rem] w-full mx-auto px-4 flex flex-wrap basis-full items-center justify-between">
      <span class="cursor-pointer sm:order-1 flex-none text-xl font-marko text-green-400 dark:text-text-green-400 focus:outline-hidden focus:opacity-80" 
      aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-offcanvas-body-scrolling-with-backdrop" data-hs-overlay="#hs-offcanvas-body-scrolling-with-backdrop"
      >TegarJaya</span>
      <div class="sm:order-3 flex items-center gap-x-2">
        {{-- tombol collapse sm:hidden --}}
        <button type="button" class="hidden hs-collapse-toggle relative size-9 flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-transparent dark:border-neutral-700 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" id="hs-navbar-alignment-collapse" aria-expanded="false" aria-controls="hs-navbar-alignment" aria-label="Toggle navigation" data-hs-collapse="#hs-navbar-alignment">
          <svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
          <svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          <span class="sr-only">Toggle</span>
        </button>
        {{-- tombol collapse sm:hidden --}}

        @auth
          <button type="button" class="relative z-50 cursor-pointer inline-flex items-center gap-x-2 text-sm font-bold text-white dark:text-white"
            aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-offcanvas-body-scrolling-with-backdrop" data-hs-overlay="#hs-offcanvas-body-scrolling-with-backdrop"
            >{{ auth()->user()->name }}
          </button>
        @endauth
        @guest
          <a wire:navigate href='/login' type="button" class="relative z-50 py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
              Masuk
          </a>
          <a wire:navigate href='/register' type="button" class="relative z-50 py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            Daftar
          </a>
        @endguest

      </div>
      <div id="hs-navbar-alignment" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2" aria-labelledby="hs-navbar-alignment-collapse">
        <div class="flex flex-col gap-5 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5">
          <a wire:navigate class="relative z-50 font-medium text-gray-300 hover:text-white focus:outline-hidden focus:text-gray-400 dark:text-neutral-400 dark:hover:text-white dark:focus:text-neutral-500" href="/">Beranda</a>
          <a wire:navigate class="relative z-50 font-medium text-gray-300 hover:text-white focus:outline-hidden focus:text-gray-400 dark:text-neutral-400 dark:hover:text-white dark:focus:text-neutral-500" href="/branches">Store</a>
          
          <div class="hs-dropdown relative z-50 inline-flex cursor-pointer">
            <span aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown" id="hs-dropdown-with-icons" class="font-medium text-gray-300 hover:text-white focus:outline-hidden focus:text-gray-400 dark:text-neutral-400 dark:hover:text-white dark:focus:text-neutral-500">Produk</span>
            <div class="z-80 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-icons">
              <div class="p-1 space-y-0.5">
                <a wire:navigate class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                  @auth
                    href="/{{ '@'.$thisPartner->where('id', auth()->user()->partner_id)->value('slug') }}"
                  @endauth
                  @guest
                    href="/{{ '@'.$thisPartner }}"
                  @endguest
                 >
                  <x-fas-home class="w-4 h-4 text-gray-800 dark:text-neutral-200"/>
                  Profil Toko
                </a>
                <a wire:navigate class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                 href="/categories">
                  <x-fas-tag class="w-4 h-4 text-gray-800 dark:text-neutral-200"/>
                  Ketegori
                </a>
                <a wire:navigate class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                 href="/products">
                  <x-fas-cubes class="w-4 h-4 text-gray-800 dark:text-neutral-200"/>
                  Produk (All)
                </a>
              </div>
            </div>
          </div>

          <a wire:navigate class="relative z-50 font-medium text-gray-300 hover:text-white focus:outline-hidden focus:text-gray-400 dark:text-neutral-400 dark:hover:text-white dark:focus:text-neutral-500" href="/cart">Troli 
            @if ($total_count > 0)
            <span class="inline-flex items-center gap-x-1.5 py-0.5 px-1 rounded-full text-xs font-medium bg-green-100/30 text-white dark:bg-green-800/30 dark:text-green-500"">{{ $total_count }}</span>
            @endif
          </a>
          <a wire:navigate class="relative z-50 font-medium text-gray-300 hover:text-white focus:outline-hidden focus:text-gray-400 dark:text-neutral-400 dark:hover:text-white dark:focus:text-neutral-500" href="/my-orders">Pesanan</a>

        </div>
      </div>
    </nav>
  </header>
      
  <!-- Start Sidebar -->
  <div id="hs-offcanvas-body-scrolling-with-backdrop" class="hs-overlay [--body-scroll:true] hs-overlay-open:translate-x-0 hidden -translate-x-full fixed top-0 start-0 transition-all duration-300 transform h-full max-w-xs w-full z-80 bg-white border-e border-gray-200 dark:bg-neutral-800 dark:border-neutral-700" role="dialog" tabindex="-1" aria-labelledby="hs-offcanvas-body-scrolling-with-backdrop-label">
    <div class="flex justify-between items-center py-3 px-4 mb-3 border-b border-gray-200 dark:border-neutral-700">
      <h3 id="hs-offcanvas-body-scrolling-with-backdrop-label" class="font-bold text-xl text-gray-800 dark:text-white">
        Menu Utama
      </h3>
      <div class="flex flex-nowrap items-center">
        <button onclick="toggle_full_screen();" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-500">
            <div id="lyrpenuh" class="size-4 mx-auto text-gray-800 dark:text-neutral-200">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
              </svg>
            </div>
            <div id="lyrpenuhtutup" class="hidden size-4 mx-auto text-gray-800 dark:text-neutral-200">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9 3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5 5.25 5.25" />
              </svg>
            </div>
        </button>
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
        <button type="button" class="ml-3 size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-offcanvas-body-scrolling-with-backdrop">
          <span class="sr-only">Close</span>
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div> 
    </div>
        <div class="relative flex flex-col h-[calc(100%-75px)]">
            <!-- Header -->
            {{-- <header class="p-4 flex justify-between items-center gap-x-2">
              <a wire:navigate class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80 dark:text-white" href="#" aria-label="Brand">Brand</a>
      
              <div class="lg:hidden -me-2">
                <!-- Close Button -->
                <button type="button" class="flex justify-center items-center gap-x-3 size-6 bg-white border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:hover:text-neutral-200 dark:focus:text-neutral-200" data-hs-overlay="#hs-sidebar-footer">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                  <span class="sr-only">Close</span>
                </button>
                <!-- End Close Button -->
              </div>
            </header> --}}
            <!-- End Header -->
      
                <!-- Body -->
                <nav class="h-full mb-3 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                  <div class="flex flex-col flex-wrap w-full px-2 pb-0 hs-accordion-group" data-hs-accordion-always-open>

                    <ul class="pt-2 space-y-1">

                      
                @php
                $slugnya = url('/').'/@'
            @endphp
            <a id="hs-sidebar-footer-with-dropdown" class="w-full {{ Auth::check() ? 'inline-flex' : 'hidden' }}  items-center p-2 text-sm text-gray-800 rounded-md hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 border border-gray-200 dark:border-gray-700" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown"
            wire:navigate href='{{ Auth::check() ? auth()->user()->is_admin == 1 ? $slugnya.auth()->user()->partner->slug : '/branches' : ''}}' 
            >
              <img class="size-4 ml-0.5 mr-2" 
              
                @if (auth()->check() && $thisBranch->where('id', auth()->user()->branch_id )->value('image') != null)
                src="{{ url('storage/'.$thisBranch->where('id', auth()->user()->branch_id )->value('image')) }}" 
                @else
                src="{{ url('storage/kios.png') }}" 
                @endif
              
              alt="Avatar">
                <span class="text-start ml-1.5 font-bold w-full">
                  @if (auth()->check())
                  {{ $thisBranch->where('id', auth()->user()->branch_id )->value('name')}} 
                  @else
                  ....
                  @endif
                </span>
                <x-fas-map-marker-alt class="size-3.5 ms-auto mr-1" />
              
            </a>    

                      <li>
                        <a wire:navigate class="{{ request()->is('/') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200" href="/">
                          <x-fas-home class="size-4" />
                          Beranda
                        </a>
                      </li>
          
                      {{-- <li class="hs-accordion" id="projects-accordion">
                        <button type="button" class="hs-accordion-toggle w-full text-start {{ request()->is('products') || request()->is('categories') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200" aria-expanded="true" aria-controls="projects-accordion-sub-1-collapse-1">
                          <x-fas-bag-shopping class="size-4" />
                          Produk
          
                          <svg class="hidden text-gray-600 hs-accordion-active:block ms-auto size-4 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          
                          <svg class="block text-gray-600 hs-accordion-active:hidden ms-auto size-4 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
          
                        <div id="projects-accordion-sub-1-collapse-1" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" role="region" aria-labelledby="projects-accordion">
                          <ul class="pt-1 space-y-1 ps-7">

                          </ul>
                        </div>
                      </li> --}}

                            <li class="hs-accordion" id="users-accordion-sub-1">
                              <button type="button" class="{{ request()->is('products') || request()->is('categories') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200" aria-expanded="true" aria-controls="users-accordion-sub-1-collapse-1">
                                <x-fas-bag-shopping class="size-4" /> Produk
          
                                <svg class="hidden text-gray-600 hs-accordion-active:block ms-auto size-4 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          
                                <svg class="block text-gray-600 hs-accordion-active:hidden ms-auto size-4 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                              </button>
          
                              <div id="users-accordion-sub-1-collapse-1" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" role="region" aria-labelledby="users-accordion-sub-1">
                                <ul class="pt-1 space-y-1 ps-2">
                                  <li>
                                    <a wire:navigate class="{{ request()->is('products') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                    href="/products">
                                      <x-fas-cubes class="size-4" /> Semua Produk
                                    </a>
                                  </li>
                                  <li>
                                    <a wire:navigate class="{{ request()->is('categories') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                    href="/categories">
                                      <x-fas-tag class="size-4" /> Kategori
                                    </a>
                                  </li>
                                  {{-- <li>
                                    <a wire:navigate class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                    href="#">
                                      Buku
                                    </a>
                                  </li> --}}
                                </ul>
                              </div>
                            </li>
                            <li>
                              <a wire:navigate class="{{ request()->is('cart') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                              href="/cart">
                                <x-fas-cart-shopping class="size-4" /> Troli <span class="ms-auto py-0.5 px-1.5 inline-flex items-center gap-x-1.5 text-xs bg-gray-200 text-gray-800 rounded-full dark:bg-neutral-600 dark:text-neutral-200">{{ $total_count }} items</span>
                              </a>
                            </li>
                            @auth
                            <li>
                              <a wire:navigate class="{{ request()->is('my-orders-unpaid') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                              href="/my-orders-unpaid">
                                <x-fas-triangle-exclamation class="size-4" /> Belum Terbayar
                              </a>
                            </li>                      
                            <li>
                              <a wire:navigate class="{{ request()->is('my-orders') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                              href="/my-orders">
                                <x-fas-envelope class="size-4" /> Pesanan
                              </a>
                            </li>                      
                            <li>
                              <a wire:navigate class="{{ request()->is('payments') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                              href="/payments">
                                <x-fas-money-bill-wave class="size-4" /> Pembayaran
                              </a>
                            </li>                      
                            <li>
                              <a wire:navigate class="{{ request()->is('items') ? 'bg-linear-to-br from-green-400/50 to-green-700/50 dark:from-green-500/50 dark:to-green-800/50' : 'dark:bg-neutral-800' }} flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                              href="/items">
                                <x-fas-cube class="size-4" /> Barang Terjual
                              </a>
                            </li>    
                            @endauth                  
          
                      
                    </ul>
                  </div>
                </nav>
                <!-- End Body -->
      
            <!-- Footer -->
            <footer class="mt-auto p-2 space-y-2">          

              <div id="jadwalsholathariini" class="w-full ps-5 text-zinc-800 dark:text-zinc-100">
                {{ $today }}
              </div> 

              <div id="jadwalsholat" class="w-full aspect-[3/1] px-3">
                <div id="owl-jadwal-sholat" class="owl-carousel owl-theme"> 
                  <div class="item w-full aspect-[3/1] px-4 rounded-lg items-center flex justify-between text-white dark:text-zinc-800 bg-linear-to-br from-lime-500 to-lime-800 dark:from-lime-500 dark:to-lime-800">
                    <div class="text-lg font-bold"><span>Jadwal Sholat<br>Maghrib</span></div>
                    <div class="text-4xl font-bold">{{ $maghrib ?? 'Unconnected' }}</div>
                  </div>  
                  <div class="item w-full aspect-[3/1] px-4 rounded-lg items-center flex justify-between text-white dark:text-zinc-800 bg-linear-to-br from-lime-500 to-lime-800 dark:from-lime-500 dark:to-lime-800">
                    <div class="text-lg font-bold"><span>Jadwal Sholat<br>Isya</span></div>
                    <div class="text-4xl font-bold">{{ $isya ?? 'Unconnected' }}</div>
                  </div>  
                  <div class="item w-full aspect-[3/1] px-4 rounded-lg items-center flex justify-between text-white dark:text-zinc-800 bg-linear-to-br from-lime-500 to-lime-800 dark:from-lime-500 dark:to-lime-800">
                    <div class="text-lg font-bold"><span>Jadwal Sholat<br>Subuh</span></div>
                    <div class="text-4xl font-bold">{{ $subuh ?? 'Unconnected' }}</div>
                  </div>  
                  <div class="item w-full aspect-[3/1] px-4 rounded-lg items-center flex justify-between text-white dark:text-zinc-800 bg-linear-to-br from-lime-500 to-lime-800 dark:from-lime-500 dark:to-lime-800">
                    <div class="text-lg font-bold"><span>Mulai Waktu<br>Terbit</span></div>
                    <div class="text-4xl font-bold">{{ $terbit ?? 'Unconnected' }}</div>
                  </div>  
                  <div class="item w-full aspect-[3/1] px-4 rounded-lg items-center flex justify-between text-white dark:text-zinc-800 bg-linear-to-br from-lime-500 to-lime-800 dark:from-lime-500 dark:to-lime-800">
                    <div class="text-lg font-bold"><span>Mulai Waktu<br>Dhuha</span></div>
                    <div class="text-4xl font-bold">{{ $dhuha ?? 'Unconnected' }}</div>
                  </div>  
                  <div class="item w-full aspect-[3/1] px-4 rounded-lg items-center flex justify-between text-white dark:text-zinc-800 bg-linear-to-br from-lime-500 to-lime-800 dark:from-lime-500 dark:to-lime-800">
                    <div class="text-lg font-bold"><span>Jadwal Sholat<br>Dzuhur</span></div>
                    <div class="text-4xl font-bold">{{ $dzuhur ?? 'Unconnected' }}</div>
                  </div>  
                  <div class="item w-full aspect-[3/1] px-4 rounded-lg items-center flex justify-between text-white dark:text-zinc-800 bg-linear-to-br from-lime-500 to-lime-800 dark:from-lime-500 dark:to-lime-800">
                    <div class="text-lg font-bold"><span>Jadwal Sholat<br>Ashar</span></div>
                    <div class="text-4xl font-bold">{{ $ashar ?? 'Unconnected' }}</div>
                  </div> 
                </div>  
              </div>  
  

                {{-- <select wire:change='changeBranch()'
                    wire:model.live='branch'
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-none"
                    id="branch"
                    data-hs-select='{
                        "hasSearch": true,
                        "searchPlaceholder": "Search...",
                        "searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-green-500 focus:ring-green-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",
                        "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                        "placeholder": "Pilih Loc.",
                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 dark:text-neutral-200 \" data-title></span></button>",
                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 errorSelect relative py-[9px] ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
                        "dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                        "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                        "optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 dark:text-neutral-200 \" data-title></div></div></div>",
                        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                        }'>
                        @auth
                        <option selected value="{{ auth()->user()->branch_id }}"
                        data-hs-select-option='{
                          "icon" : "<img src=\" @if (auth()->user()->branch->image != null) {{Str::replace('%2F', '/',url('storage', auth()->user()->branch->image))}} @else {{ url('storage/kios.png') }} @endif \" class=\"object-cover object-center w-5 h-5\" />"
                          }'>
                        
                          {{ auth()->user()->branch->name }}
                        </option>
                        @endauth
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}"

                          data-hs-select-option='{
                          "icon" : "<img src=\" @if ($branch->image != null) {{Str::replace('%2F', '/',url('storage', $branch->image))}} @else {{ url('storage/kios.png') }} @endif \" class=\"object-cover object-center w-5 h-5\" />"
                          }'>
                          
                             {{ $branch->name }} {{ $branch->phone != 0 ? $branch->phone : '' }}
                        </option> 
                    @endforeach
                </select> --}}

              <!-- Account Dropdown -->
              <div class="hs-dropdown [--strategy:absolute] [--auto-close:inside] relative w-full inline-flex pt-2 border-t border-gray-200 dark:border-neutral-700">
                <button id="hs-sidebar-footer-example-with-dropdown" type="button" class="w-full inline-flex shrink-0 items-center gap-x-2 p-2 text-start text-sm text-gray-800 rounded-md hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                  <img class="size-9 object-cover rounded-full mr-2" 
                  @if (auth()->check() && auth()->user()->image != null)
                  src="{{ url('storage/'.auth()->user()->image) }}" 
                  @else
                  src="{{ url('storage/user.png') }}" 
                  @endif
                  alt="Avatar">
                  <div class="block">
                    <div class="font-bold line-clamp-1">{{ auth()->user()->name ?? 'Belum Login' }}</div>
                    <div class="font-base line-clamp-1">{{ auth()->user()->email ?? '' }}</div>
                  </div>
                  <svg class="shrink-0 size-3.5 ms-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                </button>
      
                <!-- Account Dropdown -->
                <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 w-60 transition-[opacity,margin] duration opacity-0 hidden z-20 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-neutral-900 dark:border-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-sidebar-footer-example-with-dropdown">
                  <div class="p-1">
                    @auth              
                    @if (auth()->user()->is_admin == 1)                        
                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" href="/admin">
                      Admin Panel
                    </a>
                      <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" href="/pos">
                        POS (Sales)
                      </a>
                      <a class="{{ Auth::user()->roles[0]->name === 'Admin' || Auth::user()->roles[0]->name === 'Kasir' ? "flex" : "hidden" }} items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" href="/dompet">
                        Dompet
                      </a>
                    @else
                        
                    @endif          
                    @endauth
                    <a wire:navigate class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" href="/my-account">
                      Akun Saya
                    </a>
                    <a wire:navigate class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" 
                    @auth href="/logout" @endauth @guest href="/login" @endguest
                    >
                    @auth Keluar @endauth @guest Masuk @endguest
                    </a>
                  </div>
                </div>
                <!-- End Account Dropdown -->
              </div>
              <!-- End Account Dropdown -->
            </footer>
            <!-- End Footer -->
        </div>
  </div>
  <!-- End Sidebar -->


  <!-- Start Navbar Bottom --> 
  <nav class="md:hidden {{ request()->is('pos') || Str::of(request()->fullUrl())->contains('/@') ? 'hidden' : 'fixed'}}
    {{ request()->is('laba-rugi') ? ' hidden' : 'fixed' }}
    {{ request()->is('neraca') ? ' hidden' : 'fixed' }}
    bottom-0 left-0 z-50 w-full h-10 pb-4 bg-white border-gray-300 dark:bg-gray-800 dark:border-gray-900">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto font-medium">
      
      @if (request()->is('/') || request()->is('cart'))
      <button aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-offcanvas-body-scrolling-with-backdrop" data-hs-overlay="#hs-offcanvas-body-scrolling-with-backdrop"
      class="inline-flex flex-col items-center justify-center px-5 hover:bg-transparent dark:hover:transparent group">
          <div class="w-5 h-5 -mb-3 mx-auto {{ request()->is('my-orders')?' text-amber-600' : 'text-gray-500'}} dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <x-fas-list-alt class="text-gray-500 dark:text-white hover:text-gray-500 size-6" />
          </div>
      </button>
      @else
      <a href="javascript:history.back()"
      class="inline-flex flex-col items-center justify-center px-5 hover:bg-transparent dark:hover:transparent group">
          <div class="w-5 h-5 -mb-3 mx-auto text-gray-500 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <x-far-arrow-alt-circle-left class="text-gray-500 dark:text-white hover:text-gray-500 size-6" />
          </div>
      </a>  
      @endif

      <a wire:navigate href="/branches"
      class="inline-flex flex-col items-center justify-center px-5 hover:bg-transparent dark:hover:transparent group">
          <div class="w-5 h-5 -mb-3 mx-auto {{ request()->is('branches')?' text-amber-600' : 'text-gray-500'}} dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <x-fas-store class="text-gray-500 dark:text-white hover:text-gray-500 size-6" />
          </div>
      </a>
      
      <!-- Popover -->
      <div class="hs-tooltip [--trigger:click] inline-flex flex-col items-center justify-center px-5 bg-transparent dark:transparent group">
        <button type="button" class="absolute items-center justify-center text-sm font-semibold rounded-full shadow-md bottom-2 size-12 bg-yellow-200 dark:bg-yellow-200 hs-tooltip-toggle hover:bg-gray-800 focus:outline-hidden focus:bg-green-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:hover:bg-white dark:focus:bg-green-500">
          <img src="{{ url('storage/TegarJayaBerkah-LogoAja.png') }}" alt="" class="ml-[0.65rem] size-7" />

          <div class="absolute z-10 invisible inline-block px-1 py-2 text-sm text-gray-600 transition-opacity bg-white border border-gray-200 rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" role="tooltip">
              <a wire:navigate class="justify-between w-40 flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
              @auth
                href="/{{ '@'.$thisPartner->where('id', auth()->user()->partner_id)->value('slug') }}"
              @endauth
              @guest
                href="/{{ '@'.$thisPartner }}"
              @endguest
              >
                <span>Profil Toko</span><x-fas-home class="size-5"/>
              </a>
              <a wire:navigate class="justify-between w-40 flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
              href="/categories">
                <span>Kategori</span><x-fas-tag class="size-5"/>
              </a>
              <a wire:navigate class="justify-between w-40 flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
              href="/products">
                <span>Produk</span><x-fas-cubes class="size-5"/>
              </a>
          </div>

          <div class="absolute -left-[calc(60vw)] bottom-8 w-[120vw] h-[100vh] -z-[10] invisible transition-opacity bg-white/30 dark:bg-zinc-900/30 backdrop-blur-sm rounded-lg opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible role="tooltip">

          </div>
        </button>
      </div>
      <!-- End Popover -->

      <a wire:navigate href="/cart" 
      class="inline-flex flex-col items-center justify-center px-5 hover:bg-transparent dark:hover:transparent group">
        <div class="w-5 h-5 -mb-2 mx-auto {{ request()->is('cart')?' text-amber-600' : 'text-gray-500'}} dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <x-fas-bag-shopping class="text-gray-500 dark:text-white hover:text-gray-500 size-6" />
          <div class="{{ $total_count == 0  ? 'hidden' : 'relative' }} {{ $total_count > 9  ? 'pr-[18px]' : '' }} bottom-10 left-4 py-0.5 px-1.5 rounded-full bg-white border border-gray-400 text-xs font-medium text-green-600">{{ $total_count }}</div>
        </div>
      </a>

      <a wire:navigate href="/my-orders" 
      class="inline-flex flex-col items-center justify-center px-5 hover:bg-transparent dark:hover:transparent group">
          <div class="w-5 h-5 -mb-3 mx-auto {{ request()->is('my-orders')?' text-amber-600' : 'text-gray-500'}} dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <x-fas-envelope class="text-gray-500 dark:text-white hover:text-gray-500 size-6" />
          </div>
      </a>

    </div>
  </nav>
  <!-- End Navbar Bottom -->


  <script>

     function toggle_full_screen()
        {
            if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen))
            {
                if (document.documentElement.requestFullScreen){
                    document.documentElement.requestFullScreen();
                   document.getElementById("lyrpenuh").classList.add("hidden");
                   document.getElementById("lyrpenuhtutup").classList.remove("hidden");
                }
                else if (document.documentElement.mozRequestFullScreen){ /* Firefox */
                    document.documentElement.mozRequestFullScreen();
                   document.getElementById("lyrpenuh").classList.add("hidden");
                   document.getElementById("lyrpenuhtutup").classList.remove("hidden");
                }
                else if (document.documentElement.webkitRequestFullScreen){   /* Chrome, Safari & Opera */
                    document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                   document.getElementById("lyrpenuh").classList.add("hidden");
                   document.getElementById("lyrpenuhtutup").classList.remove("hidden");
                }
                else if (document.msRequestFullscreen){ /* IE/Edge */
                    document.documentElement.msRequestFullscreen();
                   document.getElementById("lyrpenuh").classList.add("hidden");
                   document.getElementById("lyrpenuhtutup").classList.remove("hidden");
                }
            }
            else
            {
                if (document.cancelFullScreen){
                    document.cancelFullScreen();
                   document.getElementById("lyrpenuh").classList.remove("hidden");
                   document.getElementById("lyrpenuhtutup").classList.add("hidden");
                }
                else if (document.mozCancelFullScreen){ /* Firefox */
                    document.mozCancelFullScreen();
                   document.getElementById("lyrpenuh").classList.remove("hidden");
                   document.getElementById("lyrpenuhtutup").classList.add("hidden");
                }
                else if (document.webkitCancelFullScreen){   /* Chrome, Safari and Opera */
                    document.webkitCancelFullScreen();
                   document.getElementById("lyrpenuh").classList.remove("hidden");
                   document.getElementById("lyrpenuhtutup").classList.add("hidden");
                }
                else if (document.msExitFullscreen){ /* IE/Edge */
                    document.msExitFullscreen();
                   document.getElementById("lyrpenuh").classList.remove("hidden");
                   document.getElementById("lyrpenuhtutup").classList.add("hidden");
                }
            }
        }
  </script>

  <style>
    #jadwalsholathariini{
    -webkit-animation: 3s ease 0s normal forwards 1 fadein;
    animation: 3s ease 0s normal forwards 1 fadein;
    }
    #jadwalsholat{
    -webkit-animation: 3s ease 0s normal forwards 1 fadein;
    animation: 3s ease 0s normal forwards 1 fadein;
    }

    @keyframes fadein{
        0% { opacity:0; }
        66% { opacity:0; }
        100% { opacity:1; }
    }

    @-webkit-keyframes fadein{
        0% { opacity:0; }
        66% { opacity:0; }
        100% { opacity:1; }
    }
  </style>
</div>