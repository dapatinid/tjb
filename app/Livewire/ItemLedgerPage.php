<?php

namespace App\Livewire;

use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Carbon\Carbon;

#[Title('Item Ledger')]
class ItemLedgerPage extends Component
{
    #[Url()]
    public $date_awal = '';

    #[Url()]
    public $date_akhir = '';

    #[Url()]
    public $product_id = '';

    public $search = '';

    public function mount()
    {
        if (auth()->user()->is_admin == 0) {
            return $this->redirect('/my-orders', navigate: true);
        }

        if ($this->date_awal === '') {
            // $this->date_awal = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $this->date_awal = '2025-01-01';
        }
        if ($this->date_akhir === '') {
            $this->date_akhir = Carbon::now()->format('Y-m-d');
        }
    }

    public function selectProduct($id)
    {
        $this->product_id = $id;
        $this->search = '';
    }

    public function render()
    {
        $branchId = auth()->user()->branch_id;

        // Dropdown: cari produk berdasarkan search
        $searchResults = collect();
        if (strlen($this->search) >= 2) {
            $searchResults = Product::where('branch_id', $branchId)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('variant', 'like', '%' . $this->search . '%');
                })
                ->select('id', 'name', 'variant', 'images', 'low_alert')
                ->limit(10)
                ->get();
        }

        // Produk yang dipilih
        $product = null;
        $orderItems = collect();
        $summary = null;

        if ($this->product_id) {
            $product = Product::select('id', 'name', 'variant', 'images', 'low_alert', 'unit_name')
                ->find($this->product_id);

            if ($product) {
                $rows = OrderItem::where('branch_id', $branchId)
                    ->where('product_id', $this->product_id)
                    ->where('status', '!=', 'canceled')
                    ->whereBetween('date_order', [
                        $this->date_awal  . ' 00:00:00',
                        $this->date_akhir . ' 23:59:59',
                    ])
                    ->select('product_id', 'quantity', 'p_quantity', 'mutation_type', 'status')
                    ->get();

                // Hitung ringkasan di PHP (ringan karena sudah 1 produk)
                $summary = [
                    'beli'       => 0,
                    'jual'       => 0,
                    'prod'       => 0,   // ProdPlus - ProdMins
                    'adj'        => 0,   // AdjPlus - AdjMins
                    'tf_out'     => 0,
                    'tf_in'      => 0,
                    'stok'       => 0,
                    'stok_gudang'=> 0,
                    'tot_in_new' => 0,
                    'tot_out_new'=> 0,
                    'tot_in_tf'  => 0,
                    'tot_out_tf' => 0,
                ];

                foreach ($rows as $item) {
                    $q  = (float) $item->quantity;
                    $pq = (float) $item->p_quantity;

                    match ($item->mutation_type) {
                        'Purchase'     => $summary['beli']   += $pq,
                        'Sales'        => $summary['jual']   += $q,
                        'Transfer Out' => $summary['tf_out'] += $q,
                        'Transfer In'  => $summary['tf_in']  += $pq,
                        'Production'   => ($summary['prod']  += ($pq - $q)),
                        'Adjusment'    => ($summary['adj']   += ($pq - $q)),
                        default        => null,
                    };

                    if ($item->status === 'new') {
                        $summary['tot_in_new']  += $pq;
                        $summary['tot_out_new'] += $q;
                    }
                    if ($item->status === 'transfering') {
                        $summary['tot_in_tf']  += $pq;
                        $summary['tot_out_tf'] += $q;
                    }
                }

                $summary['stok'] = $summary['beli'] - $summary['jual']
                    - $summary['tf_out'] + $summary['tf_in']
                    + $summary['prod'] + $summary['adj'];

                $summary['stok_gudang'] = $summary['stok']
                    - $summary['tot_in_new']  - $summary['tot_in_tf']
                    + $summary['tot_out_new'] + $summary['tot_out_tf'];
            }
        }

        return view('livewire.item-ledger-page', [
            'searchResults' => $searchResults,
            'product'       => $product,
            'summary'       => $summary,
        ]);
    }
}