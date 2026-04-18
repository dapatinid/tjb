<div class="w-full max-w-3xl py-10 px-4 mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="/items-status" wire:navigate
           class="p-2 rounded-full bg-white dark:bg-neutral-800 shadow hover:shadow-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 dark:text-white" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-600 dark:text-white">Item Ledger</h1>
    </div>

    {{-- Filter Tanggal --}}
    <div class="flex flex-wrap gap-3 mb-6 items-center">
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">From</span>
            <input type="date" wire:model="date_awal"
                   class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-neutral-600
                          bg-white dark:bg-neutral-800 text-sm dark:text-white
                          focus:ring-2 focus:ring-amber-400 focus:outline-none">
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">To</span>
            <input type="date" wire:model="date_akhir"
                   class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-neutral-600
                          bg-white dark:bg-neutral-800 text-sm dark:text-white
                          focus:ring-2 focus:ring-amber-400 focus:outline-none">
        </div>
    </div>

    {{-- Search Dropdown --}}
    <div class="relative mb-8">
        <div class="relative flex items-center gap-2 px-4 py-3 rounded-xl bg-white dark:bg-neutral-800
                    border border-gray-200 dark:border-neutral-600 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-400 shrink-0" fill="none"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="{{ $product ? $product->name . ' ' . $product->variant : 'Cari nama produk...' }}"
                   class="flex-1 bg-transparent text-sm dark:text-white focus:outline-none border-0 focus:border-0 focus:ring-0 
                          placeholder:text-gray-400">
            @if($product)
                <button wire:click="$set('product_id', '')"
                        class="absolute right-8 text-gray-400 hover:text-red-500 transition shrink-0"
                        title="Hapus pilihan">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Dropdown hasil pencarian --}}
        @if($searchResults->count())
        <div class="absolute z-20 w-full mt-1 bg-white dark:bg-neutral-800 border
                    border-gray-200 dark:border-neutral-600 rounded-xl shadow-lg overflow-hidden">
            @foreach($searchResults as $p)
            <button wire:click="selectProduct({{ $p->id }})"
                    class="w-full flex items-center gap-3 px-4 py-3 text-left
                           hover:bg-amber-50 dark:hover:bg-neutral-700 transition
                           border-b border-gray-100 dark:border-neutral-700 last:border-0">
                <img src="{{ $p->images ? Str::replace('%2F','/',url('storage',$p->images[0])) : url('storage/food-packaging.png') }}"
                     onerror="this.src='{{ url('storage/food-packaging.png') }}'"
                     class="w-9 h-9 rounded-lg object-cover shrink-0">
                <div>
                    <div class="text-sm font-medium dark:text-white">
                        {{ $p->name }} {{ $p->variant }}
                    </div>
                    <div class="text-xs text-gray-400">ID #{{ $p->id }}</div>
                </div>
            </button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Card Produk --}}
    @if($product && $summary)

    {{-- Info Produk --}}
    <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-md p-5 mb-4
                border border-gray-100 dark:border-neutral-700">
        <div class="flex items-center gap-4">
            <img src="{{ $product->images ? Str::replace('%2F','/',url('storage',$product->images[0])) : url('storage/food-packaging.png') }}"
                 onerror="this.src='{{ url('storage/food-packaging.png') }}'"
                 class="w-20 h-20 rounded-xl object-cover shadow">
            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-bold dark:text-white truncate">
                    {{ $product->name }} {{ $product->variant }}
                </h2>
                <div class="flex flex-wrap gap-2 mt-1">
                    <span class="text-xs text-gray-400 dark:text-gray-500">ID #{{ $product->id }}</span>
                    <span class="text-xs text-gray-400 dark:text-gray-500">· {{ $product->unit_name }}</span>
                </div>
                {{-- Status stok --}}
                @php $isLow = $summary['stok'] < ($product->low_alert ?? 0); @endphp
                <div class="mt-2">
                    @if($isLow)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold
                                     bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400
                                     px-2.5 py-1 rounded-full">
                            ⚠ LOW STOCK
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold
                                     bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400
                                     px-2.5 py-1 rounded-full">
                            ✓ Stok Aman
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Statistik --}}
    @php
        $stats = [
            ['label' => 'Beli',      'value' => $summary['beli'],        'color' => 'blue',   'icon' => '📥'],
            ['label' => 'Jual',      'value' => $summary['jual'] * -1,   'color' => 'orange', 'icon' => '🛒'],
            ['label' => 'Prod',      'value' => $summary['prod'],        'color' => 'purple', 'icon' => '⚙️'],
            ['label' => 'Adj',       'value' => $summary['adj'],         'color' => 'yellow', 'icon' => '✏️'],
            ['label' => 'Tf Out',    'value' => $summary['tf_out'] * -1, 'color' => 'red',    'icon' => '📤'],
            ['label' => 'Tf In',     'value' => $summary['tf_in'],       'color' => 'teal',   'icon' => '📦'],
        ];
        $colorMap = [
            'blue'   => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400',
            'orange' => 'bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400',
            'purple' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400',
            'yellow' => 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400',
            'red'    => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400',
            'teal'   => 'bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400',
        ];
    @endphp

    <div class="grid grid-cols-3 gap-3 mb-4">
        @foreach($stats as $stat)
        <div class="rounded-2xl p-4 {{ $colorMap[$stat['color']] }} border border-transparent
                    dark:border-neutral-700">
            <div class="text-xl mb-1">{{ $stat['icon'] }}</div>
            <div class="text-xs font-medium opacity-70 mb-0.5">{{ $stat['label'] }}</div>
            <div class="text-xl font-bold">{{ number_format($stat['value'], 0, ',', '.') }}</div>
        </div>
        @endforeach
    </div>

    {{-- Stok Akhir -- card besar --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="rounded-2xl p-5 bg-slate-800 dark:bg-white text-white dark:text-slate-800 shadow-md">
            <div class="text-xs font-medium opacity-60 mb-1 uppercase tracking-wide">Stok</div>
            <div class="text-4xl font-black">{{ number_format($summary['stok'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-2xl p-5 bg-white dark:bg-neutral-800 border border-gray-200
                    dark:border-neutral-700 shadow-sm">
            <div class="text-xs font-medium text-gray-400 mb-1 uppercase tracking-wide">St. Gudang</div>
            <div class="text-4xl font-black text-slate-700 dark:text-white">
                {{ number_format($summary['stok_gudang'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    @else
    {{-- Empty state --}}
    <div class="text-center py-20 text-gray-400 dark:text-neutral-500">
        <div class="text-5xl mb-4">🔍</div>
        <p class="text-sm">Ketik nama produk untuk mulai</p>
    </div>
    @endif

</div>