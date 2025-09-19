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
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; margin-bottom: 2em; padding-top: 2rem; padding-left: 1rem; padding-right: 1rem">
            <div style="text-align: center;">
                {{-- <img src="{{ url('storage/Taibah-FC-LOGO-bulat-aja-w-Stroke.png') }}" alt="ZaharaPOS" width="100" /> --}}
                <h4>Delivery Order No. {{ $order->q }}</h4>
                <h4 style="margin-top: -12px;">{{ $order->code_tr }}</h4>
            </div>

            <div style="justify-content: space-between; display: flex; "><span>Dibuat : </span><span>{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y H:i:s') }}</span></div>
                <div style="justify-content: space-between; display: flex; "><span>Utk Tgl : </span><span>{{ \Carbon\Carbon::parse($order->date_order)->translatedFormat('d M Y H:i') }} Antri {{ $order->q }}</span></div>
                <div style="justify-content: space-between; display: flex; "><span>Oleh : </span><span>{{ App\Models\User::find($order->created_by)->name }}</span></div>
                <div style="justify-content: space-between; display: flex; "><span>Pembeli : </span><span>{{ $order->user->id == 2 ? 'Customer Umum' : $order->user->name }}</span></div>

                    <div style="text-align: right; ">
                        {{ $user->value('phone') }} {{ $user->value('street_address') }}
                        {{ $villages->where('code',$user->value('village'))->value('name') }},
                        {{ $districts->where('code',$user->value('district'))->value('name') }},
                        {{ Illuminate\Support\Str::after($cities->where('code',$user->value('city'))->value('name'),'KABUPATEN ') }}
                        {{-- {{ $states->where('code',$user->value('state'))->value('name') }},<br>
                        {{ $user->value('zip_code') }} --}}
                    </div>

                    <div style="text-align: right; ">
                    @if ($address->value('first_name') != null || $address->value('last_name') != null)
                        {{ $address->value('first_name') }} {{ $address->value('last_name') }}  
                    @endif
                    @if ($address->value('phone') != $user->value('phone'))
                        {{ $address->value('phone') }}
                    @endif
                    @if ($address->value('street_address') != $user->value('street_address'))
                        {{ $address->value('street_address') }}
                    @endif
                    @if ($address->value('village') != $user->value('village') || $address->value('district') != $user->value('district') || $address->value('city') != $user->value('city'))
                        {{ $villages->where('code',$address->value('village'))->value('name') }},
                        {{ $districts->where('code',$address->value('district'))->value('name') }},
                        {{ Illuminate\Support\Str::after($cities->where('code',$address->value('city'))->value('name'),'KABUPATEN ') }}
                    @endif
                        {{-- {{ $states->where('code',$address->value('state'))->value('name') }},<br>
                        {{ $address->value('zip_code') }} --}}
                    </div>

    <div style="margin-top: 1rem; ">
        {{-- <div style="text-align: center; margin-bottom: 12px;"><b>order items</b></div> --}}
        <div style="justify-content: space-between; display: flex;  margin-bottom: 12px;"><span>No  |  Item Name</span> <span style="">QTY</span></div>
        @foreach ($orderitems as $orderitem)
        <div class="items" style="margin-bottom: 12px;">
            <div style="justify-content: space-between; display: flex; "><span>#{{$loop->iteration}} {{ $orderitem->product->name }} {{ $orderitem->product->variant }}</span> <span style="font-size: 24px;">{{ $orderitem->quantity }}</span></div>

            @if($orderitem->contain != '')
            @php 
              $contains = Str::of($orderitem->contain)->explode(',')
            @endphp
            @foreach ($contains as $contain)
            @php
                $qtyContain = Str::between($contain, '(', ')');
                if (is_numeric($qtyContain)) {
                    $qtyCo = $qtyContain * $orderitem->quantity;
                } else {
                    $qtyCo = '__';
                }
                
            @endphp
            <div style="margin-top: 6px; justify-content: space-between; display: flex;"><span style="margin-left: 1em;">{{ $contain }}</span><span>x{{ $orderitem->quantity }} = {{$qtyCo}}</span></div>
            @endforeach
            @endif
        
        </div>
        @endforeach
    </div>
 <hr size="3" color="black" style="border-top: dotted 1px;">

 <div style="justify-content: space-between; display: flex; "><span>Berat</span><span>{{ substr($order->total_weight, 0, -3) }}kg</span></div>

 <script type = 'text/javascript'>  
    window.onload = function(){ window.print(); }
</script>
</body>
</html>