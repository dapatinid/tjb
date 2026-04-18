<div class="w-full max-w-[85rem] py-10 px-4 mx-auto">
    <h1 class="text-2xl font-bold text-center text-slate-500">
        @if ($isadmin == 1)
            Orders | Paid Today <br>
            <span class="text-lg">
                Cash Rp {{ number_format($paymentcash - $my_orders_sum_cashback, 0, ',', '.') }} | 
                Tf Rp {{ number_format($paymenttf, 0, ',', '.') }}
            </span>
        @else
            My Orders: Rp {{ number_format($my_orders_sum, 0, ',', '.') }}
        @endif
    </h1>

    <div class="flex flex-wrap mt-6">
        @forelse ($orders as $order)
            @php
                $statusColor = match($order->status) {
                    'new' => 'bg-blue-500',
                    'processing' => 'bg-yellow-500',
                    'shipped' => 'bg-orange-500',
                    'delivered' => 'bg-green-500',
                    'canceled' => 'bg-red-500',
                    default => 'bg-gray-500'
                };
                
                // Cegah error 500 jika address null
                $nama = $order->address ? ($order->address->first_name . ' ' . $order->address->last_name) : 'No Name';
                $telp = $order->address->phone ?? '';
                $method = $paymentlast->where('paymentable_id', $order->id)->first()->payment_method ?? '-';
            @endphp

            <div wire:key="{{ $order->id }}" class="w-full p-2 lg:w-1/3 sm:w-1/2">
                <div class="bg-white border rounded-lg shadow-sm p-4 dark:bg-neutral-800">
                    <div class="flex justify-between border-b pb-2 mb-2 dark:text-white">
                        <span class="font-bold">#{{ $order->code_tr }}</span>
                        <span class="text-sm truncate max-w-[150px]">{{ $order->user->name }}</span>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="px-2 py-0.5 text-xs text-white rounded {{ $statusColor }}">{{ strtoupper($order->status) }}</span>
                            <span class="text-xs font-bold dark:text-gray-400">{{ $order->is_paid ? 'PAID' : 'UNPAID' }} ({{ strtoupper($method) }})</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ $order->date_order }}</span>
                            <span class="font-bold dark:text-gray-400">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <a href="/my-orders/{{ $order->id }}" class="flex-1 bg-blue-600 text-white text-center py-1 rounded text-sm">Detail</a>
                        @if($isadmin == 1)
                            <button wire:click="changeStatus({{ $order->id }})" class="flex-1 bg-gray-200 text-gray-800 py-1 rounded text-sm">Ubah Status</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="w-full text-center py-20 text-gray-500 italic">Belum ada pesanan.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>