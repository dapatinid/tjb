<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\AdjItem;
use App\Models\Journal;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Porder;
use App\Models\Production;
use App\Models\TrStkIn;
use App\Models\TrStkOut;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder as QueryBuilder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $modelLabel = 'ledger & payment';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Textarea::make('mutation_type')
                //     ->columnSpanFull(),
                // DateTimePicker::make('date_payment'),
                // TextInput::make('currency')
                //     ->maxLength(255),
                // TextInput::make('payment_method')
                //     ->maxLength(255),
                // TextInput::make('nominal_plus')
                //     ->numeric(),
                // TextInput::make('nominal_mins')
                //     ->numeric(),
                // TextInput::make('created_by')
                //     ->numeric(),
                // TextInput::make('updated_by')
                //     ->numeric(),
                // TextInput::make('branch_id')
                //     ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->poll('10s')
            // ->modifyQueryUsing(function (Builder $query) {
            //     return $query->addSelect([
            //         'created' => User::query()->select('name')
            //             ->whereColumn('id', 'created_by'),
            //         'updated' => User::query()->select('name')
            //             ->whereColumn('id', 'updated_by'),
            //     ]);
            // })
            ->columns([
                // Tables\Columns\IconColumn::make('porder.is_paid')
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->label('pstatus')
                //     ->sortable()
                //     ->boolean()
                //     ->trueColor('info')
                //     ->falseColor('danger')
                //     ->trueIcon('heroicon-o-check-badge')
                //     ->falseIcon('heroicon-o-x-mark'),
                // Tables\Columns\IconColumn::make('order.is_paid')
                //     ->label('status')
                //     ->sortable()
                //     ->boolean()
                //     ->trueColor('info')
                //     ->falseColor('danger')
                //     ->trueIcon('heroicon-o-check-badge')
                //     ->falseIcon('heroicon-o-x-mark'),
                // Tables\Columns\TextColumn::make('porder.code_tr')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->url(fn(Model $record): string => url('/admin/porders/' . $record->porder_id . '/edit')),
                // Tables\Columns\TextColumn::make('order.code_tr')
                //     ->sortable()
                //     ->url(fn(Model $record): string => url('/admin/orders/' . $record->order_id . '/edit')),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_payment')
                    ->label('Tgl Transaksi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->limit(15)
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('mutation_type')
                    ->label('Type')
                    ->url(function ($state, $record) {
                        if ($state) {
                            if ($state === 'Sales') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/orders/{$Mut_ID}");
                            } elseif ($state === 'Piutang Penjualan') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/orders/{$Mut_ID}");
                            } elseif ($state === 'Barang Terjual') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/orders/{$Mut_ID}");
                            } elseif ($state === 'Purchase') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/porders/{$Mut_ID}");
                            } elseif ($state === 'Hutang Pembelian') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/porders/{$Mut_ID}");
                            } elseif ($state === 'Barang Terbeli') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/porders/{$Mut_ID}");
                            } elseif ($state === 'Barang Stok Bertambah') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/adj-items/{$Mut_ID}");
                            } elseif ($state === 'Barang Stok Berkurang') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/adj-items/{$Mut_ID}");
                            } elseif ($state === 'Barang Produksi Berkembang') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/productions/{$Mut_ID}");
                            } elseif ($state === 'Barang Produksi Menyusut') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/productions/{$Mut_ID}");
                            } elseif ($state === 'Barang Transfer Keluar') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/tr-stk-outs/{$Mut_ID}");
                            } elseif ($state === 'Barang Transfer Masuk') {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/tr-stk-ins/{$Mut_ID}");
                            } else {
                                $Mut_ID = $record->paymentable_id;
                                return url("/admin/journals/{$Mut_ID}");
                            }
                        }
                    })
                    ->color('info')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->summarize(Summarizer::make()
                        ->label('Total')->numeric(locale: 'id')->prefix('Rp ')
                        ->using(fn(QueryBuilder $query) => $query->sum('nominal_plus') - $query->sum('nominal_mins')))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rekening')
                    ->label('Rekening')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('nominal_plus')
                    ->label('Plus')
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->alignRight()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total+'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nominal_mins')
                    ->label('Mins')
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->alignRight()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total-'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nominal')
                    ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800'])
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->alignRight()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Nominal')),
                Tables\Columns\TextColumn::make('debit')
                    ->label('Debit')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('kredit')
                    ->label('Kredit')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('userCre.name')
                    ->label('Created by')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('userUpd.name')
                    ->label('Updated by')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('branch.name')
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('date_payment', 'desc')
            ->persistSortInSession()
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->deselectAllRecordsWhenFiltered(false)
            ->defaultPaginationPageOption(25)
            ->paginated([
                10,
                25,
                50,
                100,
                500,
                // 1000,
                // 'all'
            ])

            ->groups([
                Tables\Grouping\Group::make('date_payment')
                    ->label('Date')
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('mutation_type')
                    ->label('Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('payment_method')
                    ->label('Method')
                    ->collapsible(),
                Tables\Grouping\Group::make('rekening')
                    ->label('Rekening')
                    ->collapsible(),
            ])



            ->filters([
                Filter::make('date_payment')
                    ->form([
                        DatePicker::make('date_payment_from'),
                        DatePicker::make('date_payment_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_payment_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date_payment', '>=', $date),
                            )
                            ->when(
                                $data['date_payment_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date_payment', '<=', $date),
                            );
                    }),
                SelectFilter::make('mutation_type')
                    ->multiple()
                    ->options([
                        'Purchase' => 'Purchase',
                        'Hutang Pembelian' => 'Hutang Pembelian',
                        'Sales' => 'Sales',
                        'Piutang Penjualan' => 'Piutang Penjualan',
                        'Barang Terbeli' => 'Barang Terbeli',
                        'Barang Terjual' => 'Barang Terjual',
                        'Barang Produksi Berkembang' => 'Barang Produksi Berkembang',
                        'Barang Produksi Menyusut' => 'Barang Produksi Menyusut',
                        'Barang Stok Bertambah' => 'Barang Stok Bertambah',
                        'Barang Stok Berkurang' => 'Barang Stok Berkurang',
                        'Barang Transfer Keluar' => 'Barang Transfer Keluar',
                        'Barang Transfer Masuk' => 'Barang Transfer Masuk',
                    ]),
                SelectFilter::make('payment_method')
                    ->multiple()
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                    ]),
                SelectFilter::make('rekening')
                    ->searchable()
                    ->options([
                        'KAS UTAMA' => 'KAS UTAMA',
                        'KAS KASIR' => 'KAS KASIR',
                        'KAS KECIL' => 'KAS KECIL',
                        'BANK BSI' => 'BANK BSI',
                        'BANK BCA' => 'BANK BCA',
                        'BANK BRI' => 'BANK BRI',
                        'BANK BNI' => 'BANK BNI',
                        'BANK BTN' => 'BANK BTN',
                        'BANK MANDIRI' => 'BANK MANDIRI',
                        'BANK JATENG' => 'BANK JATENG',
                    ]),
                SelectFilter::make('userCre')
                    ->label('Dibuat oleh')
                    ->relationship('userCre', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('userUpd')
                    ->label('Diedit oleh')
                    ->relationship('userUpd', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TrashedFilter::make()
            ])
            // ->recordUrl(fn(Model $record): string => url('/admin/orders/' . $record->order_id . '/edit'))
            ->recordUrl(null)
            // ->actions([
            //     Tables\Actions\EditAction::make()
            //         ->hidden(fn(Model $record): bool => $record->mutation_type === 'Sales' || $record->mutation_type === 'Purchase'),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //         Tables\Actions\ForceDeleteBulkAction::make(),
            //         Tables\Actions\RestoreBulkAction::make(),
            //     ]),
            // ])
            ->query(function (Payment $query) {
                return $query->where('branch_id', Auth::user()->branch_id);
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            // 'create' => Pages\CreatePayment::route('/create'),
            // 'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
