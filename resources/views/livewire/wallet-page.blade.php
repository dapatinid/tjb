<div wire:poll.55s class="w-full max-w-[85rem] pt-7 pb-10 px-4 sm:px-6 lg:px-8 mx-auto">

{{-- {{ $paymentByDate }} --}}

  <!-- CARD CASH BANK Start-->
      
  <div class="max-w-xs object-cover aspect-video mx-auto relative bg-white border border-gray-200 shadow-2xs rounded-2xl dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
    <img class="rounded-2xl" src="{{ str_contains($this->rek, 'BANK') ? url('storage/card.png') : url('storage/card-cash.png') }}" alt="Card Image">
    <div class="absolute top-0 end-0">
        <h6 class="mr-5 mt-6 text-white">
          {{ str_contains($this->rek, 'BANK') ? 'BANK' : 'TUNAI' }}
        </h6>
    </div>
    <div class="absolute top-0 start-0 end-0">
      <div class="p-5">
        <h3 class="mt-1 text-white text-2xl font-bold">
          {{ $this->rek }}
        </h3>
        <p class="text-end mt-7 text-white text-2xl">
          Rp @formatNumber($cashBankTotal)
        </p>
        <p class="mt-12 text-xs text-white">
          {{ $cashBankHistoriesLast ? $cashBankHistoriesLast->diffForHumans() : "..." }}
        </p>
      </div>
    </div>
  </div>

  <!-- CARD CASH BANK End -->

  <div wire:ignore class="flex justify-between mt-5 mb-3">
    
    <x-filament::modal>
        <x-slot name="trigger">
            <h3 class="dark:text-white font-bold">Riwayat Terakhir &#128438;</h3>
        </x-slot>
            Export Laporan
            <x-filament::input
                type="date"
                wire:model="laporan_dompet_by_date"
            />
            <x-filament::button wire:click="exportDompet" class="cursor-pointer bg-amber-300 hover:bg-amber-500" icon="heroicon-m-printer">
                Export
            </x-filament::button>
    </x-filament::modal>
    <div class="flex flex-nowrap">
      <button type="button" class="scale-90 me-3 px-2 inline-flex items-center text-xs font-medium rounded-lg border-0 bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" 
      aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal" data-hs-overlay="#hs-scale-animation-modal">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
        </svg>
      </button>

      <div id="hs-scale-animation-modal" class="hs-overlay [--body-scroll:true] hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-scale-animation-modal-label">
        <div class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
          <div class="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
              <h3 id="hs-scale-animation-modal-label" class="font-bold text-gray-800 dark:text-white">
                Transaksi
              </h3>
              <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-scale-animation-modal">
                <span class="sr-only">Close</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 6 6 18"></path>
                  <path d="m6 6 12 12"></path>
                </svg>
              </button>
            </div>
            <div class="p-4 overflow-y-auto gap-3 flex flex-wrap">
              <a href="/admin/journals/create" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                +Jurnal
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>              
              </a>
              <a href="/admin/orders/create" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                +Penjualan
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>                
              </a>
              <a href="/admin/porders/create" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                +Pembelian
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
              </a>
              <a href="/admin/productions/create" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                +Produksi
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                </svg>                
              </a>
              <a href="/admin/adj-items/create" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                +Adj Stok
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>                
              </a>
              <a href="/admin/tr-stk-outs/create" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                +Tf Out
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                </svg>
              </a>
              <a href="/admin/tr-stk-ins" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                +TF In
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
              </a>
              <a href="/admin/payments" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 focus:outline-hidden focus:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:text-red-400 dark:bg-red-800/30 dark:hover:bg-red-800/20 dark:focus:bg-red-800/20">
                Buku Besar
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>  
              </a>
              <a href="/laba-rugi" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-amber-100 text-amber-800 hover:bg-amber-200 focus:outline-hidden focus:bg-amber-200 disabled:opacity-50 disabled:pointer-events-none dark:text-amber-400 dark:bg-amber-800/30 dark:hover:bg-amber-800/20 dark:focus:bg-amber-800/20">
                Laba Rugi
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
              </a>
              <a href="/neraca" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-200 text-green-800 hover:bg-green-300 focus:outline-hidden focus:bg-green-200 disabled:opacity-50 disabled:pointer-events-none dark:text-green-400 dark:bg-green-800/30 dark:hover:bg-green-800/20 dark:focus:bg-green-800/20">
                Neraca
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                </svg>
              </a>
            </div>
            
          </div>
        </div>
      </div>

      <select name="rek" id="rek" wire:model.live="rek"
      class="py-1 px-2 pe-9 block w-40 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">                      
      <option value="KAS UTAMA">KAS UTAMA</option>  
      <option value="KAS KASIR">KAS KASIR</option>  
      <option value="KAS KECIL">KAS KECIL</option>  
      <option value="BANK BCA">BANK BCA</option>
      <option value="BANK BRI">BANK BRI</option>
      </select>
    </div>
  </div>

  <div class="">

    @foreach ($paymentByDate as $paymenttgl)

    @php
    $tglNya = $cashBankHistories->whereBetween('date_payment', [$paymenttgl->date . ' 00:00:00', $paymenttgl->date . ' 23:59:59'])       
    @endphp
    <span class="dark:text-zinc-300">{{ $tglNya->first()->date_payment->translatedFormat('l') }}, {{ $tglNya->first()->date_payment->translatedFormat('d') }} {{ $tglNya->first()->date_payment->translatedFormat('M') }}</span>
    
    <div class="bg-white rounded-xl space-y-1 divide-y-1 divide-zinc-200 p-4 mb-4 mt-1">

        @foreach ($tglNya as $transaksi)        
        <div class="pb-2 grid grid-cols-3">
          <div class="flex items-center">
            <div class="text-sm line-clamp-2 pe-2">
              {{ $transaksi->user->name }}
            </div>
          </div>
          <a       
            @php
                if ($transaksi->mutation_type === 'Sales') {
                    $urlTRS = '/admin/orders/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Piutang Penjualan') {
                    $urlTRS = '/admin/orders/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Terjual') {
                    $urlTRS = '/admin/orders/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Purchase') {
                    $urlTRS = '/admin/porders/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Hutang Pembelian') {
                    $urlTRS = '/admin/porders/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Terbeli') {
                    $urlTRS = '/admin/porders/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Stok Bertambah') {
                    $urlTRS = '/admin/adj-items/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Stok Berkurang') {
                    $urlTRS = '/admin/adj-items/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Produksi Berkembang') {
                    $urlTRS = '/admin/productions/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Produksi Menyusut') {
                    $urlTRS = '/admin/productions/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Transfer Keluar') {
                    $urlTRS = '/admin/tr-stk-outs/' . $transaksi->paymentable_id .'/edit';
                } elseif ($transaksi->mutation_type === 'Barang Transfer Masuk') {
                    $urlTRS = '/admin/tr-stk-ins/' . $transaksi->paymentable_id .'/edit';
                } else {
                    $urlTRS = '/admin/journals/' . $transaksi->paymentable_id .'/edit';
                }            
            @endphp
            href= "{{ $urlTRS }}"
          class="flex items-center text-sm">
            {{ $transaksi->paymentable_type === 'App\Models\Journal' ? $journal->where('id',$transaksi->paymentable_id)->value('notes')  :  $transaksi->mutation_type }}            
          </a>
          <div>
          <div 
            class="flex items-center justify-end {{ $transaksi->debit === "NR-DB-B-1100 CASH / BANK" ? 'text-green-600' : 'text-red-500' }}">
            @if ($transaksi->debit === "NR-DB-B-1100 CASH / BANK" )
                +
            @else
                -
            @endif
            @formatNumber($transaksi->nominal)
          </div>
            <div class="flex items-center justify-end text-xs text-gray-500">
              {{ $transaksi->date_payment->format('H:i') }}
            </div>
          </div>

        </div>
        @endforeach
        </div>
    @endforeach

  </div>
                      <!-- pagination start -->
                    {{-- <style>
                        nav div div p {
                            margin-left: 20px;
                            margin-right: 20px;
                        }
                    </style> --}}
                    <div 
                    class="mt-3"
                    >
                        {{ $paymentByDate->links() }}
                    </div>
                    <!-- pagination end -->

</div>
  