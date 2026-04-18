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

    #[Url()]
    public $date_awal = '';
    #[Url()]
    public $date_akhir = '';

    public function mount()
    {
        // Proteksi Admin
        if (auth()->user()->is_admin == 0) {
            return redirect('/my-orders');
        }

        // Set default tanggal saat pertama kali load jika URL kosong
        if (!$this->date_awal) {
            $this->date_awal = Carbon::now()->format('Y-m-d');
        }
        if (!$this->date_akhir) {
            $this->date_akhir = Carbon::now()->format('Y-m-d');
        }
    }

    public function render()
    {
        // Query Payments dengan filter tanggal
        $payments = Payment::where('paymentable_type', Order::class)
        ->where('nominal', '!=', 0)
            ->where('mutation_type', "Sales")
            ->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->paginate(12);

        // Query Unpaid Orders
        $ordersUnpaid = Order::whereNull('deleted_at')
            ->where('status', '!=', 'canceled')
            ->where('total_cashback', '<', 0) // Pastikan hanya yang minus (piutang)
            ->orderBy('id', 'desc');

        // Ambil User & Order untuk helper di view (opsional, sebaiknya gunakan relasi model)
        $users = User::all();

        return view('livewire.payments-page', [
            'payments' => $payments,
            'ordersUnpaid' => $ordersUnpaid->paginate(12, ['*'], 'unpaidPage'), // Pagination terpisah
            'users' => $users,
        ]);
    }
}