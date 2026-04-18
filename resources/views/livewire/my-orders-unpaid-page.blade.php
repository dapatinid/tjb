<div class="w-full max-w-[85rem] py-10 px-4 mx-auto">

    {{-- Judul --}}
    <h1 class="text-2xl font-bold text-center text-slate-500">
        @if ($isadmin == 1)
            Orders | Unpaid <br>
            <span class="text-lg">
                Rp {{ number_format($my_orders_sum_unpaid, 0, ',', '.') }} |
                {{ number_format($my_orders_sum_unpaid_count, 0, ',', '.') }} Transaksi
            </span>
        @else
            My Orders: Rp {{ number_format($my_orders_sum, 0, ',', '.') }}
        @endif
    </h1>

    {{-- Accordion Mode Pelunasan --}}
    @if($isadmin == 1)
    <div class="mt-6 max-w-lg mx-auto border border-gray-200 dark:border-neutral-600 rounded-xl overflow-hidden shadow-sm">
        <button wire:click="$toggle('modePelunasan')"
                class="w-full flex items-center justify-between px-4 py-3
                       {{ $modePelunasan ? 'bg-green-500 text-white' : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-white' }}
                       font-semibold text-sm transition-colors">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Mode Pelunasan {{ $modePelunasan ? '(Aktif)' : '' }}
            </span>
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="size-4 transition-transform {{ $modePelunasan ? 'rotate-180' : '' }}"
                 fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
            </svg>
        </button>

        @if($modePelunasan)
        <div class="px-4 py-4 bg-green-50 dark:bg-neutral-700 space-y-3">
            @error('pelunasan')
                <div class="text-sm text-red-500">{{ $message }}</div>
            @enderror

            {{-- Tanggal Bayar --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">
                    Tanggal Pembayaran
                </label>
                <input type="datetime-local" wire:model="tanggalBayar"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200
                              dark:border-neutral-500 bg-white dark:bg-neutral-800
                              dark:text-white focus:ring-2 focus:ring-green-400 focus:outline-none">
            </div>

            {{-- Metode --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">
                    Metode Pembayaran
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer text-sm
                                  {{ $metodeBayar === 'cash' ? 'border-green-500 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'border-gray-200 dark:border-neutral-500 bg-white dark:bg-neutral-800 dark:text-white' }}">
                        <input type="radio" wire:model.live="metodeBayar" value="cash" class="hidden">
                        Cash
                    </label>
                    <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer text-sm
                                  {{ $metodeBayar === 'transfer' ? 'border-green-500 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'border-gray-200 dark:border-neutral-500 bg-white dark:bg-neutral-800 dark:text-white' }}">
                        <input type="radio" wire:model.live="metodeBayar" value="transfer" class="hidden">
                        Transfer
                    </label>
                </div>
            </div>

            {{-- Rekening --}}
            <div x-data>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">
                    Rekening
                </label>
                <select wire:model="rekeningBayar"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200
                            dark:border-neutral-500 bg-white dark:bg-neutral-800
                            dark:text-white focus:ring-2 focus:ring-green-400 focus:outline-none">
                    {{-- Cash --}}
                    @if($metodeBayar === 'cash')
                        <option value="KAS UTAMA">KAS UTAMA</option>
                        <option value="KAS KASIR">KAS KASIR</option>
                    {{-- Transfer --}}
                    @else
                        <option value="BANK BCA">BANK BCA</option>
                        <option value="BANK BRI">BANK BRI</option>
                    @endif
                </select>
            </div>

            <p class="text-xs text-gray-500 dark:text-gray-400">
                Tekan tombol ✓ di kartu order untuk melunasi transaksi tersebut.
            </p>
        </div>
        @endif
    </div>
    @endif

    {{-- Search --}}
    <div class="mt-4 mb-6 max-w-sm mx-auto">
        <div class="flex items-center gap-2 px-3 py-2 bg-white dark:bg-neutral-800 border
                    border-gray-200 dark:border-neutral-600 rounded-lg shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-400 shrink-0" fill="none"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input wire:model.live.debounce.400ms="search"
                   type="text"
                   placeholder="Cari kode transaksi atau nama customer..."
                   class="flex-1 bg-transparent text-sm dark:text-white focus:outline-none placeholder:text-gray-400 ring-0 border-0 outline-0">
            @if($search)
                <button wire:click="$set('search', '')" class="text-gray-400 hover:text-red-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- Cards --}}
    <div class="flex flex-wrap mt-2">
        @forelse ($orders as $order)
            @php
                $statusColor = match($order->status) {
                    'new'       => 'bg-blue-500',
                    'processing'=> 'bg-yellow-500',
                    'shipped'   => 'bg-orange-500',
                    'delivered' => 'bg-green-500',
                    'canceled'  => 'bg-red-500',
                    default     => 'bg-gray-500'
                };
                $nama   = $order->address
                    ? ($order->address->first_name . ' ' . $order->address->last_name)
                    : ($order->user->name ?? 'No Name');
                $method = $paymentlast->where('paymentable_id', $order->id)->first()->payment_method ?? '-';
            @endphp

            <div wire:key="{{ $order->id }}" class="w-full p-2 lg:w-1/3 sm:w-1/2">
                <div class="bg-white border rounded-lg shadow-sm p-4 dark:bg-neutral-800">
                    <div class="flex justify-between border-b pb-2 mb-2 dark:text-white">
                        <span class="font-bold text-xs">#{{ $order->code_tr }}</span>
                        <span class="text-sm truncate max-w-50">{{ $order->user->name }}</span>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="px-2 py-0.5 text-xs text-white rounded {{ $statusColor }}">
                                {{ strtoupper($order->status) }}
                            </span>
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                {{ $order->grand_total > $order->total_payment ? 'Kurang: Rp ' . number_format($order->grand_total - $order->total_payment, 0, ',', '.') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ $order->date_order }}</span>
                            <span class="font-bold dark:text-gray-400">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <a href="/my-orders/{{ $order->id }}"
                           class="flex-1 bg-blue-600 text-white text-center py-1 rounded text-sm">
                            Detail
                        </a>
                        @if($isadmin == 1)
                            <button wire:click="changeStatus({{ $order->id }})"
                                    class="flex-1 bg-gray-200 text-gray-800 py-1 rounded text-sm">
                                Ubah Status
                            </button>
                            @if($modePelunasan)
                                <button wire:click="lunas({{ $order->id }})"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                    ✓
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="w-full text-center py-20 text-gray-500 italic">Belum ada pesanan.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>