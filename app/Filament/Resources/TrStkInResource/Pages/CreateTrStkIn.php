<?php

namespace App\Filament\Resources\TrStkInResource\Pages;

use App\Filament\Resources\TrStkInResource;
use App\Models\OrderItem;
use App\Models\TrStkOut;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateTrStkIn extends CreateRecord
{
    protected static string $resource = TrStkInResource::class;

    protected function afterCreate(): void
    {
        // Update siapa yang BUAT
        $record = $this->record;
        $outID = TrStkOut::where('code_tr', Str::replace('TRI', 'TRO', $record->code_tr))->value('id');
        OrderItem::where('tr_stk_in_id', $record->id)->update(['created_by' => Auth::user()->id, 'status' => $record->status, 'date_order' => $record->date_order]);
        OrderItem::where('tr_stk_out_id', $outID)->update(['updated_by' => Auth::user()->id, 'status' => $record->status, 'date_order' => $record->date_order]);
        // Payment::where('paymentable_type', TrStkIn::class)->where('paymentable_id', $record->id)->update([
        //     'debit' => 'NR-DB-B-2000 Persediaan Barang Dagang',
        //     'kredit' => 'NR-KR-D-2500 Barang Transfer',
        // ]);
    }
}
