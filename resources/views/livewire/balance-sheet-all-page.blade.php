<div class="w-full max-w-[85rem] pt-7 pb-10 px-4 sm:px-6 lg:px-8 mx-auto">

  <!-- JUDUL Start-->
      
  <div class="block mt-2 ">
      <div wire:ignore class="flex justify-between">
      <a class="cursor-pointer flex flex-nowrap items-center dark:text-gray-300 bg-white dark:bg-gray-800 p-1 rounded-full"
        href="/dompet" wire:navigate                   
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>

      </a> 
      <h1 class="md:text-2xl text-sm font-bold dark:text-white tengah-aja">NERACA - Semua Cabang</h1>
      <button type="button" class="cursor-pointer text-red-500 text-sm underline underline-offset-2 font-semibold text-right bg-white dark:bg-gray-800 p-1 rounded-full"
        aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal" data-hs-overlay="#hs-scale-animation-modal">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>

      <div id="hs-scale-animation-modal" class="hs-overlay [--body-scroll:true] hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-scale-animation-modal-label">
        <div class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
          <div class="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
              <h3 id="hs-scale-animation-modal-label" class="font-bold text-gray-800 dark:text-white">
                Cabang
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
              <a wire:navigate href="/neraca-all" class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-200 text-green-800 hover:bg-green-300 focus:outline-hidden focus:bg-green-200 disabled:opacity-50 disabled:pointer-events-none dark:text-green-400 dark:bg-green-800/30 dark:hover:bg-green-800/20 dark:focus:bg-green-800/20">
                Neraca (All)           
              </a>
                  @php
                      $cabang = $branches->get()->where('partner_id', auth()->user()->partner_id);
                  @endphp

                  @foreach ( $cabang as $branch)
              <button type="button" wire:click.prevent='changeBranch({{ $branch->id }})' class="cursor-pointer py-1 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">
                {{ $branch->name }}   
              </button>
                  @endforeach
            </div>
            
          </div>
        </div>
      </div>

    </div>
    <div class="flex justify-center items-center ms-17">
      <span class="text-center dark:text-white text-sm font-normal">Periode s.d </span> 
      <input type="date" wire:model.live="date_akhir" name="date_akhir" id="date_akhir"
      class="border-0 bg-transparent dark:text-white">
    </div>
  </div>

  <!-- JUDUL End -->

<div wire:poll class="grid grid-cols-2 space-x-5">

<div class="">
  <div class="flex justify-between mt-5 mb-3">
    <h3 class="dark:text-white font-bold">Aktiva</h3>
  </div>

  <div class="bg-white rounded-xl space-y-1 divide-y-1 divide-zinc-200 p-4">
    <h3 class="text-center pb-3">Aset Tetap</h3>
    @php
        $sum_tot_Debit_A = 0;
    @endphp
    @foreach ($nr_AktivaAsetTetap as $debit)        
    <div class="pb-2 grid grid-cols-2">
    <div
      class="flex items-center text-sm">
      {{ Str::after($debit,'NR-DB-') }}    
    </div>
    <div 
        class="flex items-center justify-end text-green-60">
        @formatNumber($balance->where('debit', $debit)->sum('nominal') - $balance->where('kredit', $debit)->sum('nominal'))
      </div>
    </div>
    @php
        $sum_tot_Debit_A += $balance->where('debit', $debit)->sum('nominal') - $balance->where('kredit', $debit)->sum('nominal');
    @endphp
    @endforeach
    <h3 class="text-center pt-3">@formatNumber($sum_tot_Debit_A)</h3>
  </div>

  <div class="bg-white rounded-xl space-y-1 divide-y-1 divide-zinc-200 p-4 mt-4">
    <h3 class="text-center pb-3">Aset Lancar</h3>
    @php
        $sum_tot_Debit_B = 0;
    @endphp
    @foreach ($nr_AktivaAsetLancar as $debit)        
    <div class="pb-2 grid grid-cols-2">
    <div
      class="flex items-center text-sm">
      {{ Str::after($debit,'NR-DB-') }}    
    </div>
    <div 
        class="flex items-center justify-end text-green-60">
        @formatNumber($balance->where('debit', $debit)->sum('nominal') - $balance->where('kredit', $debit)->sum('nominal'))
      </div>
    </div>
    @php
        $sum_tot_Debit_B += $balance->where('debit', $debit)->sum('nominal') - $balance->where('kredit', $debit)->sum('nominal');
    @endphp
    @endforeach
    <h3 class="text-center pt-3">@formatNumber($sum_tot_Debit_B)</h3>
  </div>
  <div class="flex justify-end mt-2 me-4 text-xl font-bold">
    <h3 class="dark:text-white">@formatNumber($sum_tot_Debit_A + $sum_tot_Debit_B)</h3>
  </div>
  </div>

  {{-- Atas Aktiva Bawah Pasiva --}}

  <div class="">
  <div class="flex justify-between mt-5 mb-3">
    <h3 class="dark:text-white font-bold">Pasiva</h3>
  </div>

  <div class="bg-white rounded-xl space-y-1 divide-y-1 divide-zinc-200 p-4">
    <h3 class="text-center pb-3">Kewajiban</h3>
    @php
        $sum_tot_KreditC = 0;
    @endphp    
    @foreach ($nr_PasivaKewajiban as $kredit)        
    <div class="pb-2 grid grid-cols-2">
    <div
      class="flex items-center text-sm">
        {{ Str::after($kredit,'NR-KR-') }}      
    </div>
      <div 
        class="flex items-center justify-end text-green-60">
        @formatNumber($balance->where('kredit', $kredit)->sum('nominal') - $balance->where('debit', $kredit)->sum('nominal'))
      </div>
    </div>
    @php
        $sum_tot_KreditC += $balance->where('kredit', $kredit)->sum('nominal') - $balance->where('debit', $kredit)->sum('nominal');
    @endphp 
    @endforeach
    <h3 class="text-center pt-3">@formatNumber($sum_tot_KreditC)</h3>
  </div>

  <div class="bg-white rounded-xl space-y-1 divide-y-1 divide-zinc-200 p-4 mt-4">
    <h3 class="text-center pb-3">Ekuitas</h3>
    @php
        $sum_tot_KreditD = 0;
    @endphp    
    @foreach ($nr_PasivaEkuitas as $kredit)        
    <div class="pb-2 grid grid-cols-2">
    <div
      class="flex items-center text-sm">
        {{ Str::after($kredit,'NR-KR-') }}      
    </div>
      <div 
        class="flex items-center justify-end text-green-60">
        @formatNumber($balance->where('kredit', $kredit)->sum('nominal') - $balance->where('debit', $kredit)->sum('nominal'))
      </div>
    </div>
    @php
        $sum_tot_KreditD += $balance->where('kredit', $kredit)->sum('nominal') - $balance->where('debit', $kredit)->sum('nominal');
    @endphp 
    @endforeach
    <div class="pb-2 grid grid-cols-2">
    <div
      class="flex items-center text-sm">
        D-6000 Laba Rugi Berjalan     
    </div>
      <div 
        class="flex items-center justify-end text-green-60">
        @formatNumber($pl_kredit_total - $pl_debit_total)
      </div>
    </div>
    <h3 class="text-center pt-3">@formatNumber($sum_tot_KreditD + $pl_kredit_total - $pl_debit_total)</h3>
  </div>
  <div class="flex justify-end mt-2 me-4 text-xl font-bold">
    <h3 class="dark:text-white">@formatNumber($sum_tot_KreditC + $sum_tot_KreditD + $pl_kredit_total - $pl_debit_total)</h3>
  </div>
  </div>

  </div>



</div>
  