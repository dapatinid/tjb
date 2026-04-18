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

{{-- Scanner OCR --}}
@if($isadmin == 1)
<div class="mb-4 max-w-sm mx-auto" x-data="ocrScanner()">

    {{-- Tombol buka scanner --}}
    <button @click="toggleScanner()"
            :class="scanning ? 'bg-red-500 hover:bg-red-600' : 'bg-indigo-500 hover:bg-indigo-600'"
            class="w-full flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
        </svg>
        <span x-text="scanning ? 'Tutup Kamera' : 'Scan Invoice'"></span>
    </button>

    {{-- Area kamera --}}
    <div x-show="scanning" x-transition class="relative rounded-xl overflow-hidden bg-black shadow-lg">
        <video x-ref="video" autoplay playsinline muted
               class="w-full rounded-xl" style="max-height: 280px; object-fit: cover;"></video>
        <canvas x-ref="canvas" class="hidden"></canvas>

        {{-- Overlay garis scan --}}
        <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
            <div class="w-4/5 h-0.5 bg-red-400 opacity-70 animate-pulse"></div>
            <div class="mt-2 text-white text-xs opacity-70 bg-black/40 px-2 py-1 rounded">
                Arahkan ke kode transaksi di invoice
            </div>
        </div>

        {{-- Status OCR --}}
        <div class="absolute bottom-2 left-2 right-2">
            <div x-show="ocrStatus"
                 :class="ocrFound ? 'bg-green-500' : 'bg-black/60'"
                 class="text-white text-xs px-3 py-1.5 rounded-lg text-center transition"
                 x-text="ocrStatus">
            </div>
        </div>
    </div>

    {{-- Hasil deteksi --}}
    <div x-show="detectedCode" x-transition
         class="mt-2 flex items-center gap-2 px-3 py-2 bg-green-50 dark:bg-green-900/30
                border border-green-300 dark:border-green-700 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-green-600 shrink-0" fill="none"
             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
        </svg>
        <span class="text-sm text-green-700 dark:text-green-300">
            Terdeteksi: <strong x-text="detectedCode"></strong>
        </span>
        <button @click="clearResult()"
                class="ml-auto text-green-500 hover:text-red-500 transition text-xs">✕</button>
    </div>
</div>

<script>
// Load Tesseract.js sekali saja
if (!window.TesseractLoaded) {
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js';
    s.onload = () => { window.TesseractLoaded = true; };
    document.head.appendChild(s);
}

function ocrScanner() {
    return {
        scanning:      false,
        ocrStatus:     '',
        ocrFound:      false,
        detectedCode:  '',
        scanInterval:  null,
        worker:        null,
        stream:        null,

        // ✅ Sesuaikan pola ini dengan format code_tr Anda
        // Contoh: TRX-20240419-001, INV/2024/001, dll
        codePattern: /ORD\d{14}-\d+-\d+/g,

        async toggleScanner() {
            if (this.scanning) {
                this.stopScanner();
            } else {
                await this.startScanner();
            }
        },

        async startScanner() {
            try {
                // ✅ Coba kamera belakang dulu (HP), fallback ke kamera depan (laptop)
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: { ideal: 'environment' }, width: 1280, height: 720 }
                    });
                } catch {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: true  // fallback: pakai kamera apapun yang tersedia
                    });
                }

                this.$refs.video.srcObject = this.stream;
                this.scanning  = true;
                this.ocrStatus = 'Memulai OCR...';
                this.ocrFound  = false;

                this.worker = await Tesseract.createWorker('eng', 1, {
                    tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-',
                });

                this.scanInterval = setInterval(() => this.doOCR(), 1500);

            } catch (err) {
                this.ocrStatus = 'Kamera tidak dapat diakses';
                console.error(err);
            }
        },

        async doOCR() {
            if (!this.scanning || !this.worker) return;

            const video  = this.$refs.video;
            const canvas = this.$refs.canvas;

            if (video.readyState < 2) return;

            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');

            // ✅ Crop bagian tengah saja (lebih cepat + akurat)
            const cropW = canvas.width  * 0.8;
            const cropH = canvas.height * 0.3;
            const cropX = (canvas.width  - cropW) / 2;
            const cropY = (canvas.height - cropH) / 2;

            ctx.drawImage(video, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);
            canvas.width  = cropW;
            canvas.height = cropH;

            // Tingkatkan kontras untuk OCR lebih akurat
            ctx.filter = 'contrast(1.5) grayscale(1)';
            ctx.drawImage(canvas, 0, 0);

            this.ocrStatus = 'Membaca...';

            try {
                const { data: { text } } = await this.worker.recognize(canvas);
                const cleaned = text.toUpperCase().replace(/\s+/g, ' ').trim();

                const matches = cleaned.match(this.codePattern);

                if (matches && matches.length > 0) {
                    const found = matches[0];
                    this.detectedCode = found;
                    this.ocrStatus    = `✓ Ditemukan: ${found}`;
                    this.ocrFound     = true;

                    // ✅ Auto-isi kolom search Livewire
                    @this.set('search', found);

                    // Berhenti scan setelah ketemu
                    this.stopScanner();
                } else {
                    this.ocrStatus = 'Mencari kode transaksi...';
                    this.ocrFound  = false;
                }
            } catch (e) {
                this.ocrStatus = 'Gagal membaca, coba lagi...';
            }
        },

        stopScanner() {
            clearInterval(this.scanInterval);
            this.scanInterval = null;

            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
                this.stream = null;
            }
            if (this.worker) {
                this.worker.terminate();
                this.worker = null;
            }

            this.scanning  = false;
            this.ocrStatus = '';
        },

        clearResult() {
            this.detectedCode = '';
            this.ocrFound     = false;
            @this.set('search', '');
        },
    }
}
</script>
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