<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <span class="text-4xl font-bold text-slate-500 dark:text-white">Order Details </span>
    <span class="text-xl font-bold text-slate-500 dark:text-white">{{ $order->code_tr }}</span>

    <!-- Grid -->
    <div class="grid gap-4 mt-5 sm:grid-cols-2 lg:grid-cols-4 sm:gap-6">
        <!-- Card -->
        <div class="flex flex-col bg-white border-none shadow-md rounded-xl dark:bg-slate-900 dark:border-gray-800">
            <div class="flex p-4 md:p-5 gap-x-4">
                <div
                    class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
                    <svg class="flex-shrink-0 text-gray-600 size-5 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>

                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-wide text-gray-500 uppercase">
                            Customer
                        </p>
                    </div>
                    <div class="flex items-center mt-1 gap-x-2 dark:text-gray-200">
                        <div>
                            @if ($address_lastname != null)
                                {{ $address_firstname }} {{ $address_lastname }}
                            @else
                                {{ $user->where('id', $order->user_id)->value('name') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="flex flex-col bg-white border-none shadow-md rounded-xl dark:bg-slate-900 dark:border-gray-800">
            <div class="flex p-4 md:p-5 gap-x-4">
                <div
                    class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
                    <svg class="flex-shrink-0 text-gray-600 size-5 dark:text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 22h14" />
                        <path d="M5 2h14" />
                        <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" />
                        <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" />
                    </svg>
                </div>

                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-wide text-gray-500 uppercase">
                            Order Date
                        </p>
                    </div>
                    <div class="flex items-center mt-1 gap-x-2">
                        <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">
                            @if (empty($order->date_order))
                            Gagal tercatat
                            @else
                            {{ $order->date_order }}
                            @endif
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="flex flex-col bg-white border-none shadow-md rounded-xl dark:bg-slate-900 dark:border-gray-800">
            <div class="flex p-4 md:p-5 gap-x-4">
                <div
                    class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
                    <svg class="flex-shrink-0 text-gray-600 size-5 dark:text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
                        <path d="m12 12 4 10 1.7-4.3L22 16Z" />
                    </svg>
                </div>

                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-wide text-gray-500 uppercase">
                            Order Status
                        </p>
                    </div>
                    <div class="flex items-center mt-1 gap-x-2 dark:text-gray-200">

                        @php
                            $status = '';

                            if ($order->status == 'new') {
                                $status = '<span class="px-3 py-1 text-white bg-blue-500 rounded shadow">New</span>';
                            }
                            if ($order->status == 'processing') {
                                $status =
                                    '<span class="px-3 py-1 text-white bg-yellow-500 rounded shadow">Processing</span>';
                            }
                            if ($order->status == 'shipped') {
                                $status =
                                    '<span class="px-3 py-1 text-white bg-orange-500 rounded shadow">Shipped</span>';
                            }
                            if ($order->status == 'delivered') {
                                $status =
                                    '<span class="px-3 py-1 text-white bg-green-500 rounded shadow">Delivered</span>';
                            }
                            if ($order->status == 'canceled') {
                                $status =
                                    '<span class="px-3 py-1 text-white bg-red-500 rounded shadow">Canceled</span>';
                            }
                        @endphp
                        {!! $status !!} {{ $order->sales_type }}

                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="flex flex-col bg-white border-none shadow-md rounded-xl dark:bg-slate-900 dark:border-gray-800">
            <div class="flex p-4 md:p-5 gap-x-4">
                <div
                    class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
                    <svg class="flex-shrink-0 text-gray-600 size-5 dark:text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12s2.545-5 7-5c4.454 0 7 5 7 5s-2.546 5-7 5c-4.455 0-7-5-7-5z" />
                        <path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                        <path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2" />
                        <path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2" />
                    </svg>
                </div>

                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-wide text-gray-500 uppercase">
                            Payment Status
                        </p>
                    </div>
                    <div class="flex items-center mt-1 gap-x-2 dark:text-gray-200">
                        @php
                            $payment_status = '';

                            if ($order->is_paid == 0) {
                                $payment_status =
                                    '<span class="px-3 py-1 text-white bg-red-400 rounded shadow">Unpaid</span>';
                            }
                            if ($order->is_paid == 1) {
                                $payment_status =
                                    '<span class="px-3 py-1 text-white bg-blue-500 rounded shadow">Paid</span>';
                            }
                        @endphp
                        @if (!empty($payment_status) || !empty($paymentslast->payment_method))
                            {!! $payment_status !!} {{ $paymentslast->payment_method }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Grid -->

    <div class="flex flex-col gap-4 mt-4 md:flex-row">
        <div class="md:w-3/5">
            <div class="p-6 mb-4 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-slate-900 dark:border-gray-800 dark:text-white">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="font-semibold text-left">Product</th>
                            <th class="px-2 font-semibold text-right">Qty</th>
                            <th class="font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_items as $item)
                            <tr wire:key="{{ $item->id }}">
                                <td class="py-4">
                                    <div class="flex flex-wrap items-center">
                                        @if ($item->product->images != null)
                                        <a wire:navigate href="/products/{{ $item->product->slug }}">
                                            <img class="w-16 mr-4 aspect-square" onerror="this.src='{{ url('storage/food-packaging.png') }}';"
                                                src="{{ Str::replace('%2F', '/',url('storage', $item->product->images[0])) }}"
                                                alt="{{ $item->product->name }}"></a>
                                        @else
                                        <a wire:navigate href="/products/{{ $item->product->slug }}">
                                            <img class="w-16 mr-4 aspect-square" src="{{ url('storage/food-packaging.png') }}"
                                                alt="{{ $item->product->name }}"></a>
                                        @endif
                                        <div class="block">
                                            <div class="font-semibold">
                                                @if (Str::contains($item->product->variant, $item->product->name))
                                                {{ $item->product->variant }}
                                                @else
                                                {{ $item->product->name }} {{ $item->product->variant }}
                                                @endif
                                            </div>
                                            <div class="font-semibold">
                                                <span class="text-xs"><span
                                                        style=" margin-left:0.5rem;margin-right:0.2rem;"
                                                        class="text-green-600 fa fa-tag"></span>{{ $item->product->unit_name }}</span>
                                                @if ($item->product->contain != '')
                                                    @php
                                                        $contains = Str::of($item->product->contain)->explode(',');
                                                    @endphp
                                                    @foreach ($contains as $contain)
                                                        <span class="text-xs bg-slate-100">{{ $contain }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-right">
                                    @php
                                        $is_return = '';
                                        if ($item->quantity < 1) {
                                        $is_return = '<span class="bg-red-400 py-0.5 px-2 rounded text-white">Return</span>';
                                        }
                                    @endphp
                                    {!! $is_return !!} 
                                    <span 
                                    @auth
                                        @if (auth()->user()->is_admin == 1 && $item->quantity > 0)
                                            aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal-return-{{ $item->id }}" data-hs-overlay="#hs-focus-management-modal-return-{{ $item->id }}"
                                        @endif 
                                    @endauth 
                                    class="px-2 rounded-full cursor-pointer hover:bg-gray-200">
                                    {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="text-xs">@currency($item->unit_amount)</div>
                                    <div>@currency($item->total_amount)</div>
                                </td>
                            </tr>
                            <div id="hs-focus-management-modal-return-{{ $item->id }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-focus-management-modal-return-label">
                                <div class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
                                {{-- <form > --}}
                                    <div class="flex flex-col bg-white border shadow-sm pointer-events-auto rounded-xl">
                                    <div class="flex items-center justify-between px-4 py-3 border-b">
                                        <h3 id="hs-focus-management-modal-return-label" class="font-bold text-gray-800">
                                            {{ $item->product->name }} : {{ $item->product->variant }}
                                        </h3>
                                        <button type="button" class="inline-flex items-center justify-center text-gray-800 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#hs-focus-management-modal-return-{{ $item->id }}">
                                        <span class="sr-only">Close</span>
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d=" M18 6 6 18">
                                                    </path>
                                                    <path d="m6 6 12 12"></path>
                                                    </svg>
                                                    </button>
                                        </div>
                                        <div class="p-4 overflow-y-auto">
                                            <label for="qty_return" class="block mb-2 text-sm font-medium">Return Quantity</label>
                                            <input wire:keyup.enter='editReturn({{ $item->id }},{{ $item->product->id }},{{ $item->order_id }})' type="number"
                                                id="qty_return" name="qty_return"
                                                wire:model='qty_return'
                                                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                                class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                                                placeholder="input jumlah retur" autofocus="">
                                        </div>
                                        @error('qty_return')
                                            <div class="text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                        <div class="flex items-center justify-end px-4 py-3 border-t gap-x-2">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                                data-hs-overlay="#hs-focus-management-modal-return-{{ $item->id }}">
                                                Cancel
                                            </button>
                                            <button wire:click='editReturn({{ $item->id }},{{ $item->product->id }},{{ $item->order_id }})'
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-400 border border-transparent rounded-lg gap-x-2 hover:bg-red-500 focus:outline-none focus:bg-red-500 disabled:opacity-50 disabled:pointer-events-none">
                                                Return Item
                                            </button>
                                        </div>
                                    </div>
                                    {{-- </form> --}}
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 mb-0 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-slate-900 dark:border-gray-800 dark:text-white">
                <div class="flex justify-between mb-3">
                    <h1 class="font-bold font-3xl text-slate-500">Shipping Address</h1>
                    <h1 class="font-bold font-lg text-slate-500">From <span
                            style=" margin-left:0.5rem;margin-right:0.2rem;"
                            class="text-green-600 fa fa-map-marker"></span> {{ $order->branch->name }}</h1>
                </div>
                <div class="flex items-center justify-between">
                    <div class="pr-2">
                        <p>
                            @if (isset($address->street_address))
                                {{ $address->street_address }},
                                {{ $villages->where('code', $address->village)->value('name') }},
                                {{ $districts->where('code', $address->district)->value('name') }},
                                {{ $cities->where('code', $address->city)->value('name') }},
                                {{ $states->where('code', $address->state)->value('name') }}, {{ $address->zip_code }}
                            @else
                                {{ $user->where('id', $order->user_id)->value('street_address') }},
                                {{ $villages->where('code', $user->where('id', $order->user_id)->value('village'))->value('name') }},
                                {{ $districts->where('code', $user->where('id', $order->user_id)->value('district'))->value('name') }},
                                {{ $cities->where('code', $user->where('id', $order->user_id)->value('city'))->value('name') }},
                                {{ $states->where('code', $user->where('id', $order->user_id)->value('state'))->value('name') }},
                                {{ $user->where('id', $order->user_id)->value('zip_code') }},
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">Cust.Phone:</p>
                        <p>
                            @if (isset($address->phone))
                                <a wire:navigate href="http://wa.me/+62{{ $address->phone }}">{{ $address->phone }}</a>
                            @else
                                <a
                                    href="http://wa.me/+62{{ $user->where('id', $order->user_id)->value('phone') }}">{{ $user->where('id', $order->user_id)->value('phone') }}</a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

        </div>
        <div class="md:w-2/5">
            <div class="p-6 mb-4 bg-white rounded-lg shadow-md dark:bg-slate-900 dark:border-gray-800 dark:text-white">
                <h1 class="mb-3 font-bold font-3xl text-slate-500">Notes</h1>
                <div class="[&>ul]:list-disc [&>ul]:ml-5">
                    <p>
                        @if ($order->notes)
                            {!! Str::markdown($order->notes) !!}
                        @else
                            ...
                        @endif
                    </p>
                </div>
            </div>
            <div class="p-6 bg-white rounded-lg shadow-md dark:bg-slate-900 dark:border-gray-800 dark:text-white">
                <h2 class="mb-4 text-lg font-semibold">Summary</h2>
                <div class="flex justify-between mb-2">
                    <span>Subtotal</span>
                    @if (empty($item))
                    gagal tercatat
                    @else
                    <span>@currency($item->order->grand_total + $item->order->discount - $item->order->shipping_amount)</span>
                    @endif
                </div>
                <div class="flex justify-between mb-2">
                    <span>Discount</span>
                    @if (empty($item))
                    gagal tercatat
                    @else
                    <span>@currency($item->order->discount)</span>
                    @endif
                </div>
                <div class="flex justify-between mb-2">
                    <span>Shipping</span>
                    @if (empty($item))
                    gagal tercatat
                    @else
                    <span>@currency($item->order->shipping_amount)</span>
                    @endif
                </div>
                <hr class="my-2">
                <div class="flex justify-between mb-4">
                    <span class="font-semibold">Grand Total</span>
                    @if (empty($item))
                    gagal tercatat
                    @else
                    <span class="font-semibold">@currency($item->order->grand_total)</span>
                    @endif
                </div>
                <div class="flex justify-between mb-2">
                    <span class="">Payment</span>
                    @if (empty($item))
                    gagal tercatat
                    @else
                    <span class="">@currency($item->order->total_payment)</span>
                    @endif
                </div>
                <div class="flex justify-between mb-2">
                    @if (empty($item))
                    <span>Unpaid</span>
                    @else
                        @if ($item->order->total_cashback >= 0)
                            <span class="">Cashback</span>
                        @else
                            <span class="">Unpaid</span>
                        @endif
                    @endif
                    @if (empty($item))
                    gagal tercatat
                    @else
                    <span class="">@currency($item->order->total_cashback)</span>
                    @endif
                </div>

            </div>

            <div class="grid grid-cols-3 gap-3">
                <a wire:navigate href="/admin/orders/{{ $order->id }}/edit"
                    class="{{ auth()->user()->is_admin == 1 ? 'block' : 'hidden' }}">
                    <button class="w-full p-3 mt-4 text-sm text-white bg-green-500 rounded-lg hover:bg-green-600">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                    </button>
                </a>
                <a href="{{ route('printorder', [$order->id]) }}"
                    class="{{ auth()->user()->is_admin == 1 ? 'block' : 'hidden' }}">
                    <button class="w-full p-3 mt-4 text-sm text-white bg-gray-500 rounded-lg hover:bg-gray-600">
                        <i class="fa fa-print" aria-hidden="true"></i> Print
                    </button>
                </a>
                <a href="{{ route('printorderprocess', [$order->id]) }}"
                    class="{{ auth()->user()->is_admin == 1 ? 'block' : 'hidden' }}">
                    <button class="w-full p-3 mt-4 text-sm text-white bg-gray-500 rounded-lg hover:bg-gray-600">
                        <i class="fa fa-coffee" aria-hidden="true"></i> Print Proses
                    </button>
                </a>
            </div>

        </div>
    </div>

    <h3 class="mt-10 text-xl font-bold text-slate-500 dark:text-white">Payment History
        @if (!empty($order->paid_at))
        <span class="text-sm font-semibold">{{ 'Paid at '.$order->paid_at }}</span>
        @endif
        <button type="button"
            class="bg-white dark:bg-gray-700 py-1 px-2 dark:text-white {{ auth()->user()->is_admin == 1 && $order->is_paid == 0 ? 'inline-flex' : 'hidden' }} items-center gap-x-2 text-sm font-medium border border-transparent bg-transparent text-black hover:text-white hover:bg-yellow-500 dark:hover:bg-yellow-500 focus:outline-none focus:bg-yellow-600 disabled:opacity-50 disabled:pointer-events-none"
            aria-haspopup="dialog" aria-expanded="false"
            aria-controls="hs-focus-management-modal-addpayment-{{ $order->id }}"
            data-hs-overlay="#hs-focus-management-modal-addpayment-{{ $order->id }}">
            +
        </button>
    </h3>

    <div id="hs-focus-management-modal-addpayment-{{ $order->id }}"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1" aria-labelledby="hs-focus-management-modal-addpayment-label">
        <div
            class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
            {{-- <form > --}}
            <div class="flex flex-col bg-white border shadow-sm pointer-events-auto rounded-xl">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 id="hs-focus-management-modal-addpayment-label" class="font-bold text-gray-800">
                        Payment
                    </h3>
                    <button type="button"
                        class="inline-flex items-center justify-center text-gray-800 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none"
                        aria-label="Close" data-hs-overlay="#hs-focus-management-modal-addpayment-{{ $order->id }}">
                        <span class="sr-only">Close</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <label for="payment_date_paid" class="block mb-2 text-sm font-medium">Tanggal Bayar</label>
                    <input wire:keyup.enter='addPayment({{ $order->id }})' type="datetime-local"
                        wire:model='payment_date_paid'
                        class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                        required="">
                </div>
                @error('payment_date_paid')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror
                <div class="p-4 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-2">
                        <label for="payment_cash"
                            class="flex w-full p-3 text-sm bg-white border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">Cash</span>
                            <input value='cash' wire:model='payment_method' type="radio" 
                                name="payment_cash"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="payment_cash">
                        </label>

                        <label for="payment_transfer"
                            class="flex w-full p-3 text-sm bg-white border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">Transfer</span>
                            <input value='transfer' wire:model='payment_method' type="radio"
                                name="payment_transfer"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="payment_transfer">
                        </label>
                    </div>
                </div>
                @error('payment_method')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror
                <div class="p-4 overflow-y-auto">
                    <label for="rekening" class="block mb-2 text-sm font-medium">Rekening</label>
                    <select wire:model='rekening' name="rekening" id="rekening"
                        class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                        required="">
                        {{-- @if ($this->payment_method === "cash") --}}
                            {{-- <option value="">-</option>    
                            <option value="KAS KASIR">KAS KASIR</option>                           --}}
                        {{-- @else --}}
                            {{-- <option value="BANK BSI">BANK BSI</option>
                            <option value="BANK BCA">BANK BCA</option>
                            <option value="BANK BRI">BANK BRI</option>
                            <option value="BANK BNI">BANK BNI</option>
                            <option value="BANK BTN">BANK BTN</option>
                            <option value="BANK MANDIRI">BANK MANDIRI</option>
                            <option value="BANK JATENG">BANK JATENG</option> --}}
                        {{-- @endif --}}
                            <script>
                                let selectKas = {
                                "KAS KASIR": "KAS KASIR",
                                }
                                let selectBank = {
                                "BANK BSI": "BANK BSI",
                                "BANK BCA": "BANK BCA",
                                "BANK BRI": "BANK BRI",
                                "BANK BNI": "BANK BNI",
                                "BANK BTN": "BANK BTN",
                                "BANK MANDIRI": "BANK MANDIRI",
                                "BANK JATENG": "BANK JATENG"
                                }
                                const selectRek = document.getElementById('rekening')
                                document.getElementById("payment_cash").addEventListener("click", (e) => {
                                    selectRek.innerHTML = ''
                                    Object.keys(selectKas).map(key => selectRek.add(new Option(selectKas[key], key)))
                                    });
                                document.getElementById("payment_transfer").addEventListener("click", (e) => {
                                    selectRek.innerHTML = ''
                                    Object.keys(selectBank).map(key => selectRek.add(new Option(selectBank[key], key)))
                                    });

                            </script>
                    </select>
                </div>
                @error('rekening')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror
                <div class="p-4 overflow-y-auto">
                    <label for="input_payment" class="block mb-2 text-sm font-medium">Nominal</label>
                    <input wire:keyup.enter='addPayment({{ $order->id }})' type="alfanumeric"
                        x-mask:dynamic="$money($input, ',', '.')" id="input_payment" name="input_payment"
                        wire:model='input_payment'
                        onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                        class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                        placeholder="Rp 0.000" autofocus="" required="">
                </div>
                @error('input_payment')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror
                {{-- <input type="hidden" wire:model='branch' value="{{ $order->branch->id }}"> --}}
                {{-- <input type="hidden" wire:model='grand_total' value="{{ $item->order->grand_total }}"> --}}
                {{-- <input type="hidden" wire:model='total_payment' value="{{ $item->order->total_payment }}"> --}}
                {{-- <input type="hidden" wire:model='total_cashback' value="{{ $item->order->total_cashback }}"> --}}
                <div class="flex items-center justify-end px-4 py-3 border-t gap-x-2">
                    <button type="button"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                        data-hs-overlay="#hs-focus-management-modal-addpayment-{{ $order->id }}">
                        Close
                    </button>
                    <button wire:click='addPayment({{ $order->id }})'
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-400 border border-transparent rounded-lg gap-x-2 hover:bg-yellow-500 focus:outline-none focus:bg-yellow-500 disabled:opacity-50 disabled:pointer-events-none">
                        Add Payment
                    </button>
                </div>
            </div>
            {{-- </form> --}}
        </div>
    </div>

    <div class="flex flex-col p-5 mt-4 bg-white rounded shadow-md dark:bg-neutral-700">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-start">Method</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Nominal</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Cashback</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase dark:text-white">Date</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-white text-end">Cashier</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($payments->where('mutation_type', 'Sales')->where('paymentable_id', $this->order) as $key => $payment)
                                @php
                                    $status = '';
                                    if ($payment->payment_method == 'cash') {
                                        $status =
                                            '<span class="px-3 py-1 text-white bg-green-500 rounded shadow">Cash</span>';
                                    }
                                    if ($payment->payment_method == 'transfer') {
                                        $status =
                                            '<span class="px-3 py-1 text-white bg-blue-500 rounded shadow">Transfer</span>';
                                    }
                                @endphp
                                <tr wire:key='{{ $payment->id }}'
                                    class="odd:bg-white even:bg-gray-100 hover:bg-yellow-100 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-900"
                                    @auth
                                        @if (auth()->user()->is_admin == 1)
                                            aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal-{{ $payment->id }}" data-hs-overlay="#hs-focus-management-modal-{{ $payment->id }}"
                                        @endif 
                                    @endauth >
                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">{!! $status !!} {{ $payment->rekening }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200 text-end">@currency($payment->nominal_plus)</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200 text-end">@currency($payment->nominal_mins)</td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $payment->date_payment }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200 text-end">{{ $user->where('id', $payment->updated_by)->value('name') }}</td>
                                </tr>
                                <div id="hs-focus-management-modal-{{ $payment->id }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-focus-management-modal-label">
                                    <div class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
                                    {{-- <form > --}}
                                        <div class="flex flex-col bg-white border shadow-sm pointer-events-auto rounded-xl">
                                        <div class="flex items-center justify-between px-4 py-3 border-b">
                                            <h3 id="hs-focus-management-modal-label" class="font-bold text-gray-800">
                                            Payment {{ $payment->date_payment }}
                                            </h3>
                                            <button type="button" class="inline-flex items-center justify-center text-gray-800 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#hs-focus-management-modal-{{ $payment->id }}">
                                            <span class="sr-only">Close</span>
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d=" M18 6 6 18">
                                                        </path>
                                                        <path d="m6 6 12 12"></path>
                                                        </svg>
                                                        </button>
                                    </div>
                                    <div class="p-4 overflow-y-auto">
                                        <label for="input_payment" class="block mb-2 text-sm font-medium">Tanggal Bayar (wajib isi)</label>
                                        <input wire:keyup.enter='editPayment({{ $payment->id }})' type="datetime-local"
                                            wire:model='payment_date_paid_edit'
                                            class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                                            required="">
                                    </div>
                                    @error('payment_date_paid_edit')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                    <div class="p-4 overflow-y-auto hidden">
                                        <div class="grid grid-cols-2 gap-2">
                                            <label for="payment_cash_{{ $payment->id }}"
                                                class="flex w-full p-3 text-sm bg-white border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                                <span class="text-sm text-gray-500 dark:text-neutral-400">Cash</span>
                                                <input value='cash' wire:model='payment_method_edit' type="radio"
                                                    name="payment_cash_{{ $payment->id }}"
                                                    class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                    id="payment_cash_{{ $payment->id }}" class="paymentcashedit">
                                            </label>

                                            <label for="payment_transfer_{{ $payment->id }}"
                                                class="flex w-full p-3 text-sm bg-white border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                                <span class="text-sm text-gray-500 dark:text-neutral-400">Transfer</span>
                                                <input value='transfer' wire:model='payment_method_edit' type="radio"
                                                    name="payment_transfer_{{ $payment->id }}"
                                                    class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                    id="payment_transfer_{{ $payment->id }}" class="paymenttransferedit">
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_method_edit')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                    <div class="p-4 overflow-y-auto">
                                        <label for="rekening_edit" class="block mb-2 text-sm font-medium">Rekening (wajib isi)</label>
                                        <select
                                            wire:model='rekening_edit' name="rekening_edit" id="rekening_edit"
                                            class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                                            required="">
                                            {{-- @if ($this->payment_method === "cash") --}}
                                                <option value="">-</option>    
                                                <option value="KAS KASIR">KAS KASIR</option>                          
                                            {{-- @else --}}
                                                <option value="BANK BSI">BANK BSI</option>
                                                <option value="BANK BCA">BANK BCA</option>
                                                <option value="BANK BRI">BANK BRI</option>
                                                <option value="BANK BNI">BANK BNI</option>
                                                <option value="BANK BTN">BANK BTN</option>
                                                <option value="BANK MANDIRI">BANK MANDIRI</option>
                                                <option value="BANK JATENG">BANK JATENG</option>
                                            {{-- @endif --}}
                                        </select>
                                    </div>
                                    @error('rekening_edit')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                    <div class="p-4 overflow-y-auto">
                                        <label for="input_payment_edit" class="block mb-2 text-sm font-medium">Nominal (wajib isi)</label>
                                        <input wire:keyup.enter='editPayment({{ $payment->id }})' type="alfanumeric"
                                            x-mask:dynamic="$money($input, ',', '.')" id="input_payment_edit" name="input_payment_edit"
                                            wire:model='input_payment_edit'
                                            onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                            class="block w-full px-4 py-3 text-sm text-center border-gray-200 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                                            placeholder="@currency($payment->nominal_plus)" autofocus="">
                                    </div>
                                    @error('input_payment_edit')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                    {{-- <input type="hidden" wire:model='branch' value="{{ $order->branch->id }}"> --}}
                                    {{-- <input type="hidden" wire:model='grand_total' value="{{ $item->order->grand_total }}"> --}}
                                    {{-- <input type="hidden" wire:model='total_payment' value="{{ $item->order->total_payment }}"> --}}
                                    {{-- <input type="hidden" wire:model='total_cashback' value="{{ $item->order->total_cashback }}"> --}}
                                    <div class="flex items-center justify-end px-4 py-3 border-t gap-x-2">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                            data-hs-overlay="#hs-focus-management-modal-{{ $payment->id }}">
                                            Cancel
                                        </button>
                                        <button wire:click='editPayment({{ $payment->id }})'
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-400 border border-transparent rounded-lg gap-x-2 hover:bg-yellow-500 focus:outline-none focus:bg-yellow-500 disabled:opacity-50 disabled:pointer-events-none">
                                            Edit Payment
                                        </button>
                                    </div>
                                </div>
                                {{-- </form> --}}
                            </div>
                        </div>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
