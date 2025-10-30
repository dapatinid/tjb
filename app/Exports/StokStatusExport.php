<?php

namespace App\Exports;

use App\Models\Journal;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokStatusExport implements FromCollection, WithMapping, WithHeadings
{
    protected $bydate;

    function __construct($byfilter)
    {
        $byfilter = Str::of($byfilter)->explode('&');
        $bydateawal = Str::after($byfilter[0], '=');
        $bydateakhir = Str::after($byfilter[1], '=');
        $this->bydateawal = $bydateawal;
        $this->bydateakhir = $bydateakhir;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Stok Tanpa New',
            'Stok',
            'STATUS',
            'Terbeli',
            'Terjual',
            'Prod',
            'Adj',
            'Tf-Out',
            'Tf-In',
        ];
    }

    public function map($product): array
    {
        $OrderItem = OrderItem::all()->where('branch_id', Auth::user()->branch_id);
        $Terbeli = $OrderItem->where('product_id',$product->id)->whereNotNull('porder_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('p_quantity');
        $Terjual = $OrderItem->where('product_id',$product->id)->whereNotNull('order_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('quantity');
        $ProdPlus = $OrderItem->where('product_id',$product->id)->whereNotNull('production_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('p_quantity');
        $ProdMins = $OrderItem->where('product_id',$product->id)->whereNotNull('production_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('quantity');
        $AdjPlus = $OrderItem->where('product_id',$product->id)->whereNotNull('adj_item_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('p_quantity');
        $AdjMins = $OrderItem->where('product_id',$product->id)->whereNotNull('adj_item_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('quantity');
        $TfOUT = $OrderItem->where('product_id',$product->id)->whereNotNull('tr_stk_out_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('quantity');
        $TfIN = $OrderItem->where('product_id',$product->id)->whereNotNull('tr_stk_in_id')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('p_quantity');

        $stIN = $OrderItem->where('product_id',$product->id)->where('status', '!=', 'new')->where('status', '!=', 'transfering')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('p_quantity');
        $stOUT = $OrderItem->where('product_id',$product->id)->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('quantity');
        $stOUTTanpaNew = $OrderItem->where('product_id',$product->id)->where('status', '!=', 'new')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->sum('quantity');
        $status = (($stIN - $stOUT) > $product->low_alert) ? 'aman' : 'LOW' ;
        return [
            $product->id,
            $product->name,
            $stIN - $stOUTTanpaNew,
            $stIN - $stOUT,
            $status,
            $Terbeli,
            $Terjual*-1,
            $ProdPlus - $ProdMins,
            $AdjPlus - $AdjMins,            
            $TfOUT*-1,
            $TfIN,

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->bydate != null) {
            return Product::where('branch_id', Auth::user()->branch_id)->orderBy('name', 'asc')->get();
            // return Product::where('branch_id', Auth::user()->branch_id)->orderBy('name', 'asc')->whereBetween('date_order', [$this->bydateawal . ' 00:00:00', $this->bydateakhir . ' 23:59:59'])->get();
        } else {
            return Product::where('branch_id', Auth::user()->branch_id)->orderBy('name', 'asc')->get();
        }
    }
}
