<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>

<style>
 
</style>

</head>
<body style="font-size: 18px; margin-bottom: 2em; padding-top: 1rem; padding-left: 1rem; padding-right: 1rem">
            {{-- <div style="text-align: center;">
                @if (isset($branchLogo))
                <img src="{{ url('storage/'.$branchLogo) }}" alt="TegarJaya" width="100" />
                @endif
                <h4>{{ $branchPartnerName }}</h4>
                <h4 style="margin-top: -12px">{{ $branchName }}</h4>
                <h4 style="margin-top: -12px">{{ $branchPhone }}</h4>
            </div> --}}
            <div>
                <div style="justify-content: center; display: flex; ">{{ $order->code_tr }}</div>
                <div style="justify-content: space-between; display: flex; "><span>Dibuat : </span><span>{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y H:i:s') }}</span></div>
                <div style="justify-content: space-between; display: flex; "><span>Utk Tgl : </span><span>{{ \Carbon\Carbon::parse($order->date_order)->translatedFormat('d M Y H:i') }} Antri {{ $order->q }}</span></div>
                <div style="justify-content: space-between; display: flex; "><span>Oleh : </span><span>{{ App\Models\User::find($order->created_by)->name }}</span></div>
                <div style="justify-content: space-between; display: flex; "><span>Pembeli : </span><span>{{ $order->user->id == 2 ? 'Customer Umum' : $order->user->name }}</span></div>

                    @if ($address->value('street_address') != null)
                    <div style="text-align: right; ">
                        {{ $address->value('first_name') }} {{ $address->value('last_name') }} {{ $address->value('phone') }} {{ $address->value('street_address') }}
                        {{ $villages->where('code',$address->value('village'))->value('name') }},
                        {{ $districts->where('code',$address->value('district'))->value('name') }},
                        {{ Illuminate\Support\Str::after($cities->where('code',$address->value('city'))->value('name'),'KABUPATEN ') }}
                        {{-- {{ $states->where('code',$address->value('state'))->value('name') }},<br>
                        {{ $address->value('zip_code') }} --}}
                    </div>
                    @else
                    <div style="text-align: right; ">
                        {{ $user->value('phone') }} {{ $user->value('street_address') }}
                        {{ $villages->where('code',$user->value('village'))->value('name') }},
                        {{ $districts->where('code',$user->value('district'))->value('name') }},
                        {{ Illuminate\Support\Str::after($cities->where('code',$user->value('city'))->value('name'),'KABUPATEN ') }}
                        {{-- {{ $states->where('code',$user->value('state'))->value('name') }},<br>
                        {{ $user->value('zip_code') }} --}}
                    </div>
                    @endif

            </div>
             <br> 
    <div class="margin-top">
        @foreach ($orderitems as $orderitem)
        <div style="display: flex; justify-content: space-between;  align-items: center;">
            <span style="display: grid; grid-template-columns: auto auto; align-items: center;">
                <span style="font-size: 24px;">{{ $orderitem->quantity }}</span>
                <span style="margin-left: 5px; text-align: left;"> {{ $orderitem->product->name }} {{ $orderitem->product->variant }} {{ '@'.Illuminate\Support\Number::abbreviate($orderitem->unit_amount) }}</span>
            </span>
            <span style="text-align: right;">{{ substr($orderitem->total_amount, 0, -3) }}rb</span>
        </div>
        @endforeach
    </div>
 <br>
    <div class="total">
        <div style="justify-content: space-between; display: {{ $order->grand_total + $order->discount - $order->shipping_amount == $order->grand_total ? 'none' : 'flex'}}; "><span>Sub : </span><span>Rp @formatNumber($order->grand_total + $order->discount - $order->shipping_amount)</span></div>
        <div style="justify-content: space-between; display: {{ $order->discount == 0 ? 'none' : 'flex'}}; "><span>Diskon : </span><span>Rp @formatNumber($order->discount)</span></div>
        <div style="justify-content: space-between; display: {{ $order->shipping_amount == 0 ? 'none' : 'flex'}}; "><span>Ongkir : </span><span>Rp @formatNumber($order->shipping_amount)</span></div>
        <div style="justify-content: space-between; display: flex; "><span>Total : </span><span>Rp @formatNumber($order->grand_total)</span></div>
    </div>
 <hr size="3" color="black">
    <div style="justify-content: space-between; display: {{ $order->total_payment == 0 ? 'none' : 'flex'}}; "><span>Bayar</span><span>Rp @formatNumber($order->total_payment)</span></div>
    <div style="justify-content: space-between; display: flex; ">
        @if ( $order->total_cashback < 0)
        <span>Belum Bayar : </span>
        @else
        <span>Kembali : </span>
        @endif 
        <span>Rp @formatNumber($order->total_payment - $order->grand_total)</span>
    </div>
    <div style="justify-content: space-between; display: {{ $order->total_payment == 0 ? 'none' : 'flex'}}; "><span>Berat</span><span>{{ substr($order->total_weight, 0, -3) }}kg</span></div>
    <div style="justify-content: space-between; display: {{ $order->notes == null ? 'none' : 'flex'}}; "><span>Catatan </span><span style="text-align: right;">{{ $order->notes }}</span></div>
 {{-- <br>
    <div style="text-align: center;">
        <div>Terima Kasih</div>
        <div>Aplikasi &copy; TegarJaya</div>
        <div>089684561000</div>
    </div> --}}

    <script type = 'text/javascript'>  
        window.onload = function(){ window.print(); }
    </script>
</body>
</html>