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
                <h4>Transfer No. {{ $trSTK->code_tr }}</h4>
                <h4 style="margin-top: -12px;">Dari {{ $branch->find($trSTK->from_branch_id)->name }}</h4>
                <h4 style="margin-top: -12px;">Kepada {{ $branch->find($trSTK->to_branch_id)->name }}</h4>
            </div>
    <div class="">
        {{-- <div style="text-align: center; margin-bottom: 12px;"><b>order items</b></div> --}}
        <div style="justify-content: space-between; display: flex;  margin-bottom: 12px;">
            <span>No  |  Item Name</span> 
            <span style="">QTY</span>
            <span style="">W</span>
        </div>
        @foreach ($orderitems as $orderitem)
        <div class="items" style="margin-bottom: 12px;">
            <div style="justify-content: space-between; display: flex; ">
                <span>#{{$loop->iteration}} {{ $orderitem->product->name }} {{ $orderitem->product->variant }}</span> 
                <span style="font-size: 24px;">{{ $orderitem->quantity }}</span>
                <span style="font-size: 24px;">{{ substr($orderitem->total_weight, 0, -3) }}</span>
            </div>

            {{-- @if($orderitem->contain != '')
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
            @endif --}}
        
        </div>
        @endforeach
    </div>
 <hr size="3" color="black" style="border-top: dotted 1px;">

    <div style="justify-content: space-between; display: flex; "><span>Berat</span><span>{{ substr($trSTK->total_weight, 0, -3) }}kg</span></div>

 <script type = 'text/javascript'>  
    window.onload = function(){ window.print(); }
</script>
</body>
</html>