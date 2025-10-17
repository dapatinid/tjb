<div class="w-full max-w-[85rem] py-10 px-1 sm:px-4 lg:px-8 mx-auto">
    {{-- <div class="justify-start sm:hidden absolute left-4 top-1.5 bg-blue-600 dark:bg-neutral-800 py-2 pr-10">
        <a class="cursor-pointer flex flex-nowrap items-center text-white bg:text-gray-300 "
          href="/cart"                    
          >
              <x-far-arrow-alt-circle-left class="w-5 h-5 mr-2 text-white hover:text-blue-500"/> kembali
        </a> 
      </div> --}}
    <h1 class="text-2xl font-bold text-nowrap text-center text-slate-500 dark:text-white">
        @if ($isadmin == 1)
            Orders | Paid Today<br>
            <span class="text-lg">Cash @currency($paymentcash - $my_orders_sum_cashback) | Tf @currency($paymenttf)<br></span>
        @else
            My Orders @currency($my_orders_sum)
        @endif
    </h1>

    @auth
        @if ($isadmin == 1)
            <div class="flex mt-3">
                <div
                    class="flex p-1 mx-auto transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600">
                    <nav class="flex gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                        <a wire:navigate.hover href="/my-orders-unpaid">
                            <button type="button"
                                class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white"
                                id="segment-item-4" aria-selected="false" data-hs-tab="#segment-4" aria-controls="segment-4"
                                role="tab">
                                Unpaid
                            </button>
                        </a>
                        <button type="button"
                            class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white active"
                            id="segment-item-1" aria-selected="true" data-hs-tab="#segment-1" aria-controls="segment-1"
                            role="tab">
                            Paid Orders
                        </button>

                    </nav>
                </div>
            </div>
        @endif
    @endauth

    <div class="mt-3">
        <div id="segment-1" role="tabpanel" aria-labelledby="segment-item-1">
            <div class="flex flex-col pt-4 mt-4 ">
                <div class="flex flex-wrap">

                                    @forelse ($orders as $order)
                                        @php
                                            $status = '';
                                            $payment_status = '';

                                            if ($order->status == 'new') {
                                                $status =
                                                    '<span class="px-3 py-1 text-white bg-blue-500 rounded shadow cursor-pointer"><i class="fa fa-thumb-tack" aria-hidden="true"></i> New</span>';
                                            }
                                            if ($order->status == 'processing') {
                                                $status =
                                                    '<span class="px-3 py-1 text-white bg-yellow-500 rounded shadow cursor-pointer"><i class="fa fa-refresh" aria-hidden="true"></i> Processing</span>';
                                            }
                                            if ($order->status == 'shipped') {
                                                $status =
                                                    '<span class="px-3 py-1 text-white bg-orange-500 rounded shadow cursor-pointer"><i class="fa fa-truck" aria-hidden="true"></i> Shipped</span>';
                                            }
                                            if ($order->status == 'delivered') {
                                                $status =
                                                    '<span class="px-3 py-1 text-white bg-green-500 rounded shadow cursor-pointer"><i class="fa fa-check-circle" aria-hidden="true"></i> Delivered</span>';
                                            }
                                            if ($order->status == 'canceled') {
                                                $status =
                                                    '<span class="px-3 py-1 text-white bg-red-500 rounded shadow cursor-pointer"><i class="fa fa-times" aria-hidden="true"></i> Canceled</span>';
                                            }

                                            if ($order->is_paid == 0) {
                                                $payment_status =
                                                    '<span class="px-3 py-1 text-white bg-red-400 rounded shadow">Unpaid</span>';
                                            }
                                            if ($order->is_paid == 0 && $order->status == 'canceled') {
                                                $payment_status =
                                                    '<span class="px-3 py-1 text-white bg-gray-600 rounded shadow">Unpaid</span>';
                                            }
                                            if ($order->is_paid == 1) {
                                                $payment_status =
                                                    '<span class="px-3 py-1 text-white bg-blue-500 rounded shadow">Paid</span>';
                                            }

                                            if (empty($order->address->last_name)) {
                                                $nama = '';
                                            } else {
                                                $nama =
                                                    '-> ' .
                                                    $order->address->first_name .
                                                    ' ' .
                                                    $order->address->last_name;
                                            }

                                            if (empty($order->address->phone)) {
                                                $telp = '';
                                            } else {
                                                $telp = $order->address->phone;
                                            }

                                            $payment_last = $paymentlast
                                                ->where('paymentable_id', $order->id)
                                                ->value('payment_method');

                                        @endphp
                                        <div wire:key='{{ $order->id }}' class="w-full p-1 lg:w-1/3 sm:w-1/2 ">
                                        <div id="hs-dropdown-left-but-right-on-lg" class="group hs-dropdown relative [--strategy:absolute]">
                                            <div 
                                                class="text-sm text-gray-800 whitespace-nowrap dark:text-gray-800">

                                                <div 
                                                style="position: relative; mask: radial-gradient(7px at 14px 14px, transparent 98%, black) -14px -14px;"  
                                                class="flex justify-between px-3 py-2 bg-white border-b-2 border-gray-300 border-dashed group-hover:bg-zinc-50 group-focus:bg-zinc-50">
                                                    <span>{{ $order->q }}</span>
                                                    <span class="font-medium">{{ auth()->user()->is_admin == 1 ? ($order->user->id == 2 ? "Customer Umum" : $order->user->name) : '' }}
                                                        {{ auth()->user()->is_admin == 0 ? $order->branch->name : '' }}
                                                        {{ $nama }} </span>
                                                    <span class="flex hover:lg:text-yellow-500 whitespace-nowrap">
                                                        <svg class="relative -bottom-[2px] size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                                    </span>
                                                </div>
                                                <div style="position: relative; mask: radial-gradient(7px at 14px 14px, transparent 98%, black) -14px -14px;"  
                                                class="pt-2 bg-white group-hover:bg-zinc-50 group-focus:bg-zinc-50">
                                                    <div class="flex justify-between px-3 py-2">
                                                        <span class="">{!! $status !!}</span>
                                                        <span class="text-center">{{ $order->sales_type }}</span>
                                                        <span class="text-end">{{ $payment_last }}
                                                            {!! $payment_status !!}</span>
                                                    </div>

                                                    <div class="flex justify-between px-3 pb-2">
                                                        <span
                                                            class="{{ Str::substr($order->date_order, 0, 10) != $today ? 'text-green-600' : '' }}">{{ $order->date_order }}</span>
                                                        <span><a
                                                                href="http://wa.me/+62{{ $telp }}">{{ $telp }}</a></span>
                                                        <span class="font-medium">@currency($order->grand_total)</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-left-but-right-on-lg"
                                                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden z-10 top-0 start-auto end-0 min-w-60 bg-gray-200 p-2 shadow-md rounded-lg mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700">

                                                <div class="py-1">
                                                    <a 
                                                        href="/my-orders/{{ $order->id}}"><button
                                                        class="w-full px-4 py-2 text-white bg-yellow-500 rounded-md hover:bg-yellow-300 items-center flex space-x-2 justify-center"><x-fas-search class="size-4" /> <span> {{ auth()->user()->is_admin == 1 ? $order->id : 'Detail'  }}</span></button></a>
                                                    </div>
                                                @auth
                                                    @if (auth()->user()->is_admin == 1)
                                                        <div class="py-1">
                                                            <button
                                                            wire:click.prevent='changeStatus({{ $order->id }})'
                                                                    class="w-full px-4 py-2 text-white rounded-md bg-slate-600 hover:bg-slate-400">
                                                                        Ganti Status</button>
                                                        </div>
                                                        <div class="py-1">
                                                            <a href="{{ route('printorder', [$order->id]) }}" target="_blank"><button
                                                                    class="w-full px-4 py-2 text-white rounded-md bg-slate-600 hover:bg-slate-400">
                                                                    Print</button></a>
                                                        </div>
                                                        <div class="py-1">
                                                            <a href="{{ route('printorderprocess', [$order->id]) }}" target="_blank"><button
                                                                    class="w-full px-4 py-2 text-white rounded-md bg-slate-600 hover:bg-slate-400">
                                                                        Srt Jalan</button></a>
                                                        </div>
                                                    @endif
                                                    @if (auth()->user()->is_admin == 0)
                                                        <div class="py-1">
                                                            @php
                                                                $wakasir = $branch
                                                                    ->where('id', $order->branch_id)
                                                                    ->value('phone');

                                                            @endphp
                                                            <a
                                                                href="https://wa.me/+62{{ $wakasir }}?text=Apakah pesanan saya no. {{ $order->id }} sudah di proses?"><button
                                                                    class="w-full px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-500"><i
                                                                        class="fa fa-whatsapp"
                                                                        aria-hidden="true"></i> WA Kasir</button>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endauth

                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="py-4 text-xl font-semibold text-center text-slate-500 tengah-aja">Kosong</div>
                                    @endforelse

                                </div>

                    <!-- pagination start -->
                    {{-- <style>
                        nav div div p {
                            margin-left: 20px;
                            margin-right: 20px;
                        }
                    </style> --}}
                    <div class="flex justify-center my-5">
                        {{-- {{ $orders->links('pagination::bootstrap-4') }} --}}
                        {{ $orders->links('vendor.pagination.tailwind') }}
                    </div>
                    <!-- pagination end -->

            </div>
        </div>

    </div>


</div>
