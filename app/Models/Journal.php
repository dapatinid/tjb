<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Journal extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'paymentable_id',
        'paymentable_type',

        'notes',
        'journal_type',
        'mutation_type',

        'debit',
        'kredit',
        'currency',
        'payment_method',
        'nominal',

        'from_rekening',
        'to_rekening',

        'created_by',
        'updated_by',
        'branch_id',
        'to_branch_id',
        'date_journal',
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userCre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function userUpd(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->update(['updated_by' => auth()->user()->id,]);
        });
        static::deleted(function ($model) {
            if ($model->isForceDeleting()) {
                $model->payments()->withTrashed()->forceDelete();
            } else {
                $model->payments()->delete();
            }
        });
        static::restored(function ($model) {
            $model->payments()->withTrashed()->restore();
        });


        static::created(function ($model) {
            if ($model->journal_type == 'tf') {
                $mutation_type = 'tf saldo';
            } elseif ($model->journal_type == 'ps') {
                $mutation_type = 'ps saldo';
            } elseif ($model->journal_type == 'sa') {
                $mutation_type = 'sa saldo';
            } else {
                $mutation_type = $model->mutation_type;
            }

            $cabangPenerimaNominal = ($model->journal_type == 'tf') ? $model->to_branch_id : $model->branch_id;

            $ledgerDebit = new Payment();
            $ledgerDebit->mutation_type = $mutation_type;
            $ledgerDebit->debit = $model->debit;
            $ledgerDebit->kredit = ($model->journal_type == 'tf') ? 'NR-KR-D-3000 Nominal Transfer' : null;
            $ledgerDebit->currency = $model->currency;
            $ledgerDebit->nominal_plus = $model->nominal;
            $ledgerDebit->nominal_mins = 0;
            $ledgerDebit->nominal = $model->nominal;

            $rekeningnyaDebit = ($model->debit == 'NR-DB-B-1100 CASH / BANK') ? $model->from_rekening : null;
            $rekeningnyaDebit = ($model->journal_type == 'tf') ? $model->to_rekening : $rekeningnyaDebit;
            $ledgerDebit->rekening = $rekeningnyaDebit;
            $ledgerDebit->payment_method = $model->payment_method;
            $ledgerDebit->user_id = null;

            $ledgerDebit->user_id = auth()->user()->id;
            $ledgerDebit->created_by = $model->created_by;
            $ledgerDebit->updated_by = $model->updated_by;
            $ledgerDebit->branch_id = $cabangPenerimaNominal;
            $ledgerDebit->date_payment = $model->date_journal;

            $ledgerDebit->paymentable_id = $model->id;
            $ledgerDebit->paymentable_type = Journal::class;
            $ledgerDebit->save();

            $ledgerKredit = new Payment();
            $ledgerKredit->mutation_type = $mutation_type;
            $ledgerKredit->debit = ($model->journal_type == 'tf') ? 'NR-KR-D-3000 Nominal Transfer' : null;
            $ledgerKredit->kredit = $model->kredit;
            $ledgerKredit->currency = $model->currency;
            $ledgerKredit->nominal_plus = 0;
            $ledgerKredit->nominal_mins = $model->nominal;
            $ledgerKredit->nominal = $model->nominal;

            $rekeningnyaKredit = ($model->kredit == 'NR-DB-B-1100 CASH / BANK') ? $model->from_rekening : null;
            $rekeningnyaKredit = ($model->journal_type == 'tf') ? $model->from_rekening : $rekeningnyaKredit;
            $ledgerKredit->rekening = $rekeningnyaKredit;
            $ledgerKredit->payment_method = $model->payment_method;
            $ledgerKredit->user_id = null;

            $ledgerKredit->user_id = auth()->user()->id;
            $ledgerKredit->created_by = $model->created_by;
            $ledgerKredit->updated_by = $model->updated_by;
            $ledgerKredit->branch_id = $model->branch_id;
            $ledgerKredit->date_payment = $model->date_journal;

            $ledgerKredit->paymentable_id = $model->id;
            $ledgerKredit->paymentable_type = Journal::class;
            $ledgerKredit->save();

            // tegaskan apakah transfer atau cash

            // $rekDebit = Payment::where('paymentable_type', Journal::class)->where('paymentable_id', $model->id)->orderByDesc('id')->first()->value('rekening');
            $rekDebit = $model->payments[0]->rekening;
            if ($rekDebit != null) {
                if (str_contains($rekDebit, 'BANK')) {
                    $payment_method = 'transfer';
                } elseif (str_contains($rekDebit, 'KAS')) {
                    $payment_method = 'cash';
                }
            } else {
                $payment_method = null;
            }

            Payment::where('id', $model->payments[0]->id)
                ->update([
                    'payment_method' => $payment_method,
                ]);

            // $rekKredit = Payment::where('paymentable_type', Journal::class)->where('paymentable_id', $model->id)->orderByDesc('id')->first()->value('rekening');
            $rekKredit = $model->payments[1]->rekening;
            if ($rekKredit != null) {
                if (str_contains($rekKredit, 'BANK')) {
                    $payment_method = 'transfer';
                } elseif (str_contains($rekKredit, 'KAS')) {
                    $payment_method = 'cash';
                }
            } else {
                $payment_method = null;
            }

            Payment::where('id', $model->payments[1]->id)
                ->update([
                    'payment_method' => $payment_method,
                ]);
        });




        static::updated(function ($model) {
            if ($model->journal_type == 'tf') {
                $mutation_type = 'tf saldo';
            } elseif ($model->journal_type == 'ps') {
                $mutation_type = 'ps saldo';
            } elseif ($model->journal_type == 'sa') {
                $mutation_type = 'sa saldo';
            } else {
                $mutation_type = $model->mutation_type;
            }

            $rekeningnyaDebit = ($model->debit == 'NR-DB-B-1100 CASH / BANK') ? $model->from_rekening : null;
            $rekeningnyaDebit = ($model->journal_type == 'tf') ? $model->to_rekening : $rekeningnyaDebit;
            $cabangPenerimaNominal = ($model->journal_type == 'tf') ? $model->to_branch_id : $model->branch_id;

            Payment::where('paymentable_type', Journal::class)
                ->where('paymentable_id', $model->id)
                ->orderBy('id')->first()
                ->update([
                    'mutation_type' => $mutation_type,
                    'debit' => $model->debit,
                    'kredit' => $model->journal_type == 'tf' ? 'NR-KR-D-3000 Nominal Transfer' : null,
                    'currency' => $model->currency,
                    'nominal_plus' => $model->nominal,
                    'nominal_mins' => 0,
                    'nominal' => $model->nominal,
                    'rekening' => $rekeningnyaDebit,
                    'user_id' => auth()->user()->id,
                    'updated_by' => $model->updated_by,
                    'branch_id' => $cabangPenerimaNominal,
                    'date_payment' => $model->date_journal,
                ]);

            $rekeningnyaKredit = ($model->kredit == 'NR-DB-B-1100 CASH / BANK') ? $model->from_rekening : null;
            $rekeningnyaKredit = ($model->journal_type == 'tf') ? $model->from_rekening : $rekeningnyaKredit;

            Payment::where('paymentable_type', Journal::class)
                ->where('paymentable_id', $model->id)
                ->orderByDesc('id')->first()
                ->update([
                    'mutation_type' => $mutation_type,
                    'debit' => $model->journal_type == 'tf' ? 'NR-KR-D-3000 Nominal Transfer' : null,
                    'kredit' => $model->kredit,
                    'currency' => $model->currency,
                    'nominal_plus' => 0,
                    'nominal_mins' => $model->nominal,
                    'nominal' => $model->nominal,
                    'rekening' => $rekeningnyaKredit,
                    'user_id' => auth()->user()->id,
                    'updated_by' => $model->updated_by,
                    'branch_id' => $model->branch_id,
                    'date_payment' => $model->date_journal,
                ]);

            // tegaskan apakah transfer atau cash

            // $rekDebit = Payment::where('paymentable_type', Journal::class)->where('paymentable_id', $model->id)->orderByDesc('id')->first()->value('rekening');
            $rekDebit = $model->payments[0]->rekening;
            if ($rekDebit != null) {
                if (str_contains($rekDebit, 'BANK')) {
                    $payment_method = 'transfer';
                } elseif (str_contains($rekDebit, 'KAS')) {
                    $payment_method = 'cash';
                }
            } else {
                $payment_method = null;
            }

            Payment::where('id', $model->payments[0]->id)
                ->update([
                    'payment_method' => $payment_method,
                ]);

            // $rekKredit = Payment::where('paymentable_type', Journal::class)->where('paymentable_id', $model->id)->orderByDesc('id')->first()->value('rekening');
            $rekKredit = $model->payments[1]->rekening;
            if ($rekKredit != null) {
                if (str_contains($rekKredit, 'BANK')) {
                    $payment_method = 'transfer';
                } elseif (str_contains($rekKredit, 'KAS')) {
                    $payment_method = 'cash';
                }
            } else {
                $payment_method = null;
            }

            Payment::where('id', $model->payments[1]->id)
                ->update([
                    'payment_method' => $payment_method,
                ]);
        });
    }
}
