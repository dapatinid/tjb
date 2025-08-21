<div class="w-full max-w-[85rem] pt-5 px-4 sm:px-4 lg:px-4 mx-auto pb-16">

  <div 
    wire:loading wire:loading.class="fixed" 
    wire:loading.class.remove="hidden" 
    class="hidden left-[calc(50vw-20px)] top-[calc(50vh-20px)]">
    <x-fas-arrows-rotate class="animate-spin size-10 text-blue-500 dark:text-blue-500"/>
  </div>
  
  <div class="container mx-auto">
    <div class="flex justify-between mb-4">
      <a class="cursor-pointer flex flex-nowrap items-center dark:text-gray-300 bg-white dark:bg-gray-800 p-1 rounded-full"
        href="/products" wire:navigate                   
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>

      </a> 
      <h1 class="text-xl font-semibold dark:text-white tengah-aja">Shopping Cart</h1>
      <button wire:click='clearItem()' class="cursor-pointer text-red-500 text-sm underline underline-offset-2 font-semibold text-right bg-white dark:bg-gray-800 p-1 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
        </svg>
      </button>
    </div>

    
    @auth
    <div class="grid grid-cols-1 gap-4  {{ $grup_items->count() ==1 ? 'md:grid-cols-1' : 'lg:grid-cols-3 md:grid-cols-2' }}"> 
    @forelse ($grup_items as $gr_item )
          
        <div class="block">
        <div class="p-4 mb-2 bg-white rounded-lg shadow-md dark:bg-zinc-700 pb-5 {{ $grup_items->count() ==1 ? 'max-w-md mx-auto' : '' }}">
          <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-500">
          <a wire:navigate href="{{ '/@'.$mitra->where('id',$branches->where('id',$gr_item['branch_id'])->value('partner_id'))->value('slug')  }}">
            <h2 class="flex flex-nowrap">
              <x-fas-store class="w-5 h-5 mr-2 text-green-600 dark:text-green-400"/>
              <span class="dark:text-white">
                {{ $branches->where('id',$gr_item['branch_id'])->value('name_partner')  }} {{ $branches->where('id',$gr_item['branch_id'])->value('name')  }}
                </span>
            </h2>
          </a>
          <button wire:click='clearItemByBranch({{ $gr_item['branch_id'] }} , {{ $cart_items->where('branch_id', $gr_item['branch_id'])->count() }})' class="text-sm font-semibold text-right text-red-500 cursor-pointer dark:text-red-500">{{ $cart_items->where('branch_id', $gr_item['branch_id'])->count() }} items</button>
          </div>
          <div>
          <table class="w-full">
            <tbody class="block h-[calc(100vh-270px)] min-h-80 overflow-auto no-scrollbar">
              @foreach ($cart_items->where('branch_id', $gr_item['branch_id']) as $key => $item)

              <tr wire:key='{{ $item['product_id'] }}' class="flex">
                  <td class="py-3">
                      <div class="mr-2">
                        <a wire:navigate href="/products/{{ $item['slug'] }}">
                          @if ( $item['image'] != null)
                            <img class="object-cover w-16 aspect-square" src="{{ Str::replace('%2F', '/',url('storage', $item['image'])) }}" alt="{{ $item['name'] }}">                          
                          @else
                            <img class="object-cover w-16 aspect-square" src="{{ url('storage/food-packaging.png') }}" alt="{{ $item['name'] }}">
                          @endif
                        </a>  
                      </div>
                  </td>
                      <td class="py-3 ms-2">
                      <div class="">
                        <div class="font-semibold dark:text-white"><a wire:navigate href="/products/{{ $item['slug'] }}">
                          @if (Str::contains($item['variant'], $item['name']))
                          {{ $item['variant'] }}
                          @else
                          {{ $item['name'] }} {{ $item['variant'] }}
                          @endif
                        </a></div>
                        
                        <div class="text-xs dark:text-gray-400">
                          <span class="mr-3"><span>@</span>{{ number_format($item['unit_amount']) }}</span>
                          @php
                          if ($item['unit_name'] != null) {
                            $unit_name = '<span style=" margin-right:0.5rem;" class="text-green-600 fa fa-tag"></span>'.$item['unit_name'];
                          } else {
                            $unit_name = '';
                          }
                          @endphp
                          <span class="pr-2">{!! $unit_name !!}</span>
                        </div>
                        @if($item['contain'] != '')
                          @php
                            $contains = Str::of($item['contain'])->explode(',')
                          @endphp
                          <div class="mr-1">
                          @foreach ($contains as $contain)
                            <span class="text-xs bg-slate-100 dark:bg-slate-800">{{ $contain }}</span>
                          @endforeach
                          </div>
                        @endif

                        <div class="flex items-center justify-start mt-2" livewire:modal.modal-cart>
                          <div class="bg-gray-100 rounded-md">
                            <button 
                              wire:click='decreaseQty({{ $item['product_id'] }})'
                              class="relative z-10 py-2 bg-white dark:bg-neutral-800 border-2 border-gray-100 rounded-md w-8 hover:bg-green-400">
                              <x-fas-minus class="text-gray-800 dark:text-white hover:text-gray-500 size-3 ml-2" />
                            </button>

                            <button 
                              class="inline-flex items-center px-2 py-1 text-sm font-medium text-black bg-transparent border border-transparent gap-x-2 hover:text-white hover:bg-green-500 focus:outline-none focus:bg-green-600 disabled:opacity-50 disabled:pointer-events-none"
                              aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal-{{ $item['id'] }}" data-hs-overlay="#hs-focus-management-modal-{{ $item['id'] }}"
                             >
                                {{ $item['quantity']  }}
                            </button>
                            
                            <div id="hs-focus-management-modal-{{ $item['id'] }}" class="hs-overlay [--body-scroll:true] [--overlay-backdrop:false] [--auto-close:false] hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-focus-management-modal-label">
                              <div class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
                                {{-- <form > --}}
                                  <div class="flex flex-col bg-white border shadow-sm pointer-events-auto rounded-xl">
                                    <div class="flex items-center justify-between px-4 py-3 border-b">
                                      <h3 id="hs-focus-management-modal-label" class="font-bold text-gray-800">
                                        {{ $item['name'] }} : {{ $item['variant'] }}
                                      </h3>
                                      <button wire:click='typeQtyReset()' type="button" class="inline-flex items-center justify-center text-gray-800 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#hs-focus-management-modal-{{ $item['id'] }}">
                                        <span class="sr-only">Close</span>
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                          <path d="M18 6 6 18"></path>
                                          <path d="m6 6 12 12"></path>
                                        </svg>
                                      </button>
                                    </div>
                                    <div class="p-4 overflow-y-auto">
                                      <label for="input-label" class="block mb-2 text-sm font-medium">Quantity</label>
                                      <input wire:keyup.enter='typeQty({{ $item['id'] }}, {{ $item['quantity'] }})' type="number" id="thisqty" name="thisqty" wire:model='thisqty' onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400" placeholder="{{ $item['quantity'] }}" autofocus="" >
                                    </div>
                                    <div class="flex items-center justify-end px-4 py-3 border-t gap-x-2">
                                      <button wire:click='typeQtyReset()' type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-300 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-focus-management-modal-{{ $item['id'] }}">
                                        Batal
                                      </button>
                                      <button wire:click='typeQty({{ $item['id']}}, {{ $item['quantity'] }} )' class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-lg gap-x-2 hover:bg-green-600 focus:outline-none focus:bg-green-500 disabled:opacity-50 disabled:pointer-events-none">
                                        Simpan
                                      </button>
                                    </div>
                                  </div>
                                {{-- </form> --}}
                              </div>
                            </div>
                            
                            <button 
                            wire:click='increaseQty({{ $item['product_id'] }}, {{ $item['quantity'] }})' 
                            class="px-2 py-2 bg-white dark:bg-neutral-800 border-2 border-gray-100 w-8 rounded-md hover:bg-green-400">
                              <x-fas-plus class="text-gray-800 dark:text-white hover:text-gray-500 size-3 mr-2" />
                            </button>
                          </div>
                        </div>
                      </div>

                  </td>
                  <td class="ms-auto block text-end py-[12.6px]">
                    <div class="dark:text-gray-400">total</div>
                    <div class="font-semibold dark:text-white">{{ number_format($item['total_amount']) }}</div>
                    <button wire:click='removeItem({{ $item['product_id'] }})' class="text-sm bg-slate-200 border-2 border-red-400 rounded-md px-[0.35rem] hover:bg-red-500 hover:text-white hover:border-red-700 mt-1">
                      <span wire:loading.remove wire:target='removeItem({{ $item['product_id'] }})'>X</span>
                      <span wire:loading wire:target='removeItem({{ $item['product_id'] }})'>
                        <div class="mt-1 animate-spin inline-block size-3 border-[2px] border-current border-t-transparent text-black rounded-full dark:text-black group-hover:text-white dark:group-hover:text-white" role="status" aria-label="loading">
                          <span class="sr-only">Loading...</span>
                        </div>  
                      </span>   
                    </button>
                  </td>
                </tr>
                  
              @endforeach

              <!-- More product rows -->

            </tbody>
          </table>
        </div>
        <a wire:navigate href="/checkout?branch_id={{ $gr_item['branch_id'] }}&shipping_method=self_pickup&sales_type=self_pickup&payment_method=cash&rekening=KAS+KASIR"><button class="w-full px-4 py-2 mt-5 text-center text-white bg-blue-500 rounded-lg hover:bg-green-500">Checkout @currency($cart_items->where('branch_id', $gr_item['branch_id'])->sum('total_amount'))</button></a>
        </div>
        
      </div>
      @empty
      <div colspan="5" class="py-4 text-4xl font-semibold text-center text-slate-500 tengah-aja">Troli kosong. Yuk belanja! <br><a wire:navigate href="/products" class="text-blue-500">Klik disini...</a></div>
      @endforelse
          
    </div>
    @endauth
    @guest
    <div colspan="5" class="py-4 text-4xl font-semibold text-center text-slate-500 tengah-aja">Troli kosong. Anda harus login. <br><a wire:navigate href="/login" class="text-blue-500">Klik disini...</a></div>
    @endguest
  </div>
</div>

{{-- <script>
  window.addEventListener('cart-page', event => {
     window.location.reload(false); 
  })
</script> --}}