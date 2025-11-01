<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-2xl font-bold text-center text-slate-500 dark:text-white">
    Items List
  </h1>
  
  <div class="flex items-center justify-between mt-3">
    <div class="flex">
      <div class="flex p-1 mx-auto transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600">
        <nav class="flex gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
          <a href="/items-status" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white" >
            Items
          </a>
          <button type="button" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white active" id="segment-item-2" aria-selected="false" data-hs-tab="#segment-2" aria-controls="segment-2" role="tab">
            Return
          </button>
          {{-- <a href="/items-warehouse" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white" >
            Warehouse
          </a> --}}
                  
        </nav>
      </div>
    </div>

    <div class="inline-flex flex-wrap justify-end text-end">
    <div class=""><span class="dark:text-gray-400">From</span> <input wire:model.live='date_awal' type="date" name="date_awal" id="date_awal" class="px-2 bg-white"></div>
    <div class=""><span class="dark:text-gray-400 ml-3">To</span> <input wire:model.live='date_akhir' type="date" name="date_akhir" id="date_akhir" class="px-2 bg-white"></div>
    </div>
  </div>
  
  <div class="mt-3">
    <div id="segment-2" class="" role="tabpanel" aria-labelledby="segment-item-2">
      <div class="flex flex-col p-5 mt-4 bg-white rounded-lg shadow-lg dark:bg-neutral-700">
        <div class="-m-1.5 overflow-x-auto">
          <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">

              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                  <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">#</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Date</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Name</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Variant</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Qty Return</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Price</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Total</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach ($itemsreturn as $item)  
                  <tr class="odd:bg-white even:bg-gray-100 hover:bg-green-400 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-900">
                    <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200"><a wire:navigate href="/my-orders/{{ $orders->where('id', $item->order_id)->value('id') }}">{{ $orders->where('id', $item->order_id)->value('id') }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $item->updated_at }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $products->where('id', $item->product_id)->value('name') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $products->where('id', $item->product_id)->value('variant') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $item->unit_amount }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $item->total_amount }}</td>
                  </tr>
                  @endforeach
    
                </tbody>
              </table>
             
            </div>
          </div> 
          

        </div>
      </div>
    </div>
    
   
    <!-- pagination start -->
            <div class="mx-2 mt-5">
                {{ $itemsreturn->links('vendor.pagination.tailwind') }}
            </div>
  <!-- pagination end -->
  </div> 


</div>