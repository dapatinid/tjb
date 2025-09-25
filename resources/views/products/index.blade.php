<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List + Cart</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="//cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div x-data="productApp()" x-init="init()">

    <div class="flex flex-wrap">
    <!-- Cart -->
    <div class="sm:w-1/3 w-full bg-white min-h-full">
        <div class="flex justify-between fixed z-50 sm:w-1/3 w-full top-0 py-3 px-3 bg-blue-700 text-white">
            <div class="flex space-x-3 justify-start">
                <span class="font-bold text-lg">Cart </span><span @click="clearCart" class="cursor-pointer text-sm">&#x267B;</span>
            </div>
            <div class="flex space-x-3 justify-end">
                <span class="font-bold" x-text="pos_cart.reduce((t,i)=>t+(i.quantity),0)"></span>
                <span class="font-bold" x-text="formatPrice(pos_cart.reduce((t,i)=>t+(i.quantity*i.price),0))"></span>
            </div>
            

        </div>
        
        <div style="margin-top: 3.5rem;">
            <table class="w-full table-auto bg-white" >
                <template x-if="pos_cart.length === 0">
                    <p class="text-gray-500 text-center my-6">Cart kosong</p>
                </template>
                <template x-for="(item, index) in pos_cart" :key="item.id">
                    <tr class="border-b text-sm">
                        <td class="py-1 px-2">
                            <div class="font-semibold" x-text="item.name"></div>
                            <div class="" x-text="item.variant"></div>
                        </td>
                        <td class="py-1 px-2">
                        <input type="number" min="1" x-model.number="item.quantity" @change="saveCart"
                            class="border w-16 text-center">
                        </td>
                        <td class="py-1 px-2">
                        <input type="number" step="100" x-model.number="item.price" @change="saveCart"
                            class="border w-24 text-center">
                        </td>
                        <td class="py-1 px-2">
                            <span class="flex justify-end" x-text="formatPrice(item.quantity * item.price)"></span>
                        </td>
                        <td class="py-1 px-2">    <button @click="removeFromCart(index)" class="text-red-500">x</button></td>
                    </tr>
                </template>
            </table>
        </div>        
    </div>


    <div class="sm:w-2/3 w-full p-2">

        <!-- Search -->
        <div class="mb-2 flex justify-end">
            <input type="text" placeholder="Cari produk..." x-model="search" @input.debounce.500ms="fetchProducts"
                class="border p-2 rounded w-full">
        </div>

        <!-- Product List -->
        <div class="grid md:grid-cols-3 grid-cols-2 gap-2">
            <template x-for="product in products.data" :key="product.id">
                <div class="bg-white p-4 rounded-md shadow hover:bg-blue-100 cursor-pointer" @click="addToCart(product)">
                    <h2 class="font-bold text-lg" x-text="product.name"></h2>
                    <h2 class="text-sm" x-text="product.variant"></h2>
                    <p class="text-gray-500"><span x-text="formatPrice(product.price)"></span></p>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex flex-wrap justify-center gap-2">
            <template x-for="page in totalPages" :key="page">
                <button @click="goToPage(page)"
                    :class="{'bg-blue-500 text-white': currentPage===page, 'bg-gray-200': currentPage!==page}"
                    class="px-3 py-1 rounded">
                    <span x-text="page"></span>
                </button>
            </template>
        </div>

    </div>
    </div>
</div>

<script>
function productApp() {
    return {
        products: { data: [] },
        search: '',
        currentPage: 1,
        totalPages: 1,
        pos_cart: [],

        init() {
            this.fetchProducts();
            this.loadCart();
        },

        async fetchProducts() {
            let res = await axios.get('/productscart', {
                params: { search: this.search, page: this.currentPage },
                headers: { 'Accept': 'application/json' }
            });
            this.products = res.data;
            this.totalPages = res.data.last_page;
            this.currentPage = res.data.current_page;
        },

        goToPage(page) {
            this.currentPage = page;
            this.fetchProducts();
        },

        addToCart(product) {
            let existing = this.pos_cart.find(i => i.id === product.id);
            if (existing) {
                existing.quantity += 1;
            } else {
                this.pos_cart.push({
                    id: product.id,
                    name: product.name,
                    variant: product.variant,
                    price: product.price,
                    quantity: 1
                });
            }
            this.saveCart();
        },

        removeFromCart(index) {
            this.pos_cart.splice(index, 1);
            this.saveCart();
        },

        clearCart() {
            if (!confirm('Yakin ingin mengosongkan cart?')) return;
            this.pos_cart = [];
            localStorage.removeItem('pos_cart');
        },

        saveCart() {
            localStorage.setItem('pos_cart', JSON.stringify(this.pos_cart));
        },

        loadCart() {
            let data = localStorage.getItem('pos_cart');
            if (data) {
                this.pos_cart = JSON.parse(data);
            }
        },

        formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        },
    }
}
</script>
</body>
</html>
