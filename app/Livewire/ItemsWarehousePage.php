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
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('Items Warehouse')]
class ItemsWarehousePage extends Component
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

    public function exportTabelStokLow()
    {
        if ($this->date_awal == null || $this->date_akhir == '') {
            LivewireAlert::title('Tanggal Kosong')
                ->show();
        } else {
            return redirect()->route('exporttabelstoklow', http_build_query(array(
                'date_awal' => $this->date_awal,
                'date_akhir' => $this->date_akhir,
            )));
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

        $products = Product::where('branch_id', auth()->user()->branch_id)->orderBy('name', 'asc')->paginate(25);

        return view('livewire.items-warehouse-page', [
            'products' => $products,
        ]);
    }
}
