<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
<body class="bg-gray-100 p-5">

    <div class="w-full mx-auto" x-data="app()">

        <div class="grid grid-cols-2 gap-5">
            <div class="w-full">
                <h2 class="text-3xl font-bold mb-6">Keranjang Belanja</h2>

                <div x-show="cart.length === 0" class="text-center text-gray-500">
                    Keranjang Anda kosong.
                </div>
                
                <div x-show="cart.length > 0">
                    <table class="w-full table-auto bg-white rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="p-4">Produk</th>
                                <th class="p-4">Harga</th>
                                <th class="p-4">Kuantiti</th>
                                <th class="p-4">Subtotal</th>
                                <th class="p-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in cart" :key="item.id">
                                <tr class="border-b">
                                    <td class="p-4" x-text="item.name"></td>
                                    {{-- <td class="p-4" x-text="formatPrice(item.price)"></td> --}}
                                    <td class="p-4">
                                        <input type="number"
                                            x-model.number="item.price"
                                            @input="updateCart()"
                                            min="100"
                                            class="w-20 border p-1 rounded-md">
                                    </td>
                                    <td class="p-4">
                                        <input type="number"
                                            x-model.number="item.quantity"
                                            @input="updateCart()"
                                            min="1"
                                            class="w-20 border p-1 rounded-md">
                                    </td>
                                    <td class="p-4" x-text="formatPrice(item.price * item.quantity)"></td>
                                    <td class="p-4">
                                        <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700">Hapus</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <div class="mt-4 text-right font-bold text-xl">
                        Total: <span x-text="formatPrice(totalPrice)"></span>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <h1 class="text-3xl font-bold mb-6">Daftar Produk</h1>
                
                <div class="flex justify-between items-center mb-4">
                    <input type="text" x-model="search" @input.debounce.500ms="getProducts()" placeholder="Cari produk..." class="border p-2 rounded-md">
                    <div>
                        <button @click="previousPage()" :disabled="!pagination.prev_page_url" class="bg-gray-300 px-4 py-2 rounded-md"><</button>
                        <span class="px-4">Hal <span x-text="pagination.current_page"></span> dari <span x-text="pagination.last_page"></span></span>
                        <button @click="nextPage()" :disabled="!pagination.next_page_url" class="bg-gray-300 px-4 py-2 rounded-md">></button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <template x-for="product in products" :key="product.id">
                        <div @click="addToCart(product)" class="bg-white p-6 rounded-lg shadow-md cursor-pointer hover:bg-amber-100">
                            <h3 class="font-bold text-xl mb-2" x-text="product.name"></h3>
                            <p class="text-gray-600 mb-4" x-text="formatPrice(product.price)"></p>
                            {{-- <button @click="addToCart(product)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                +Cart
                            </button> --}}
                        </div>
                    </template>
                </div>
            </div>
        </div> 

    </div>

    <script>
        function app() {
            return {
                products: [],
                cart: [],
                search: '',
                pagination: {},
                
                init() {
                    // Load cart dari localStorage saat inisialisasi
                    this.cart = JSON.parse(localStorage.getItem('cart')) || [];
                    this.getProducts();
                    
                    // Watch for changes in the cart and save to localStorage
                    this.$watch('cart', (newCart) => {
                        localStorage.setItem('cart', JSON.stringify(newCart));
                    });
                },

                async getProducts(url = '/api/products') {
                    const params = new URLSearchParams({ search: this.search });
                    const response = await fetch(`${url}?${params.toString()}`);
                    const data = await response.json();
                    this.products = data.data;
                    this.pagination = {
                        current_page: data.current_page,
                        last_page: data.last_page,
                        next_page_url: data.next_page_url,
                        prev_page_url: data.prev_page_url,
                    };
                },
                
                nextPage() {
                    if (this.pagination.next_page_url) {
                        this.getProducts(this.pagination.next_page_url);
                    }
                },

                previousPage() {
                    if (this.pagination.prev_page_url) {
                        this.getProducts(this.pagination.prev_page_url);
                    }
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            quantity: 1,
                        });
                    }
                },
                
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                updateCart() {
                    // Alpine automatically handles reactivity, just need to re-save to localStorage
                    localStorage.setItem('cart', JSON.stringify(this.cart));
                },

                formatPrice(price) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(price);
                },

                get totalPrice() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                }
            };
        }
    </script>
</body>
</html>