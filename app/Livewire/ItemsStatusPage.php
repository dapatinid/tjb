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

#[Title('Items Status')]
class ItemsStatusPage extends Component
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

    // public function updatedStatus()
    // {
    //         $this->dispatch('refreshPage');
        
    // }
    
    public function render()
    {
        if ($this->date_awal == '') {
            $date_awal = Carbon::now()->firstOfYear()->format('Y-m-d');
            $this->date_awal = $date_awal;
        }
        if ($this->date_akhir == '') {            
            $date_akhir = Carbon::now()->format('Y-m-d');
            $this->date_akhir = $date_akhir;
        }

        $orderItems = OrderItem::with('product')->where('branch_id', auth()->user()->branch_id)->orderBy('product_id', 'asc')->where('status', '!=', 'canceled')->whereBetween('date_order', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->get();

        return view('livewire.items-status-page', [
            'orderItems' => $orderItems,
        ]);
    }
}
