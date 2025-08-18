<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-2xl font-bold text-center text-slate-500 dark:text-white">
      Payments List
    </h1>
    
    <div class="flex items-center justify-between mt-3">
      <div class="flex">
        <div class="flex p-1 mx-auto transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600">
          <nav class="flex gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
            <button type="button" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white active" id="segment-item-1" aria-selected="true" data-hs-tab="#segment-1" aria-controls="segment-1" role="tab">
              Payments
            </button>
            <button type="button" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white" id="segment-item-2" aria-selected="false" data-hs-tab="#segment-2" aria-controls="segment-2" role="tab">
              Unpaid
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
      <div id="segment-1" role="tabpanel" aria-labelledby="segment-item-1">
        <div class="flex flex-col p-5 mt-4 bg-white rounded-lg shadow-lg dark:bg-neutral-700">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Order</th>
                      <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Date Payment</th>
                      <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Customer</th>
                      <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Method</th>
                      <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Nominal</th>
                      <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Kembali</th>
                      <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>
  
                    @foreach ($payments as $payment)     
                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-green-400 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-900">
                      <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200"><a wire:navigate href="/my-orders/{{ $payment->paymentable_id }}">{{ $payment->paymentable_id }}</a></td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $payment->date_payment }}</td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $users->where('id',$payment->user_id)->value('name') }}</td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $payment->payment_method }}</td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($payment->nominal_plus)</td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($payment->nominal_mins)</td>                      
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($payment->nominal_plus - $payment->nominal_mins)</td>                      
                    </tr>
                    @endforeach
      
                  </tbody>
                  <tfoot>
                    @php
                      $jumlahkembalian = 0;
                      foreach($payments as $payment) {
                          $jumlahkembalian += $payment->nominal_mins;
                      }
                    @endphp
                    <tr class="bg-slate-300 dark:bg-neutral-900">
                      <td colspan="4" class="px-6 dark:text-gray-200">CASH @formatNumber($payments->where('payment_method', 'cash')->sum('nominal_plus') - $jumlahkembalian) | TRANSFER @formatNumber($payments->where('payment_method', 'transfer')->sum('nominal_plus'))</td>
                      <td class="px-6 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($payments->sum('nominal_plus'))</td>
                      <td class="px-6 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($jumlahkembalian)</td>
                      <td class="px-6 py-4 text-sm font-bold text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($payments->sum('nominal_plus') - $jumlahkembalian)</td>
                  </tr>
                  </tfoot>
                </table>
              </div>
            </div> 
            
            <!-- pagination start -->
            <!-- pagination end -->
    
          </div>
        </div>
      </div>
      <div id="segment-2" class="hidden" role="tabpanel" aria-labelledby="segment-item-2">
        <div class="flex flex-col p-5 mt-4 bg-white rounded-lg shadow-lg dark:bg-neutral-700">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
  
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Order</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Date Order</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Customer</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Nominal Unpaid</th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($ordersUnpaid->where('total_cashback', '<', 0) as $orderUpd)     
                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-green-400 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-900">
                      <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200"><a wire:navigate href="/my-orders/{{ $orderUpd->id }}">{{ $orderUpd->id }}</a></td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $orderUpd->date_order }}</td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $users->where('id',$orderUpd->user_id)->value('name')  }}</td>
                      <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($orderUpd->total_cashback)</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr class="bg-slate-300 dark:bg-neutral-900">
                        <td colspan="3"></td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800 whitespace-nowrap text-end dark:text-gray-200">@formatNumber($ordersUnpaid->where('total_cashback', '<', 0)->sum('total_cashback'))</td>
                    </tr>
                  </tfoot>
                </table>
               
              </div>
            </div> 
            
            <!-- pagination start -->
            <!-- pagination end -->
  
          </div>
        </div>
      </div>
     
    </div> 
  
  </div>