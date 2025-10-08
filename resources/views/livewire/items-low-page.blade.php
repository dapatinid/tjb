<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-2xl font-bold text-center text-slate-500 dark:text-white">
    Items List
  </h1>
  
  <div class="flex items-center justify-between mt-3">
    <div class="flex">
      <div class="flex p-1 mx-auto transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600">
        <nav class="flex gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
          <a wire:navigate.hover href="/items-sold" wire:navigate.hover class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white" >
            Items
          </a>
          <a wire:navigate.hover href="/items-return" wire:navigate.hover class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white" >
            Return
          </a>
          <button type="button" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white active" id="segment-item-3" aria-selected="false" data-hs-tab="#segment-3" aria-controls="segment-3" role="tab">
            Low Stock
          </button>
                  
        </nav>
      </div>
    </div>

    <div class="inline-flex flex-wrap justify-end text-end">
    <div class=""><span class="dark:text-gray-400">From</span> <input wire:model.live='date_awal' type="date" name="date_awal" id="date_awal" class="px-2 bg-white"></div>
    <div class=""><span class="dark:text-gray-400 ml-3">To</span> <input wire:model.live='date_akhir' type="date" name="date_akhir" id="date_akhir" class="px-2 bg-white"></div>
    </div>
  </div>
  
  <div class="mt-3">
      <div class="flex flex-col p-5 mt-4 bg-white rounded-lg shadow-lg dark:bg-neutral-700">
        <div class="-m-1.5 overflow-x-auto">
          <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">

              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                  <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">#</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Name</th>
                    @if (Auth::user()->roles[0]->name === 'Admin' || Auth::user()->roles[0]->name === 'Owner')                        
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Cost</th>
                    @endif
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Stock tanpa New</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Stock</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Status</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Beli</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Jual</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Prod</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Adj</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Tf-Out</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Tf-In</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach ($products as $product) 
                  @php
                    $stIN = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->where('status', '!=', 'new')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('p_quantity');
                    $stOUT = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('quantity');
                    $stOUTTanpaNew = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->where('status', '!=', 'new')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('quantity');
                    $status = (($stIN - $stOUT) > $product->low_alert) ? '<span class="px-3 py-1 text-white bg-blue-500 rounded shadow">aman</span>' : '<span class="px-3 py-1 text-white bg-red-500 rounded shadow">LOW</span>' ;
                    
                    $belinya = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('porder_id')->where('status', '!=', 'new')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('p_quantity');
                    $jualnya = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('order_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('quantity');
                    $bikinnyaP = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('production_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('p_quantity');
                    $bikinnyaM = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('production_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('quantity');
                    $sesuainyaP = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('adj_item_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('p_quantity');
                    $sesuainyaM = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('adj_item_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('quantity');
                    $transferoutnya = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('tr_stk_out_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('quantity');
                    $transferinnya = App\Models\OrderItem::where('branch_id', auth()->user()->branch_id)->where('product_id',$product->id)->whereNotNull('tr_stk_in_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->sum('p_quantity');
                  @endphp     
                  <tr class="odd:bg-white even:bg-gray-100 hover:bg-green-400 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-900">
                    <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200"><a wire:navigate href="/admin/products/{{ $product->id }}/edit">{{ $product->id }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><a wire:navigate href="/admin/products/{{ $product->id }}/edit">{{ $product->name }} {{ $product->variant != null ? $product->variant : ""}}</a></td>
                    @if (Auth::user()->roles[0]->name === 'Admin' || Auth::user()->roles[0]->name === 'Owner')       
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $product->cogs }}</td>
                    @endif
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $stIN - $stOUTTanpaNew }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $stIN - $stOUT }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{!! $status !!}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $belinya }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ ($jualnya)*-1 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $bikinnyaP - $bikinnyaM }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $sesuainyaP - $sesuainyaM }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ ($transferoutnya)*-1 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">{{ $transferinnya }}</td>
                  </tr>
                  @endforeach
    
                </tbody>
              </table>
             
            </div>
          </div> 
          
          <!-- pagination start -->
                    <div class="mx-2">
                        {{ $products->links() }}
                    </div>
          <!-- pagination end -->

        </div>
      </div>
   
  </div> 

</div>