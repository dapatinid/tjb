<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Attributes\Url;

#[Title('Items Return')]
class ItemsReturnPage extends Component
{
    use WithPagination;
    // protected $paginationTheme = 'bootstrap';

    #[Url()]
    public $date_awal = '';
    #[Url()]
    public $date_akhir = '';

    public function mount()
    {
        $isadmin = auth()->user()->is_admin;
        if ($isadmin == 0) {
            return redirect('/my-orders');
        }
    }

    public function render()
    {

        if ($this->date_awal == '' || $this->date_akhir == '') {
            $date_awal = Carbon::now()->firstOfYear()->format('Y-m-d');
            $date_akhir = Carbon::now()->format('Y-m-d');
            // $date_awal = Carbon::now()->startOfMonth()->format('Y-m-d');
            // $date_akhir = Carbon::now()->endOfMonth()->format('Y-m-d');
            // } else {
            //     $date_awal = $this->date_awal;
            //     $date_akhir = $this->date_akhir;
            $this->date_awal = $date_awal;
            $this->date_akhir = $date_akhir;
        }

        $orders = Order::where('branch_id', auth()->user()->branch_id)->get();
        $products = Product::where('branch_id', auth()->user()->branch_id)->get();
        $itemsreturn = OrderItem::where('branch_id', auth()->user()->branch_id)
            ->where('quantity', '<', '0')
            ->whereBetween('updated_at', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->where('status', '!=', 'canceled');

        return view('livewire.items-return-page', [
            'orders' => $orders,
            'products' => $products,
            'itemsreturn' => $itemsreturn->paginate(25),
        ]);
    }
}
