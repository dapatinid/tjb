<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Payments')]
class PaymentsPage extends Component
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
            // $date_awal = Carbon::now()->firstOfMonth()->format('Y-m-d');
            // $date_akhir = Carbon::now()->format('Y-m-d');
            $date_awal = Carbon::now()->format('Y-m-d');
            $date_akhir = Carbon::now()->format('Y-m-d');
            // $date_awal = Carbon::now()->startOfMonth()->format('Y-m-d');
            // $date_akhir = Carbon::now()->endOfMonth()->format('Y-m-d');
            // } else {
            //     $date_awal = $this->date_awal;
            //     $date_akhir = $this->date_akhir;
            $this->date_awal = $date_awal;
            $this->date_akhir = $date_akhir;
        }

        $orders = Order::all();
        $users = User::all();

        $payments = Payment::where('paymentable_type', Order::class)->where('mutation_type', "Sales")
            ->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->paginate(12);

        $ordersUnpaid = Order::whereNull('deleted_at')
                    ->where('status', '!=', 'canceled')
                    ->orderBy('id', 'desc')
                    ;


        return view('livewire.payments-page', [
            'orders' => $orders,
            'payments' => $payments,
            'ordersUnpaid' => $ordersUnpaid,
            'users' => $users,
        ]);
    }
}
