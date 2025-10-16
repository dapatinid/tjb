<div class="mx-auto" x-data="posApp()" x-init="init()">

{{-- dalam head --}}
{{-- <script defer src="https://unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script> --}}
{{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" /> --}}
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
{{-- dalam head --}}

    <script>
        window.initialCart = @json($initialCart ?? []);
    </script>
  <div x-data="{ showCart: false, showModalMenu: false, showModal: false, showSidebar: false }" 
        class="grid md:grid-cols-3 grid-cols-1 md:gap-x-2 gap-y-2 gap-0">

    <!-- Modal Checkout -->
    <div 
        x-show="showModalMenu" 
        x-transition.opacity 
        x-cloak
        class="fixed inset-0 flex items-center justify-center z-50 bg-black/50 bg-opacity-50"
        @click.self="showModalMenu = false">

        <div 
            class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/3"
            x-transition.scale>
            
            <div class="text-xl font-bold border-b border-gray-200 p-3 text-center">MENU</div>
            <div class="flex flex-wrap p-3 gap-2">
              <a href="/" class="px-2 py-1 text-white bg-blue-500 rounded cursor-pointer">Home</a>
              <a href="/admin" class="px-2 py-1 text-white bg-blue-500 rounded cursor-pointer">Admin Panel</a>
              <a href="/my-orders-unpaid" class="px-2 py-1 text-white bg-blue-500 rounded cursor-pointer">Order (Unpaid)</a>
              <a href="/my-orders" class="px-2 py-1 text-white bg-blue-500 rounded cursor-pointer">Order (Paid)</a>
              <a href="/payments" class="px-2 py-1 text-white bg-blue-500 rounded cursor-pointer">Payments</a>
              <a href="/items-sold" class="px-2 py-1 text-white bg-blue-500 rounded cursor-pointer">Stock</a>
            </div>

            <div class="flex justify-end gap-2 p-3 border-t border-gray-200">
                <button @click="showModalMenu = false" class="px-2 py-1 bg-gray-300 rounded">Batal</button>
            </div>
        </div>
    </div>
    <!-- Modal Clear Cart -->
    <div 
        x-show="showModal" 
        x-transition.opacity 
        x-cloak
        class="fixed inset-0 flex items-center justify-center z-50 bg-black/50 bg-opacity-50"
        @click.self="showModal = false">

        <div 
            class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/3 p-6"
            x-transition.scale>
            
            <h2 class="text-lg font-bold mb-4">Kosongkan Cart</h2>
            <p class="mb-4">Apakah Anda yakin hapus semua item di cart?</p>

            <div class="flex justify-end gap-2">
                <button @click="showModal = false" class="px-2 py-1 bg-gray-300 rounded">Batal</button>
                <button @click="clearCart(); showModal = false" class="px-2 py-1 bg-red-600 text-white rounded">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Modal Detail Produk -->
    <div 
        x-show="showProductModal" 
        x-transition.opacity 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        @click.self="showProductModal = false"
    >
      <div class="bg-white p-4 rounded-lg shadow-lg w-11/12 md:w-1/3">
          <div class="flex gap-2">
            <h2 class="text-lg font-bold mb-2" x-text="productDetail.name"></h2>
            <h2 class="text-lg font-bold mb-2 text-green-500" x-text="productDetail.variant ? `${productDetail.variant}` : ``"></h2>
          </div>
          <img :src="productDetail.images && productDetail.images.length > 0 
            ? '/storage/' + productDetail.images[0] 
            : '/storage/foto-produk.png'" alt="" class="w-full object-cover rounded mb-2 mx-auto aspect-square">
          <p class="font-semibold text-lg" x-text="`Rp${formatMoney(productDetail.price)}`"></p>
          <p class="text-gray-700 text-sm mb-2" x-text="productDetail.description"></p>

          <div class="text-right mt-3">
              <button @click="showProductModal = false" class="px-3 py-1 bg-gray-300 rounded">Tutup</button>
          </div>
      </div>
    </div>

    <!-- Sidebar Cart (mobile: slide-in, desktop: tetap terlihat) -->
    <div 
        class="fixed inset-y-0 left-0 w-full min-h-screen bg-white transform transition-transform duration-300 z-20
               md:static md:translate-x-0 " 
        :class="{ '-translate-x-full': !showCart, 'translate-x-0': showCart }">

        <div class="sticky top-0 p-[15px] flex justify-between items-center bg-white border-b border-gray-200">
          <h2 class="font-bold text-lg" x-text="`Cart (${(qtybyqty)}/${(countcart)})`"></h2> 
          <div class="flex justify-end gap-3">
            <button type="button"  class="text-blue-500" @click="showModalMenu = true; showCart = false">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="25" height="25">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
              </svg>
            </button>            
            <button  type="button" class="text-yellow-500 cursor-pointer ">
                {{-- TOMBOL FULLSCREEN START --}}
                <svg onclick="toggle_full_screen()" id="layarpenuh" class="cursor-pointer hover:text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                </svg> 
                <svg onclick="toggle_full_screen()" id="layarpenuhtutup" class="hidden cursor-pointer hover:text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
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
            <button class="md:hidden text-red-500" @click="showCart = false">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="25" height="25">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
          </div>       
        </div>

        <!-- isi cart -->
        <div class="px-3 pt-1.5 pb-24 h-[calc(100vh-4rem)] md:h-auto overflow-y-auto overscroll-contain scrollbar-thin scrollbar-thumb-gray-300" style="-webkit-overflow-scrolling: touch;">
            <template x-if="cart.length === 0">
                <div class="text-sm text-gray-500 text-center">Cart kosong</div>
            </template>

            <div class="space-y-2 " >
                <template x-for="(item, idx) in cart" :key="item.id">
                <div class="block items-center gap-2 border border-gray-200 rounded p-2">
                    <div class="w-full flex justify-between">
                        <div class="flex justify-start gap-2 cursor-pointer hover:underline hover:text-blue-500"  @click="showProduct(item.id)">
                            <div class="font-medium text-sm" x-text="item.name"></div>
                            <div class="font-light text-sm" x-text="item.variant"></div>
                        </div>
                        <div>
                            <button @click="removeItem(idx)" class="text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="w-full flex space-x-2">
                        <div class="w-1/3">
                            <input type="number" step="100" class="w-full text-sm border border-gray-200 rounded px-2 py-0" 
                            x-mask:dynamic="(value) => {
                            const numeric = value.replace(/[^0-9]/g, '');
                            if (numeric === '') return '0';   // tampilkan 0 jika kosong
                            return Number(numeric).toLocaleString('id-ID');
                            }"
                            x-model="item.price_display"
                            @input="updatePrice(item)">
                        </div>
                        <div class="w-1/3 flex justify-center gap-1">
                            <button @click="decrementQty(item)" onmouseup="setTimeout(() => this.blur(), 200)" class="items-center text-red-400 transition-colors duration-300 active:text-black focus:text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                                <input type="number" class="w-12 text-sm border border-gray-200 rounded px-2 py-0 text-center" x-model.number="item.quantity" @input="recalcItem(item)">
                            <button @click="incrementQty(item)" onmouseup="setTimeout(() => this.blur(), 200)" class="items-center text-blue-500 transition-colors duration-300 active:text-black focus:text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="w-1/3 flex justify-end text-sm" x-text="`${formatMoney(item.subtotal)}`">
                        </div>
                    </div>
                </div>
                </template>
            </div>

            <div class="mt-4">
                <div class="flex justify-between">
                <div class="font-bold text-sm" x-text="`Total : ${(totalweight)} kg`"></div>
                {{-- <div class="font-bold text-sm" x-text="`Total : `"></div> --}}
                <div class="font-bold text-sm" x-text="`Rp${formatMoney(total)}`"></div>
                </div>

                <div class="mt-3 flex gap-2">
                {{-- <input x-model="customer_name" placeholder="Nama pelanggan (opsional)" class="border border-gray-200 px-3 py-2 rounded w-full"> --}}
                <button @click="showModal = true" 
                    {{-- @click="clearCart()"  --}}
                    class="px-3 py-2 border border-gray-200 rounded grow-0 text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
                <button x-show="cart.length > 0" 
                  x-transition.delay.1000ms
                  wire:click="triggerLoadCart"  
                  {{-- @click="checkout()"  --}}
                  @click="hiddenBtmCo()" 
                  onmouseup="setTimeout(() => this.blur(), 200);"
                  wire:loading.attr="disabled"
                  class="px-3 py-2 bg-green-600 text-white rounded w-full transition-colors duration-300 active:bg-green-800 focus:bg-green-800">
                    <!-- Normal Text -->
                    <span wire:loading.remove>
                        Checkout
                    </span>

                    <!-- Loading Spinner -->
                    <span wire:loading class="flex items-center">
                        <span class="flex flex-nowrap gap-2 justify-center">
                          <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                          </svg>
                          <span class="text-sm">
                            Loading...
                          </span>
                        </span>
                    </span>
                </button>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Products -->
    <div class="col-span-2 bg-white h-full">
      <div class="flex items-center gap-2 mb-4 sticky top-0 p-2 border-b border-gray-200" style="background-color: white; z-index: 10;">
        <button @click="showCart = true" class="md:hidden">
            <div x-text="`${(qtybyqty)}`" x-show="qtybyqty > 0" class="absolute px-1 rounded-full -mt-1 -ml-1 text-white bg-gray-800 "></div>
            <div class="p-2 bg-amber-400 text-white rounded">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="25" height="25">
                <path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>
        <div class="relative w-full">
        <input x-model.debounce.500ms="q" @input.debounce.500ms="fetchProducts()" placeholder="Cari produk..." class="border border-gray-200 px-3 py-2 rounded w-[calc(100%)]">
            <!-- Tombol X -->
        <button 
            type="button" 
            x-show="q" 
            @click="q = ''; fetchProducts()" 
            class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-gray-600"
        >
            ✕
        </button>
        </div>

        <!-- Tombol ikon filter -->
        <button 
            @click="showSidebar = true" 
            class="flex items-center gap-1 p-2 border border-gray-200 rounded-lg hover:bg-gray-100 transition">
            <!-- Heroicon filter -->
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
              class="size-5 text-gray-700">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
              </svg>

            {{-- <span class="text-sm font-medium">Filter</span> --}}
        </button>

        <!-- Overlay gelap -->
        <div 
            x-show="showSidebar"
            @click="showSidebar = false"
            x-transition.opacity
            class="fixed inset-0 bg-black/50 z-40"
            style="display: none;">
        </div>

        <!-- Sidebar kanan -->
        <div 
            x-show="showSidebar"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed top-0 right-0 w-80 sm:w-96 h-full bg-white shadow-xl z-50 flex flex-col"
            style="display: none;">

            <!-- Header sidebar -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Filter</h2>
                <button @click="showSidebar = false" class="p-2 rounded hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                        stroke="currentColor" class="w-5 h-5 text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Isi sidebar -->
            <div class="p-4 space-y-4 overflow-y-auto flex-1">
                <!-- Dropdown perPage -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Items per page</label>
                    <select x-model="perPage" @change="fetchProducts()" class="w-full border-gray-300 rounded-md">
                      <option value="5">5</option>
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      <option value="200">200</option>
                      <option value="500">500</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-gray-200">

                  <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>

                  <div class="flex flex-wrap gap-2">
                    <button
                      {{-- x-show="activeBrands.length > 0" --}}
                      @click="clearBrand()"
                      class="px-2 py-1 rounded-full border text-xs transition-colors duration-200 flex-shrink-0"
                        :class="activeBrands.length <= 0
                          ? 'bg-yellow-500 text-white border-yellow-500'
                          : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border-gray-300'"            
                    >
                      Semua
                    </button>
                    <template x-for="brn in brands" :key="brn.id">
                      <button
                        @click="toggleBrand(brn.id)"
                        class="px-2 py-1 rounded-full border text-xs transition-colors duration-200 flex-shrink-0"
                        :class="activeBrands.includes(brn.id)
                          ? 'bg-yellow-500 text-white border-yellow-500'
                          : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border-gray-300'"
                        x-text="brn.name"
                      ></button>
                    </template>
                  </div>

                </div>

                <div class="pt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sortir berdasarkan</label>
                    <div class="flex items-center justify-between bg-gray-100 rounded-lg p-1">
                        <label 
                            class="flex-1 text-center cursor-pointer py-2 rounded-lg text-xs font-medium"
                            :class="sortBy === 'name' ? 'bg-white shadow text-gray-800' : 'text-gray-500'"
                        >
                            <input @change="fetchProducts()" type="radio" value="name" x-model="sortBy" class="hidden" />
                            Nama
                        </label>
                        <label 
                            class="flex-1 text-center cursor-pointer py-2 rounded-lg text-xs font-medium"
                            :class="sortBy === 'updated_at' ? 'bg-white shadow text-gray-800' : 'text-gray-500'"
                        >
                            <input @change="fetchProducts()" type="radio" value="updated_at" x-model="sortBy" class="hidden" />
                            Last Edited
                        </label>
                        <label 
                            class="flex-1 text-center cursor-pointer py-2 rounded-lg text-xs font-medium"
                            :class="sortBy === 'price' ? 'bg-white shadow text-gray-800' : 'text-gray-500'"
                        >
                            <input @change="fetchProducts()" type="radio" value="price" x-model="sortBy" class="hidden" />
                            Harga
                        </label>
                    </div>
                </div>

                <!-- Sort Order -->
                <div class="pt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                    <div class="flex items-center justify-between bg-gray-100 rounded-lg p-1">
                        <label 
                            class="flex-1 text-center cursor-pointer py-2 rounded-lg text-sm font-medium"
                            :class="sortOrder === 'asc' ? 'bg-white shadow text-gray-800' : 'text-gray-500'">
                            <input type="radio" value="asc" x-model="sortOrder" @change="fetchProducts()" class="hidden" />
                            Naik
                        </label>
                        <label 
                            class="flex-1 text-center cursor-pointer py-2 rounded-lg text-sm font-medium"
                            :class="sortOrder === 'desc' ? 'bg-white shadow text-gray-800' : 'text-gray-500'">
                            <input type="radio" value="desc" x-model="sortOrder" @change="fetchProducts()" class="hidden" />
                            Turun
                        </label>
                    </div>
                </div>                
            </div>

            <!-- Footer (opsional) -->
            <div class="p-4 border-t border-gray-200">
                <button 
                    @click="showSidebar = false" 
                    class="w-full bg-gray-800 text-white py-2 rounded-md hover:bg-gray-700 transition">
                    Selesai
                </button>
            </div>
        </div>        
      </div>

      <!-- Badge toggle kategori -->
      <div class="relative -mt-1 mb-1 mx-2">
        <div class="absolute left-0 top-0 h-full w-10 md:hidden bg-gradient-to-r from-white to-transparent pointer-events-none z-5"></div>
        <div class="absolute right-0 top-0 h-full w-10 md:hidden bg-gradient-to-l from-white to-transparent pointer-events-none z-5"></div>

        <div
          class="flex gap-2 overflow-x-auto scrollbar-hide md:px-0 px-1"
          style="white-space: nowrap; -ms-overflow-style: none; scrollbar-width: none;"
        >

          <button
            {{-- x-show="activeCategories.length > 0" --}}
            @click="clearCategory()"
            class="px-2 py-1 rounded-full border text-xs transition-colors duration-200 flex-shrink-0"
              :class="activeCategories.length <= 0
                ? 'bg-yellow-500 text-white border-yellow-500'
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border-gray-300'"            
          >
            Semua
          </button>

          <template x-for="cat in categories" :key="cat.id">
            <button
              @click="toggleCategory(cat.id)"
              class="px-2 py-1 rounded-full border text-xs transition-colors duration-200 flex-shrink-0"
              :class="activeCategories.includes(cat.id)
                ? 'bg-yellow-500 text-white border-yellow-500'
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border-gray-300'"
              x-text="cat.name"
            ></button>
          </template>

        </div>
      </div>  

      <div class="grid lg:grid-cols-4 md:grid-cols-3 xs:grid-cols-3 grid-cols-2 gap-2 px-2">
    <template x-for="(col, i) in roundRobin(products.data, colsCount)" :key="i">
      <div>
        <template x-for="p in col" :key="p.id">
          <div class="relative ">
          <div class="absolute top-2.5 left-2 bg-white p-1 rounded-full" @click="showProduct(p.id)"> 
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
          </div>
              <!-- 🔵 Badge jumlah produk di cart -->
              <template x-if="cart.some(item => item.id === p.id)">
                  <div 
                      x-transition.scale.origin.top.right
                      class="absolute top-2.5 right-2 bg-white border border-gray-300 rounded-full flex items-center justify-between h-6 shadow-md text-xs select-none"
                  >
                      <!-- Tombol - -->
                      <button 
                          class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold transition-colors duration-300 active:bg-red-400 focus:bg-red-400" onmouseup="setTimeout(() => this.blur(), 200)"
                          @click.stop="
                              let item = cart.find(i => i.id === p.id);
                              if (item) {
                                  if (item.quantity > 1) {
                                      decrementQty(item);
                                  } else {
                                      cart = cart.filter(i => i.id !== p.id);
                                      // this.recalcTotals(); // opsional kalau kamu mau recalculasi total
                                  }
                              }
                          "
                      >−</button>

                      <!-- Angka quantity -->
                      <span class="text-gray-800 font-semibold mx-1" 
                            x-text="(cart.find(i => i.id === p.id) || {}).quantity || ''">
                      </span>

                      <!-- Tombol + -->
                      <button 
                          class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold transition-colors duration-300 active:bg-blue-400 focus:bg-blue-400" onmouseup="setTimeout(() => this.blur(), 200)"
                          @click.stop="
                              let item = cart.find(i => i.id === p.id);
                              if (item) {
                                  incrementQty(item);
                              }
                          "
                      >+</button>
                  </div>
              </template>
              <button @click="addToCart(p)"  onmouseup="setTimeout(() => this.blur(), 300)" 
                :class="cart.some(item => item.id === p.id) ? 'bg-yellow-300': p.is_active == 0 ? 'bg-green-400': 'bg-white'" 
                {{-- :class="cart.some(item => item.id === p.id) ? 'bg-yellow-300': 'bg-white'"  --}}
                {{-- :class="p.is_active == 0 ? 'bg-green-200': 'bg-white'"  --}}
                class="w-full my-2 p-3 block rounded border border-gray-200 transition-colors duration-300 active:bg-blue-400 focus:bg-blue-400">
                <img :src="p.images && p.images.length > 0 
                ? '/storage/' + p.images[0] 
                : '/storage/foto-produk.png'" alt="" class="rounded aspect-square object-cover">
                <div x-text="p.name" class="font-semibold text-sm/4 mt-1 text-start line-clamp-2"></div>
                <div class="flex flex-wrap justify-between gap-1">
                    <em class="text-xs pe-1 line-clamp-2" x-text="p.variant"></em>
                    <div class="flex ms-auto"><span class="text-xs" x-text="`Rp${formatMoney(p.price)}`"></span></div>       
                </div>            
              </button>
          </div>
        </template>
      </div>
    </template>
      </div>

      <!-- pagination -->
      <div class="flex flex-wrap items-center md:justify-between justify-center mt-4 p-4 gap-2">
        <div class="flex flex-wrap gap-2">
          <div x-text="`Total : ${products.total || 0}`"></div>
          <div x-text="`${products.from || 0}-${products.to || 0}`"></div>
        </div>
        <div class="flex gap-2 md:w-auto md:justify-end w-full justify-center">
          <button :disabled="!products.prev_page_url" @click="fetchProducts(products.first_page_url)" class="px-3 py-1 border border-gray-100 bg-white transition-colors duration-300 active:bg-yellow-400 focus:bg-yellow-400 disabled:bg-gray-200 rounded" onmouseup="setTimeout(() => this.blur(), 200)" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
            </svg>
          </button>
          <button :disabled="!products.prev_page_url" @click="fetchProducts(products.prev_page_url)" class="px-3 py-1 border border-gray-100 bg-white transition-colors duration-300 active:bg-yellow-400 focus:bg-yellow-400 disabled:bg-gray-200 rounded" onmouseup="setTimeout(() => this.blur(), 200)" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
          </button>
          <button disabled class="px-3 py-1 border border-gray-100 bg-gray-800 rounded text-white" x-text="products.current_page" ></button>
          <button :disabled="!products.next_page_url" @click="fetchProducts(products.next_page_url)" class="px-3 py-1 border border-gray-100 bg-white transition-colors duration-300 active:bg-yellow-400 focus:bg-yellow-400 disabled:bg-gray-200 rounded" onmouseup="setTimeout(() => this.blur(), 200)" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
          </button>
          <button :disabled="!products.next_page_url" @click="fetchProducts(products.last_page_url)" class="px-3 py-1 border border-gray-100 bg-white transition-colors duration-300 active:bg-yellow-400 focus:bg-yellow-400 disabled:bg-gray-200 rounded" onmouseup="setTimeout(() => this.blur(), 200)" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
            </svg>
          </button>
        </div>
      </div>
    </div>


  </div>


<script>
function posApp() {
  return {
    products: { data: [], total: 0, next_page_url: null, prev_page_url: null },
    q: '',
    perPage: 50,
    cart: [],
    customer_name: '',
    sortBy: 'name',
    sortOrder: 'asc',

    showProductModal: false,
    productDetail: {},

    categories: @js($categories), // dari controller
    brands: @js($brands), // dari controller

    activeCategories: [],
    activeBrands: [],

    toggleCategory(id) {
      const i = this.activeCategories.indexOf(id);
      if (i === -1) {
        // jika belum ada, tambahkan
        this.activeCategories.push(id);
      } else {
        // jika sudah ada, hapus
        this.activeCategories.splice(i, 1);
      }
      this.fetchProducts();
    },

    clearCategory() {
      this.activeCategories = [];
      this.fetchProducts();
    },   
    toggleBrand(id) {
      const i = this.activeBrands.indexOf(id);
      if (i === -1) {
        // jika belum ada, tambahkan
        this.activeBrands.push(id);
      } else {
        // jika sudah ada, hapus
        this.activeBrands.splice(i, 1);
      }
      this.fetchProducts();
    },

    clearBrand() {
      this.activeBrands = [];
      this.fetchProducts();
    },   

    colsCount: 2,
    updateCols() {
      if (window.matchMedia('(min-width: 1024px)').matches) {
        this.colsCount = 4; // lg:grid-cols-4
      } else if (window.matchMedia('(min-width: 768px)').matches) {
        this.colsCount = 3; // md:grid-cols-3
      } else if (window.matchMedia('(min-width: 430px)').matches) {
        this.colsCount = 3; // xs:grid-cols-3
      } else {
        this.colsCount = 2; // grid-cols-2 (mobile)
      }
    },
    roundRobin(arr, cols) {
      const result = Array.from({ length: cols }, () => []);
      arr.forEach((item, i) => result[i % cols].push(item));
      return result;
    },    

    init() {
        // Mengetahui ukuran lebar layar
        this.updateCols();
        window.addEventListener('resize', () => this.updateCols());

        // Jika localStorage kosong maka isi dari window.initialCart (data yang disiapkan server)
        const saved = localStorage.getItem('pos_cart');
        if (saved) {
        try { this.cart = JSON.parse(saved); } catch(e) { this.cart = []; }
        } else if (window.initialCart && window.initialCart.length > 0) {
        this.cart = window.initialCart.map(item => ({
            id: item.id,
            name: item.name || '',
            variant: item.variant || '',
            weight: Number(item.weight) || '',
            quantity: Number(item.quantity) || 1,
            price: Number(item.price) || 0,
            price_display: Number(item.price).toLocaleString('id-ID') || 0,
            subtotal: Number(item.subtotal) || (Number(item.quantity) * Number(item.price)),
            subtotalweight: Number(item.total_weight) || (Number(item.quantity) * Number(item.weight)),
        }));
            localStorage.setItem('pos_cart', JSON.stringify(this.cart));
        }
        this.fetchProducts();
    },

    async showProduct(id) {
      this.showProductModal = true
      this.productDetail = { name: 'Loading...', variant: '', image_url: '', price: 0, description: '' }

      try {
        let response = await fetch(`/api/products/${id}`)
        if (!response.ok) throw new Error('Gagal ambil data')
        this.productDetail = await response.json()
      } catch (err) {
        console.error(err)
        this.productDetail = { name: 'Error memuat produk', description: '', price: 0 }
      }
    },    

    async fetchProducts(url = null) {
      try {
        let endpoint;

        if (url) {
          endpoint = url;
        } else {
          const query = encodeURIComponent(this.q || '');
          const sortBy = this.sortBy !== 'name' ? this.sortBy : 'name';
          const sortOrder = this.sortOrder !== 'asc' ? this.sortOrder : 'asc';

          let categoryParams = '';
          if (this.activeCategories.length > 0) {
            categoryParams = this.activeCategories.map(id => `&category_id[]=${id}`).join('');
          }
          let brandParams = '';
          if (this.activeBrands.length > 0) {
            brandParams = this.activeBrands.map(id => `&brand_id[]=${id}`).join('');
          }

          endpoint = `/api/products?q=${query}&per_page=${this.perPage}&sort_by=${sortBy}&sort_order=${sortOrder}${categoryParams}${brandParams}`;
        }

        const res = await fetch(endpoint);
        if (!res.ok) throw new Error('Gagal mengambil produk');

        const data = await res.json();

        // tetap gunakan struktur lama
        this.products = data;
      } catch (e) {
        console.error(e);
      }
    },

    addToCart(p) {
      const found = this.cart.find(i => i.id === p.id);
      if (found) {
        found.quantity = (found.quantity||0) + 1;
        found.price = Number(found.price) || Number(p.price);
        found.subtotal = Number((found.quantity * found.price).toFixed(2));
        found.subtotalweight = Number((found.quantity * found.weight).toFixed(2));
      } else {
        this.cart.push({
          id: p.id,
          name: p.name,
          variant: p.variant,
          weight: Number(p.weight) ?? 0,
          quantity: 1,
            price: Number(p.price),
            price_display: Number(p.price).toLocaleString('id-ID'),
          subtotal: Number(p.price),
          subtotalweight: Number(p.weight),
        });
      }
      this.saveCart();
    },

    incrementQty(item) {
    item.quantity = (Number(item.quantity) || 0) + 1;
    this.recalcItem(item);
    },
    decrementQty(item) {
    item.quantity = (Number(item.quantity) || 1) - 1;
    if (item.quantity < 1) item.quantity = 1; // minimal 1
    this.recalcItem(item);
    },

    updatePrice(item) {
    const raw = item.price_display.replace(/[^0-9]/g, '');
    item.price = raw === '' ? 0 : Number(raw);
    this.recalcItem(item);
    },

    recalcItem(item) {
      item.quantity = Math.max(1, Number(item.quantity)||1);
      item.price = Number(item.price)||0;
      item.subtotal = Number((item.quantity * item.price).toFixed(2));
      item.subtotalweight = Number((item.quantity * item.weight).toFixed(2));
      item.price_display = item.price ? item.price.toLocaleString('id-ID') : '';
      this.saveCart();
    },

    removeItem(idx) {
      this.cart.splice(idx,1);
      this.saveCart();
    },

    clearCart() {
      this.cart = [];
      this.customer_name = '';
      localStorage.removeItem('pos_cart');
    },

    hiddenBtmCo() {
      this.cart.length = 0;
    },

    saveCart() {
      localStorage.setItem('pos_cart', JSON.stringify(this.cart));
    },

    get qtybyqty() {
      return this.cart.reduce((sum, it) => sum + (Number(it.quantity)||0), 0).toFixed(0);
    },
    get countcart() {
      return this.cart.length.toFixed(0);
    },
    get total() {
      return this.cart.reduce((sum, it) => sum + (Number(it.subtotal)||0), 0).toFixed(2);
    },
    get totalweight() {
      return this.cart.reduce((sum, it) => sum + (Number(it.subtotalweight)||0), 0).toFixed(2);
    },

    formatMoney(v) {
      // simple formatting, sesuaikan bila mau lokal
      return Number(v).toLocaleString('id-ID', {minimumFractionDigits:0, maximumFractionDigits:0});
    },

    // async checkout() {
    //     if (this.cart.length === 0) {
    //         alert('Cart kosong');
    //         return;
    //     }

    //     this.saveCart();

    //     const payload = {
    //         customer_name: this.customer_name || null,
    //         items: this.cart.map(i => ({ id: i.id, name: i.name, variant: i.variant, weight: i.weight, quantity: i.quantity, price: i.price }))
    //     };

    //     try {
    //         const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    //         const res = await fetch('/api/checkout', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'Accept': 'application/json',
    //             'X-CSRF-TOKEN': token
    //         },
    //         body: JSON.stringify(payload)
    //     });

    //     const json = await res.json();
    //     if (!res.ok) throw new Error(json.message || 'Checkout gagal');

    //     // alert(`Checkout sukses. ID transaksi: ${json.cart_id} — Total: Rp ${this.formatMoney(json.total)}`);
    //     alert(`SUKSES. Total items: ${json.totalcount} — Total amount: Rp ${this.formatMoney(json.total)}`);
    //         this.clearCart();
    //         window.location.href = '/checkout?branch_id=<?= Auth::user()->branch_id ?>&shipping_method=self_pickup&sales_type=self_pickup&payment_method=cash&rekening=KAS+KASIR&date_order=<?= date('Y') ?>-<?= date('m') ?>-<?= date('d') ?>T<?= date('H') ?>%3A<?= date('i') ?>';
    //     } catch (e) {
    //         console.error(e);
    //          alert('Checkout gagal — lihat console untuk detail');
    //     }
    // }
  }
}

</script>

    <script>
        document.addEventListener('livewire:initialized', () => {

            const posComponent = Livewire.find('{{ $this->getId() }}');

            // Memuat data dari localStorage saat halaman dimuat
            posComponent.on('loadCart', () => {
                const storedCart = localStorage.getItem('pos_cart');
                if (storedCart) {
                    const parsedCart = JSON.parse(storedCart);
                    posComponent.call('saveCart', parsedCart );
                    localStorage.removeItem('pos_cart');
                }

            });

        });
    </script>

<style>
input[type="number"] {
  text-align:center;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  margin: 0;
}    
</style>
</div>
