<div>
@foreach ($productsAllModal as $product)
  <!-- Start ModalProduk -->
                    <span id="modalProd-{{ $product->id }}" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal-{{ $product->id }}" data-hs-overlay="#hs-focus-management-modal-{{ $product->id }}"
                        class="absolute top-0 -z-50"> modalProduk </span>
                      <div id="hs-focus-management-modal-{{ $product->id }}" class="[--body-scroll:true] [--overlay-backdrop:false] hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto" role="dialog" tabindex="-1" aria-labelledby="hs-focus-management-modal-label">
                        <div class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
                          {{-- <form > --}}
                            <div class="flex flex-col border shadow-sm pointer-events-auto bg-gray-50 rounded-xl">
                              <div class="flex items-center justify-between px-4 py-3 border-b">
                                <h3 class="font-bold text-gray-800">
                                  {{ $product->name }}{{ $product->variant ? " : ".$product->variant : "" }}
                                </h3>
                                <div class="flex justify-end">
                                    <a href="/products/{{ $product->slug }}" class="inline-flex items-center justify-center mr-2 text-gray-800 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200">
                                        <x-fas-location-arrow class="mx-auto text-blue-500 size-4"/>
                                    </a>
                                    <button x-on:click='$wire.thisqty="";$wire.thisprice=""'
                                        type="button" class="inline-flex items-center justify-center text-red-400 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled" aria-label="Close" data-hs-overlay="#hs-focus-management-modal-{{ $product->id }}">
                                        <span class="sr-only">Close</span>
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                </div>
                                <div class="py-3 space-y-1">
                                <div class="px-3 flex justify-between text-sm font-medium">
                                  {{-- <label for="input-label" class="block mb-2 text-sm font-medium">Quantity : <span class="text-blue-500">{{ $cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') }} </span></label> --}}
                                  <span>Qty</span> <span>Harga</span>
                                </div>
                                <div class="px-3 grid grid-cols-2">
                                <input type="number" id="thisqty" name="thisqty" wire:model='thisqty' 
                                    onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" 
                                    class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400" 
                                    @if ($cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                                    wire:keydown.enter='addToCart({{ $product->id }}); soundBeep.play();'
                                    placeholder="{{ $cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') }}" 
                                    @else
                                    wire:keydown.enter='addToCart({{ $product->id }}); setTimeout(scrollBottom, 5000); soundBeep.play();'
                                    placeholder="1" 
                                    @endif 
                                    {{-- @keyup.enter="modalClose({{ $product->id }})" --}}
                                autofocus="">
                                <input type="alfanumeric" id="thisprice" name="thisprice" wire:model='thisprice' x-mask:dynamic="$money($input, ',', '.')"
                                    onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" 
                                    class="block w-full px-4 py-3 ml-2 text-sm text-center bg-white border border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400" 
                                    @if ($cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                                    wire:keydown.enter='addToCart({{ $product->id }}); soundBeep.play();'
                                    placeholder="@formatNumber($cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('unit_amount'))" 
                                    @else
                                    wire:keydown.enter='addToCart({{ $product->id }}); setTimeout(scrollBottom, 5000); soundBeep.play();'
                                    placeholder="@formatNumber($product->price)" 
                                    @endif 
                                    {{-- @keyup.enter="modalClose({{ $product->id }})" --}}
                                >
                              </div>
                              </div>
                              <div class="flex items-center px-4 py-3 border-t gap-x-2 {{ $cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') > 0 ? 'justify-between' : 'justify-end' }}">
                                @if ($cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                                <button type="button" wire:click='removeItem({{ $product->id }})'
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-red-400 rounded-lg gap-x-2 hover:bg-gray-400 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled" data-hs-overlay="#hs-focus-management-modal-{{ $product->id }}">
                                    <x-fas-trash class="w-5 h-5 mx-auto text-white"/>
                                </button>
                                @endif
                                <div>
                                <button x-on:click='$wire.thisqty="";$wire.thisprice=""' id="closeButtonModalProduk-{{ $product->id }}"
                                  type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-300 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled" data-hs-overlay="#hs-focus-management-modal-{{ $product->id }}">
                                  Batal
                                </button>
                                <button type="button"
                                id='addToCartButton' 
                                @if ($cartcek->where('product_id', $product->id)->where('created_by', auth()->user()->id)->value('quantity') > 0)
                                    wire:click='addToCart({{ $product->id }}); soundBeep.play();'
                                @else
                                    wire:click='addToCart({{ $product->id }}); setTimeout(scrollBottom, 5000); soundBeep.play();'
                                @endif
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-lg gap-x-2 hover:bg-green-600 focus:bg-green-600 focus:outline-none disabled:opacity-50 disabled" data-hs-overlay="#hs-focus-management-modal-{{ $product->id }}">
                                    Simpan
                                </button> 
                                </div>
                      
                              </div>
                            </div>
                          {{-- </form> --}}
                        </div>
                      </div>
                    <!-- End ModalProduk -->
@endforeach
</div>
