<div>

  <span id="modalProd" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal" data-hs-overlay="#hs-focus-management-modal"
    class="modalProdukSaya absolute top-0 -z-50"> modalProduk </span>

<div wire:ignore.self id="hs-focus-management-modal" class="[--body-scroll:true] [--overlay-backdrop:false] hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto" role="dialog" tabindex="-1" aria-labelledby="hs-focus-management-modal-label">
    <div class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
      {{-- <form > --}}
        <div class="flex flex-col border shadow-sm pointer-events-auto bg-gray-50 rounded-xl">
          <div class="flex items-center justify-between px-4 py-3 border-b">
            <h3 id="hs-focus-management-modal-label" class="font-bold text-gray-800">
              {{ $modalNamaProduk }} {{ $modalVariantProduk == null ? '' : '-'.' '.$modalVariantProduk }}
            </h3>
            <div class="flex justify-end">

                <a wire:navigate href="/products/{{ $modalSlugProduk }}" class="inline-flex items-center justify-center mr-2 text-gray-800 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200">
                    
                        <x-fas-location-arrow class="mx-auto text-blue-500 size-4"/>
                    
                </a>
                <button type="button" wire:click='resetEditModal' id="hs-focus-management-modal-close" class="inline-flex items-center justify-center text-red-400 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled" aria-label="Close" data-hs-overlay="#hs-focus-management-modal">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            </div>
          <div class="p-4 overflow-y-auto">
            <div class="flex justify-between flex-nowrap">
                <label class="block mb-2 text-sm font-medium">Quantity</label>
                
                <label class="block mb-2 text-sm font-medium text-blue-400">@ @currency($productcek->where('id',$modalIdProduk)->value('price'))</label>
            </div>
            <div class="grid grid-cols-2">
                <input 
                type="number" id="thisqty" name="thisqty" wire:model='thisqty' 
                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" 
                class="block w-full px-4 py-3 mr-2 text-sm text-center border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400" 
                @if ($cartcek->where('product_id', $modalIdProduk)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                wire:keyup.enter='addToCart({{ $modalIdProduk == null || $modalIdProduk == '' ? 0 : $modalIdProduk }}); soundBeep.play(); modalClose();'
                placeholder="{{ $cartcek->where('product_id', $modalIdProduk)->where('created_by', auth()->user()->id)->value('quantity') }}" 
                @else
                wire:keyup.enter='addToCart({{ $modalIdProduk == null || $modalIdProduk == '' ? 0 : $modalIdProduk }}); setTimeout(scrollBottom, 5000); soundBeep.play(); modalClose();'
                placeholder="inputkan qty" 
                @endif 
                autofocus="">
                <input type="alfanumeric" id="thisprice" name="thisprice" wire:model='thisprice' x-mask:dynamic="$money($input, ',', '.')"
                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" 
                class="block w-full px-4 py-3 ml-2 text-sm text-center bg-white border border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400" 
                @if ($cartcek->where('product_id', $modalIdProduk)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                wire:keyup.enter='addToCart({{ $modalIdProduk == null || $modalIdProduk == '' ? 0 : $modalIdProduk }}); soundBeep.play(); modalClose();'
                placeholder="ubah harga" 
                @else
                wire:keyup.enter='addToCart({{ $modalIdProduk == null || $modalIdProduk == '' ? 0 : $modalIdProduk }}); setTimeout(scrollBottom, 5000); soundBeep.play(); modalClose();'
                placeholder="inputkan harga" 
                @endif 
                >
            </div>
          </div>
          <div class="flex items-center px-4 py-3 border-t gap-x-2 {{ $cartcek->where('product_id', $modalIdProduk)->where('created_by', auth()->user()->id)->value('quantity') > 0 ? 'justify-between' : 'justify-end' }}">
            @if ($cartcek->where('product_id', $modalIdProduk)->where('created_by', auth()->user()->id)->value('quantity') > 0)
            <button type="button" wire:click='removeItem({{ $modalIdProduk }}); modalClose();'
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-red-400 rounded-lg gap-x-2 hover:bg-gray-400 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled" data-hs-overlay="#hs-focus-management-modal">
                <x-fas-trash class="w-5 h-5 mx-auto text-white"/>
            </button>
            @endif
            <div>
            <button type="button" wire:click='resetEditModal' class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-300 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled" data-hs-overlay="#hs-focus-management-modal">
              Batal
            </button>
            <button
            id='addToCartButton' 
            @if ($cartcek->where('product_id', $modalIdProduk)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                wire:click='addToCart({{ $modalIdProduk == null || $modalIdProduk == '' ? 0 : $modalIdProduk }}); soundBeep.play();'
            @else
                wire:click='addToCart({{ $modalIdProduk == null || $modalIdProduk == '' ? 0 : $modalIdProduk }}); setTimeout(scrollBottom, 5000); soundBeep.play();'
            @endif
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-lg gap-x-2 hover:bg-green-600 focus:bg-green-600 focus:outline-none disabled:opacity-50 disabled" data-hs-overlay="#hs-focus-management-modal">
                Simpan
            </button> 
            </div>

          </div>
        </div>
      {{-- </form> --}}
    </div>
  </div>


</div>
