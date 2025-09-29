<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>POS — Simple</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
</head>
<body class="bg-gray-100">

<script>
    window.initialCart = @json($initialCart ?? []);
</script>

<div class="mx-auto" x-data="posApp()" x-init="init()">
  <div x-data="{ showCart: false }" class="grid md:grid-cols-3 grid-cols-1 md:gap-x-2 gap-y-2 gap-0">

    <!-- Sidebar Cart (mobile: slide-in, desktop: tetap terlihat) -->
    <div 
        class="fixed inset-y-0 left-0 w-full bg-white shadow-lg transform transition-transform duration-300 z-50
               md:static md:translate-x-0 overflow-y-auto overscroll-contain scrollbar-thin scrollbar-thumb-gray-300" style="-webkit-overflow-scrolling: touch;"
        :class="{ '-translate-x-full': !showCart, 'translate-x-0': showCart }">

        <div class="p-4 flex justify-between items-center border-b">
          <h2 class="font-bold text-lg" x-text="`Cart (${(qtybyqty)})`"></h2> 
          <div class="flex justify-end gap-3">
            <a href="/" class="text-blue-500 cursor-pointer" >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="25" height="25">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>            
            <button  type="button" class="text-yellow-500 bg-amber-500 hover:bg-amber-300 cursor-pointer ">
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg> 
            </button>
          </div>       
        </div>

        <!-- isi cart -->
        <div class="p-4 h-[calc(100%)] " >
            <template x-if="cart.length === 0">
                <div class="text-sm text-gray-500 text-center">Cart kosong</div>
            </template>

            <div class="space-y-2 " >
                <template x-for="(item, idx) in cart" :key="item.id">
                <div class="block items-center gap-2 border rounded p-2">
                    <div class="w-full flex justify-between">
                        <div class="flex justify-start gap-2">
                            <div class="font-medium" x-text="item.name"></div>
                            <div class="font-light" x-text="item.variant"></div>
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
                            <input type="number" step="100" class="w-full border rounded px-2" 
                            x-mask:dynamic="(value) => {
                            const numeric = value.replace(/[^0-9]/g, '');
                            if(!numeric) return '';
                            return Number(numeric).toLocaleString('id-ID');
                            }"
                            x-model="item.price_display"
                            @input="updatePrice(item)">
                        </div>
                        <div class="w-1/3 flex justify-center gap-1">
                            <button @click="decrementQty(item)" class="items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                                <input type="number" class="w-12 border rounded px-2 text-center" x-model.number="item.quantity" @input="recalcItem(item)">
                            <button @click="incrementQty(item)" class="items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="w-1/3 flex justify-end" x-text="`Rp ${formatMoney(item.subtotal)}`">
                        </div>
                    </div>
                </div>
                </template>
            </div>

            <div class="mt-4">
                <div class="flex justify-between">
                <div x-text="`Total : ${(totalweight)} kg`"></div>
                <div x-text="`Rp ${formatMoney(total)}`"></div>
                </div>

                <div class="mt-3 flex gap-2">
                {{-- <input x-model="customer_name" placeholder="Nama pelanggan (opsional)" class="border px-3 py-2 rounded w-full"> --}}
                <button @click="clearCart()" class="px-3 py-2 border rounded grow-0 text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
                <button @click="checkout()" class="px-3 py-2 bg-green-600 text-white rounded w-full">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="col-span-2 bg-white">
      <div class="flex items-center gap-2 mb-4 sticky top-0 p-2 border-b" style="background-color: white; z-index: 10;">
        <div class="md:hidden">
            <div x-text="`${(qtybyqty)}`" x-show="qtybyqty > 0" class="absolute px-1 rounded-full -mt-1 -ml-1 text-white bg-green-500 "></div>
            <button @click="showCart = true" class="p-2 bg-blue-600 text-white rounded">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="25" height="25">
                <path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <input x-model.debounce.500ms="q" @input.debounce.500ms="fetchProducts()" placeholder="Cari produk..." class="border px-3 py-2 rounded w-full">
        <select x-model="perPage" @change="fetchProducts()" class="border rounded" 
            style="
                width: 100px;
                padding: 8px 16px 8px 16px;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: url('https://www.svgrepo.com/show/80156/down-arrow.svg');
                background-repeat: no-repeat;
                background-size: 14px 14px;
                background-position: calc(100% - 16px);
            " 
            >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>

      <div class="grid lg:grid-cols-3 grid-cols-2 gap-3 px-2">
        <template x-for="p in products.data" :key="p.id">
          <div class="border rounded p-3">
            <div class="relative -mb-10 flex justify-end">
                <button @click="addToCart(p)" class="bg-blue-600 text-white px-2 py-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="font-semibold mr-11" x-text="p.name"></div>
            <div class="flex justify-start gap-2">
                <span class="text-sm" x-text="`Rp ${formatMoney(p.price)}`"></span>
                <em class="text-sm" x-text="p.variant"></em>
            </div>            
          </div>
        </template>
      </div>

      <!-- pagination -->
      <div class="mt-4 flex items-center justify-between p-4">
        <div x-text="`Total: ${products.total || 0}`"></div>
        <div class="flex gap-2">
          <button :disabled="!products.prev_page_url" @click="fetchProducts(products.prev_page_url)" class="px-3 py-1 border rounded">Prev</button>
          <button :disabled="!products.next_page_url" @click="fetchProducts(products.next_page_url)" class="px-3 py-1 border rounded">Next</button>
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

    init() {
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

    async fetchProducts(url = null) {
      try {
        let endpoint;
        if (url) endpoint = url;
        else endpoint = `/api/products?q=${encodeURIComponent(this.q||'')}&per_page=${this.perPage}`;

        const res = await fetch(endpoint);
        if (!res.ok) throw new Error('Gagal mengambil produk');
        const data = await res.json();

        // map data to expected keys if pagination structure differs
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
    item.price = Number(raw) || 0;
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

    saveCart() {
      localStorage.setItem('pos_cart', JSON.stringify(this.cart));
    },

    get qtybyqty() {
      return this.cart.reduce((sum, it) => sum + (Number(it.quantity)||0), 0).toFixed(0);
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

    async checkout() {
        if (this.cart.length === 0) {
            alert('Cart kosong');
            return;
        }

        this.saveCart();

        const payload = {
            customer_name: this.customer_name || null,
            items: this.cart.map(i => ({ id: i.id, name: i.name, variant: i.variant, weight: i.weight, quantity: i.quantity, price: i.price }))
        };

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const res = await fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Checkout gagal');

        // alert(`Checkout sukses. ID transaksi: ${json.cart_id} — Total: Rp ${this.formatMoney(json.total)}`);
        alert(`Checkout SUKSES. Total items: ${json.totalcount} — Total amount: Rp ${this.formatMoney(json.total)}`);
            this.clearCart();
            window.location.href = '/checkout?branch_id=<?= Auth::user()->branch_id ?>&shipping_method=self_pickup&sales_type=self_pickup&payment_method=cash&rekening=KAS+KASIR&date_order=<?= date('Y') ?>-<?= date('m') ?>-<?= date('d') ?>T<?= date('H') ?>%3A<?= date('i') ?>';
        } catch (e) {
            console.error(e);
             alert('Checkout gagal — lihat console untuk detail');
        }
    }
  }
}
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

</body>
</html>
