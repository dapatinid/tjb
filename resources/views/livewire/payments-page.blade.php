<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-2xl font-bold text-center text-slate-500 dark:text-white">
      Payments List
    </h1>
    
    <div class="flex flex-col md:flex-row items-center justify-between mt-6 gap-4">
      <div class="flex p-1 bg-gray-100 rounded-lg dark:bg-neutral-700">
        <nav class="flex gap-x-1" aria-label="Tabs" role="tablist">
          <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 rounded-lg active hs-tab-active:bg-white hs-tab-active:text-gray-800 dark:hs-tab-active:text-gray-100 dark:hs-tab-active:bg-gray-800 dark:text-neutral-400" id="segment-item-1" data-hs-tab="#segment-1" aria-controls="segment-1" role="tab">
            Payments
          </button>
          <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-800 dark:hs-tab-active:text-gray-100 dark:hs-tab-active:bg-gray-800 dark:text-neutral-400" id="segment-item-2" data-hs-tab="#segment-2" aria-controls="segment-2" role="tab">
            Unpaid
          </button>
        </nav>
      </div>
  
      <div class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm border dark:bg-neutral-800 dark:border-neutral-700">
        <span class="text-sm dark:text-gray-400">From:</span>
        <input wire:model.live='date_awal' type="date" class="text-sm border-none focus:ring-0 dark:bg-transparent dark:text-white">
        <span class="text-sm dark:text-gray-400">To:</span>
        <input wire:model.live='date_akhir' type="date" class="text-sm border-none focus:ring-0 dark:bg-transparent dark:text-white">
      </div>
    </div>
    
    <div class="mt-6">
      <div id="segment-1" role="tabpanel" aria-labelledby="segment-item-1">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 border dark:border-neutral-700">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-neutral-700">
                <tr>
                  <th class="px-6 py-3 text-start text-xs font-semibold uppercase dark:text-neutral-200">Order</th>
                  <th class="px-6 py-3 text-start text-xs font-semibold uppercase dark:text-neutral-200">Date</th>
                  <th class="px-6 py-3 text-start text-xs font-semibold uppercase dark:text-neutral-200">Customer</th>
                  <th class="px-6 py-3 text-start text-xs font-semibold uppercase dark:text-neutral-200">Method</th>
                  <th class="px-6 py-3 text-end text-xs font-semibold uppercase dark:text-neutral-200">Nominal</th>
                  <th class="px-6 py-3 text-end text-xs font-semibold uppercase dark:text-neutral-200">Kembali</th>
                  <th class="px-6 py-3 text-end text-xs font-semibold uppercase dark:text-neutral-200">Total</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @php $jumlahkembalian = 0; @endphp
                @foreach ($payments as $payment)     
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                  <td class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                    <a wire:navigate href="/my-orders/{{ $payment->paymentable_id }}">#{{ $payment->paymentable_id }}</a>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $payment->date_payment }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                    {{ $users->firstWhere('id', $payment->user_id)->name ?? 'N/A' }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 uppercase">{{ $payment->payment_method }}</td>
                  <td class="px-6 py-4 text-sm text-end text-gray-800 dark:text-gray-200">{{ number_format($payment->nominal_plus, 0, ',', '.') }}</td>
                  <td class="px-6 py-4 text-sm text-end text-red-500">{{ number_format($payment->nominal_mins, 0, ',', '.') }}</td>                      
                  <td class="px-6 py-4 text-sm text-end font-semibold text-gray-800 dark:text-gray-200">{{ number_format($payment->nominal_plus - $payment->nominal_mins, 0, ',', '.') }}</td>                      
                </tr>
                @php $jumlahkembalian += $payment->nominal_mins; @endphp
                @endforeach
              </tbody>
              <tfoot class="bg-gray-50 dark:bg-neutral-900 font-bold">
                <tr>
                  <td colspan="4" class="px-6 py-4 text-sm dark:text-gray-200">
                    CASH: {{ number_format($payments->where('payment_method', 'cash')->sum('nominal_plus') - $jumlahkembalian, 0, ',', '.') }} | 
                    TF: {{ number_format($payments->where('payment_method', 'transfer')->sum('nominal_plus'), 0, ',', '.') }}
                  </td>
                  <td class="px-6 py-4 text-end">{{ number_format($payments->sum('nominal_plus'), 0, ',', '.') }}</td>
                  <td class="px-6 py-4 text-end text-red-500">{{ number_format($jumlahkembalian, 0, ',', '.') }}</td>
                  <td class="px-6 py-4 text-end text-green-600 dark:text-green-400">{{ number_format($payments->sum('nominal_plus') - $jumlahkembalian, 0, ',', '.') }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="mt-4">{{ $payments->links() }}</div>
      </div>

      <div id="segment-2" class="hidden" role="tabpanel" aria-labelledby="segment-item-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 border dark:border-neutral-700">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-neutral-700">
              <tr>
                <th class="px-6 py-3 text-start text-xs font-semibold uppercase dark:text-neutral-200">Order</th>
                <th class="px-6 py-3 text-start text-xs font-semibold uppercase dark:text-neutral-200">Date Order</th>
                <th class="px-6 py-3 text-start text-xs font-semibold uppercase dark:text-neutral-200">Customer</th>
                <th class="px-6 py-3 text-end text-xs font-semibold uppercase dark:text-neutral-200">Nominal Unpaid</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ordersUnpaid as $orderUpd)     
              <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                <td class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                    <a wire:navigate href="/my-orders/{{ $orderUpd->id }}">#{{ $orderUpd->id }}</a>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $orderUpd->date_order }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                    {{ $users->firstWhere('id', $orderUpd->user_id)->name ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 text-sm text-end text-red-500 font-semibold">{{ number_format($orderUpd->total_cashback, 0, ',', '.') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div> 
</div>