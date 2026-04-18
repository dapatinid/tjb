<?php

namespace App\Livewire;

use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Url;

#[Title('Items Status')]
class ItemsStatusPage extends Component
{
    #[Url()]
    public $date_awal = '';

    #[Url()]
    public $date_akhir = '';

    public function mount()
    {
        if (auth()->user()->is_admin == 0) {
            return $this->redirect('/my-orders', navigate: true);
        }

        // ✅ Default tanggal awal = awal bulan ini, akhir = hari ini
        if ($this->date_awal === '') {
            $this->date_awal = Carbon::now()->firstOfMonth()->format('Y-m-d');
        }
        if ($this->date_akhir === '') {
            $this->date_akhir = Carbon::now()->format('Y-m-d');
        }
    }

    public function render()
    {
        $orderItems = OrderItem::with([
                // ✅ Eager load hanya kolom yang dibutuhkan JS di blade
                'product:id,name,variant,low_alert',
            ])
            ->where('branch_id', auth()->user()->branch_id)
            ->where('status', '!=', 'canceled')
            ->whereBetween('date_order', [
                $this->date_awal  . ' 00:00:00',
                $this->date_akhir . ' 23:59:59',
            ])
            // ✅ Hanya ambil kolom yang benar-benar dipakai JS
            ->select([
                'product_id',
                'quantity',
                'p_quantity',
                'mutation_type',
                'status',
            ])
            ->orderBy('product_id')
            ->get();

        return view('livewire.items-status-page', [
            'orderItems' => $orderItems,
        ]);
    }
}