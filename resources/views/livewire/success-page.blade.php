<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <section class="flex items-center font-poppins dark:bg-gray-700 ">
    <div class="justify-center flex-1 max-w-6xl px-4 py-4 mx-auto bg-white border rounded-md dark:border-gray-900 dark:bg-gray-900 md:py-10 md:px-10">
      <div>
        <h1 class="px-4 mb-8 text-2xl font-semibold tracking-wide text-gray-700 dark:text-gray-300 ">
          Thank you. Your order has been received. </h1>
        <div class="flex items-stretch w-full h-full px-4 mb-8 border-b border-gray-200 dark:border-gray-700 md:flex-row xl:flex-col md:space-x-6 lg:space-x-8 xl:space-x-0">
          <div class="w-full pb-6 space-x-4 flex-col-2">
                <div>
                <p class="text-lg font-semibold text-left text-gray-800 dark:text-gray-400">
                  @if($order->address->first_name != null || $order->address->last_name != null)
                  {{ $order->address->full_name }}
                  @else
                  {{ $user->where('id',$order->user_id)->value('name') }}
                  {{-- Nama tidak terdaftar --}}
                  @endif
                </p>
                <p class="text-sm cursor-pointer dark:text-gray-400">Phone : {{ $order->address->phone }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->address->street_address }}</p>
                </div>
                <div>
                <h6 class="text-sm text-right text-gray-600 dark:text-gray-400">
                  {{ $villages->where('code',$order->address->village)->value('name') }},
                   {{ $districts->where('code',$order->address->district)->value('name') }},<br>
                   {{ $cities->where('code',$order->address->city)->value('name') }},
                   {{ $states->where('code',$order->address->state)->value('name') }},
                   {{ $order->address->zip_code }}
                </h6>
              </div>
          </div>
        </div>
        <div class="flex flex-wrap items-center pb-4 mb-10 border-b border-gray-200 dark:border-gray-700">
          <div class="w-full px-4 mb-4 sm:w-1/5">
            <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400 ">
              Order Number : </p>
            <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">
              {{ $order->code_tr }}</p>
          </div>
          <div class="w-full px-4 mb-4 sm:w-1/5">
            <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400 ">
              Date : </p>
            <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">
              {{ $order->created_at->format('d-m-Y') }}</p>
          </div>
          <div class="w-full px-4 mb-4 sm:w-1/5">
            <p class="mb-2 text-sm font-medium leading-5 text-gray-800 dark:text-gray-400 ">
              Total : </p>
            <p class="text-base font-semibold leading-4 text-blue-600 dark:text-gray-400">
              @currency($order->grand_total)</p>
          </div>
          <div class="w-full px-4 mb-4 sm:w-1/5">
            <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400 ">
              Order Type : </p>
            <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400 ">
              {{ $order->sales_type }}</p>
          </div>
          <div class="w-full px-4 mb-4 sm:w-1/5">
            <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400 ">
              Payment Method : </p>
            <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400 ">
              {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Transfer' }}</p>
          </div>
        </div>
        <div class="px-4 mb-10">
          <div class="flex flex-col items-stretch justify-center w-full space-y-4 md:flex-row md:space-y-0 md:space-x-8">
            <div class="flex flex-col w-full space-y-6 ">
              <h2 class="mb-2 text-xl font-semibold text-gray-700 dark:text-gray-400">Order details</h2>
              <div class="flex flex-col items-center justify-center w-full pb-4 space-y-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between w-full">
                  <p class="text-base leading-4 text-gray-800 dark:text-gray-400">Subtotal</p>
                  <p class="text-base leading-4 text-gray-600 dark:text-gray-400">@currency($order->grand_total + $order->discount - $order->shipping_amount)</p>
                </div>
                <div class="flex items-center justify-between w-full">
                  <p class="text-base leading-4 text-gray-800 dark:text-gray-400">Discount
                  </p>
                  <p class="text-base leading-4 text-gray-600 dark:text-gray-400">@currency($order->discount)</p>
                </div>
                <div class="flex items-center justify-between w-full">
                  <p class="text-base leading-4 text-gray-800 dark:text-gray-400">Shipping</p>
                  <p class="text-base leading-4 text-gray-600 dark:text-gray-400">@currency($order->shipping_amount)</p>
                </div>
              </div>
              <div class="flex items-center justify-between w-full">
                <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">Total</p>
                <p class="text-base font-semibold leading-4 text-gray-600 dark:text-gray-400">@currency($order->grand_total)</p>
              </div>
            </div>
            <div class="flex flex-col w-full px-2 space-y-4 md:px-8 ">
              <h2 class="mb-2 text-xl font-semibold text-gray-700 dark:text-gray-400">Shipping</h2>
              <div class="flex items-start justify-between w-full">
                <div class="flex items-center justify-center space-x-2">
                  <div class="w-8 h-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-6 h-6 text-blue-600 dark:text-blue-400 bi bi-truck" viewBox="0 0 16 16">
                      <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
                      </path>
                    </svg>
                  </div>
                  <div class="flex flex-col items-center justify-start">
                    <p class="text-lg font-semibold leading-6 text-gray-800 dark:text-gray-400">
                      Delivery<br><span class="text-sm font-normal">Gratis Ongkir selama promo</span>
                    </p>
                  </div>
                </div>
                <p class="text-lg font-semibold leading-6 text-gray-800 dark:text-gray-400">@currency($order->shipping_amount)</p>
              </div>
            </div>
          </div>
        </div>
        <div class="items-center block gap-4 px-4 mt-6 sm:flex ">
          <a href="/my-orders"><button class="w-full px-4 py-2 mt-2 text-sm text-center bg-blue-500 rounded-md text-gray-50 md:w-auto dark:text-gray-300 hover:bg-blue-600 dark:hover:bg-gray-700 dark:bg-gray-800">
            View My Orders</button>
          </a>
          <a href="/products"><button class="w-full px-4 py-2 mt-2 text-sm text-center text-blue-500 border border-blue-500 rounded-md md:w-auto hover:text-white hover:bg-blue-600 dark:border-gray-700 dark:hover:bg-gray-700 dark:text-gray-300">
            Go back shopping</button>
          </a>
          @auth
          @if (auth()->user()->is_admin == 1)
          <a target="_blank" href="{{ route('printorder', [$order->id]) }}" target="_blank"><button class="w-full px-4 py-2 mt-2 text-sm text-center bg-gray-500 rounded-md text-gray-50 md:w-auto dark:text-gray-300 hover:bg-blue-600 dark:hover:bg-gray-700 dark:bg-gray-800">
            <i class="fa fa-print" aria-hidden="true"></i> Print My Orders</button>
          </a>
          <a target="_blank" href="{{ route('printorderprocess', [$order->id]) }}" target="_blank"><button class="w-full px-4 py-2 mt-2 text-sm text-center bg-gray-500 rounded-md text-gray-50 md:w-auto dark:text-gray-300 hover:bg-blue-600 dark:hover:bg-gray-700 dark:bg-gray-800">
            <i class="fa fa-coffee" aria-hidden="true"></i> Print Srt Jalan</button>
          </a>
          <a href="/pos"><button class="w-full px-4 py-2 mt-2 text-sm text-center bg-green-500 rounded-md text-gray-50 md:w-auto dark:text-gray-300 hover:bg-green-600 dark:hover:bg-gray-700 dark:bg-gray-800">
            <i class="fa fa-fax" aria-hidden="true"></i> POS - Safety Mode</button>
          </a>
          <a href="/poscart"><button class="w-full px-4 py-2 mt-2 text-sm text-center bg-green-500 rounded-md text-gray-50 md:w-auto dark:text-gray-300 hover:bg-green-600 dark:hover:bg-gray-700 dark:bg-gray-800">
            <i class="fa fa-fax" aria-hidden="true"></i> POS - Quick Mode</button>
          </a>
          @endif
          @endauth
        </div>
      </div>
    </div>
  </section>
</div>