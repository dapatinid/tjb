<!-- resources/views/products/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List & Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function cartStore() {
            return {
                cart: JSON.parse(localStorage.getItem('cart') || '[]'),

                saveCart() {
                    localStorage.setItem('cart', JSON.stringify(this.cart));
                },

                addToCart(product) {
                    let item = this.cart.find(i => i.id === product.id);
                    if (item) {
                        item.qty++;
                    } else {
                        this.cart.push({...product, qty: 1});
                    }
                    this.saveCart();
                },

                updateQty(index, qty) {
                    this.cart[index].qty = qty;
                    this.saveCart();
                },

                updatePrice(index, price) {
                    this.cart[index].price = price;
                    this.saveCart();
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },

                get total() {
                    return this.cart.reduce((sum, i) => sum + (i.qty * i.price), 0);
                }
            }
        }
    </script>
</head>
<body class="p-6">

    <h1 class="text-2xl mb-4">Product List</h1>

    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
        <input type="text" name="search" placeholder="Cari produk..."
               value="{{ request('search') }}"
               class="border px-2 py-1 rounded">
        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Cari</button>
    </form>

    <div class="grid grid-cols-3 gap-4" x-data="cartStore()">
        @foreach($products as $product)
            <div class="border p-4 rounded shadow">
                <h2 class="font-bold">{{ $product->name }}</h2>
                <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <button @click="addToCart({id: {{ $product->id }}, name: '{{ $product->name }}', price: {{ $product->price }}})"
                        class="bg-green-500 text-white px-3 py-1 mt-2 rounded">
                    Tambah ke Cart
                </button>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>

    <hr class="my-6">

    <div x-data="cartStore()" class="mt-6">
        <h2 class="text-xl mb-4">Cart</h2>
        <template x-if="cart.length === 0">
            <p>Cart kosong.</p>
        </template>
        <table class="border-collapse w-full" x-show="cart.length > 0">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Produk</th>
                    <th class="border p-2">Qty</th>
                    <th class="border p-2">Harga</th>
                    <th class="border p-2">Subtotal</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(item, index) in cart" :key="item.id">
                    <tr>
                        <td class="border p-2" x-text="item.name"></td>
                        <td class="border p-2">
                            <input type="number" min="1" class="border w-16 text-center"
                                   x-model="item.qty"
                                   @change="updateQty(index, item.qty)">
                        </td>
                        <td class="border p-2">
                            <input type="number" class="border w-24 text-center"
                                   x-model="item.price"
                                   @change="updatePrice(index, item.price)">
                        </td>
                        <td class="border p-2" x-text="item.qty * item.price"></td>
                        <td class="border p-2">
                            <button @click="removeItem(index)" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="text-right mt-4 font-bold">
            Total: Rp <span x-text="total"></span>
        </div>
    </div>

</body>
</html>
