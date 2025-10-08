<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\OrderItem;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\OrderItemResource\Pages;
use App\Models\AdjItem;
use App\Models\Order;
use App\Models\Porder;
use App\Models\Production;
use App\Models\TrStkIn;
use App\Models\TrStkOut;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\Builder as QueryBuilder;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;
    protected static ?string $label = 'Stock Histories';
    protected static ?string $navigationGroup = 'Product';
    protected static ?int $navigationSort = 21;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('porder_id')
                //     ->numeric(),
                // Forms\Components\TextInput::make('order_id')
                //     ->numeric(),
                // Forms\Components\TextInput::make('adj_item_id')
                //     ->numeric(),
                // Forms\Components\TextInput::make('product_id')
                //     ->numeric(),
                // Forms\Components\TextInput::make('p_quantity')
                //     ->numeric(),
                // Forms\Components\TextInput::make('p_unit_amount')
                //     ->numeric(),
                // Forms\Components\TextInput::make('p_total_amount')
                //     ->numeric(),
                // Forms\Components\TextInput::make('quantity')
                //     ->numeric(),
                // Forms\Components\TextInput::make('unit_amount')
                //     ->numeric(),
                // Forms\Components\TextInput::make('total_amount')
                //     ->numeric(),
                // Forms\Components\TextInput::make('notes')
                //     ->numeric(),
                // Forms\Components\TextInput::make('stock_before')
                //     ->numeric(),
                // Forms\Components\TextInput::make('stock_after')
                //     ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->poll('10s')
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->addSelect([
                        'created' => User::query()->select('name')
                            ->whereColumn('id', 'created_by'),
                        'updated' => User::query()->select('name')
                            ->whereColumn('id', 'updated_by'),
                    ]);
            })
            ->columns([

                TextColumn::make('date_order')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'canceled' => 'canceled',
                        'pending' => 'Pending',
                        'transfering' => 'Transfering',
                        'done' => 'Done',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'gray',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                        'pending' => 'warning',
                        'transfering' => 'warning',
                        'done' => 'success',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'canceled' => 'heroicon-m-x-circle',
                        'pending' => 'heroicon-m-arrow-path',
                        'transfering' => 'heroicon-m-truck',
                        'done' => 'heroicon-m-check-badge',
                    })
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: false),


                TextColumn::make('product.sku')
                    ->label('Code')
                    ->sortable()
                    ->searchable(isIndividual: true),
                TextColumn::make('product.alias')
                    ->label('Product')
                    ->sortable()
                    ->searchable(isIndividual: true),


                TextColumn::make('mutation_type')
                    ->label('mutation')
                    ->url(function ($state, $record) {
                        if ($state) {
                            if ($state === 'Sales') {
                                $Mut_ID = OrderItem::where('id', $record->id)->value('order_id');
                                return url("/admin/orders/{$Mut_ID}");
                            } elseif ($state === 'Purchase') {
                                $Mut_ID = OrderItem::where('id', $record->id)->value('porder_id');
                                return url("/admin/porders/{$Mut_ID}");
                            } elseif ($state === 'Adjusment') {
                                $Mut_ID = OrderItem::where('id', $record->id)->value('adj_item_id');
                                return url("/admin/adj-items/{$Mut_ID}");
                            } elseif ($state === 'Production') {
                                $Mut_ID = OrderItem::where('id', $record->id)->value('production_id');
                                return url("/admin/productions/{$Mut_ID}");
                            } elseif ($state === 'Transfer Out') {
                                $Mut_ID = OrderItem::where('id', $record->id)->value('tr_stk_out_id');
                                return url("/admin/tr-stk-outs/{$Mut_ID}");
                            } elseif ($state === 'Transfer In') {
                                $Mut_ID = OrderItem::where('id', $record->id)->value('tr_stk_in_id');
                                return url("/admin/tr-stk-ins/{$Mut_ID}");
                            }
                        }
                    })
                    ->color('info')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->alignRight()
                    ->summarize(Summarizer::make()
                        ->label('STOCK')
                        ->using(fn(QueryBuilder $query) => $query->sum('p_quantity') - $query->sum('quantity'))),

                TextColumn::make('p_quantity')
                    ->label('Qty In')
                    ->numeric()
                    ->alignRight()
                    ->sortable()
                    ->summarize(Sum::make()->numeric(locale: 'id')->label('Total+'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('quantity')
                    ->label('Qty Out')
                    ->numeric()
                    ->alignRight()
                    ->sortable()
                    ->summarize(Sum::make()->numeric(locale: 'id')->label('Total-'))
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('unit_amount')
                    ->label('Amount In')
                    ->numeric(locale: 'id')
                    ->alignRight()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                // ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total+')),
                TextColumn::make('total_amount')
                    ->label('T. Amount In')
                    ->numeric(locale: 'id')
                    ->alignRight()
                    ->sortable()
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total+'))
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('p_unit_amount')
                    ->label('Amount Out')
                    ->numeric(locale: 'id')
                    ->alignRight()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                // ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total-')),
                TextColumn::make('p_total_amount')
                    ->label('T. Amount Out')
                    ->numeric(locale: 'id')
                    ->alignRight()
                    ->sortable()
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total-'))
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('branch.name')
                    ->sortable()
                    ->searchable(isIndividual: true),

                TextColumn::make('notes')
                    ->searchable()
                    ->sortable()
                    ->alignRight()
                    ->summarize(
                        Summarizer::make()
                            ->label('TOTAL')
                            ->numeric(locale: 'id')->prefix('Rp ')
                            ->using(fn(QueryBuilder $query) => $query->sum('total_amount') - $query->sum('p_total_amount'))
                    ),

                // TextColumn::make('stock_before')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('stock_after')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created')
                    ->label('Created by')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated')
                    ->label('Updated by')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // ->formatStateUsing(fn(Model $record): string => "{$record->porder->id} {$record->order->id}")

                TextColumn::make('order.code_tr')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('porder.code_tr')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('production.code_tr')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('adjItem.code_tr')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('trStkOut.code_tr')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('trStkIn.code_tr')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])->defaultSort('date_order', 'desc')
            // ->persistSortInSession()
            // ->persistFiltersInSession()
            // ->persistSearchInSession()
            // ->deselectAllRecordsWhenFiltered(false)
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
                Tables\Grouping\Group::make('updated_at')
                    ->label('Date')
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('status')
                    ->label('Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('product.alias')
                    ->label('Product')
                    ->collapsible(),
            ])
            ->filters([
                Filter::make('date_order')
                    ->form([
                        DatePicker::make('date_order_from'),
                        DatePicker::make('date_order_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_order_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date_order', '>=', $date),
                            )
                            ->when(
                                $data['date_order_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date_order', '<=', $date),
                            );
                    }),
                Filter::make('status')
                    ->label('without Canceled')
                    ->query(fn(Builder $query): Builder => $query->where('status', '!=', 'canceled'))
                    ->default(true),
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //         Tables\Actions\ForceDeleteBulkAction::make(),
            //         Tables\Actions\RestoreBulkAction::make(),
            //     ]),
            // ])
            ->query(function (OrderItem $query) {
                if (Auth::user()->id != 0) {
                    return $query->where('branch_id', Auth::user()->branch_id)
                        // ->where('order.status', '!=', 'canceled')
                        // ->where('porder.status', '!=', 'canceled')
                    ;
                } else {
                    return $query
                        // ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
                        // ->leftJoin('porders', 'order_items.porder_id', '=', 'porders.id')
                        // ->get()
                        // ->where('order.status', '!=', 'canceled')
                        // ->where('porder.status', '!=', 'canceled')
                    ;
                }
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
            'index' => Pages\ListOrderItems::route('/'),
            // 'create' => Pages\CreateOrderItem::route('/create'),
            // 'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
