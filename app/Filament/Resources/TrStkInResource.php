<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrStkInResource\Pages;
use App\Models\Branch;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TrStkIn;
use App\Models\TrStkOut;
use App\Models\User;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class TrStkInResource extends Resource
{
    protected static ?string $model = TrStkIn::class;
    protected static ?string $label = 'Transfer In';
    protected static ?string $navigationGroup = 'Branch';
    protected static ?int $navigationSort = 24;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make()->schema([
                    Section::make('Tr. Information')->schema([

                        Hidden::make('branch_id')
                            ->default(fn() => Auth::user()->branch_id)
                            ->required(),

                        Hidden::make('created_by')
                            ->default(fn() => Auth::user()->id) ## keperluan untuk memilih user setelah ada auth
                            // ->disabledOn('edit')
                            ->required(),

                        Hidden::make('updated_by')
                            ->default(fn() => Auth::user()->id) ## keperluan untuk memilih user setelah ada auth
                            ->required(),

                        TextInput::make('code_tr')
                            ->label('No. Transsaction')
                            ->default('TRI' . date('YmdHis') . '-' . Auth::user()->id) ## Jika ingin menggunakan OrderID otomatis
                            ->readOnly()
                            ->columnSpan(4),

                        Select::make('from_branch_id')
                            ->label('From')
                            ->disabled()
                            ->dehydrated()
                            ->relationship(
                                name: 'branch',
                                modifyQueryUsing: fn(Builder $query) => $query->orderBy('name')->where('partner_id', Auth::user()->partner_id)->where('id', '!=', Auth::user()->branch_id),
                            )
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name} {$record->phone}")
                            ->searchable(['name', 'phone'])
                            ->preload()
                            ->required()
                            ->columnSpan(4),

                        Select::make('to_branch_id')
                            ->label('To')
                            ->disabled()
                            ->dehydrated()
                            ->relationship('branch', 'name')
                            ->default(Auth::user()->branch_id)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(4),

                        DateTimePicker::make('date_order')
                            ->label('Date Tr.')
                            ->readOnly()
                            // ->disabled()
                            // ->dehydrated()
                            ->required()
                            ->columnSpan(4),

                        Hidden::make('user_id')
                            ->label('User')
                            ->required()
                            ->default(fn() => Auth::user()->id),

                        Select::make('currency')
                            ->default('idr')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->options([
                                'idr' => 'IDR',
                                'usd' => 'USD',
                                'eur' => 'EUR'
                            ])
                            ->columnSpan(4),

                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            ->columnSpan(6)
                            ->options([
                                'new' => 'New',
                                'transfering' => 'Transfering',
                                'done' => 'Done'
                            ])
                            ->colors([
                                'new' => 'info',
                                'transfering' => 'warning',
                                'done' => 'success'
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'transfering' => 'heroicon-m-arrow-path',
                                'done' => 'heroicon-m-check-badge'
                            ]),
                        // ->live(onBlur: true)
                        // ->afterStateUpdated(fn($state, Set $set) => ($state === 'done' ? $set('date_order', now()->format('Y-m-d H:i:s')) : ''))

                        Textarea::make('notes')
                            ->autosize()
                            ->readOnly()
                            ->columnSpan(6)
                    ])->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),

                    Section::make('Tr. Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->reorderable()
                            ->deletable(false)
                            ->schema([

                                Select::make('product_id')
                                    ->disabled()
                                    ->relationship(
                                        name: 'product',
                                        modifyQueryUsing: fn(Builder $query) => $query->orderBy('name')->orderBy('variant')->where('branch_id', Auth::user()->branch_id),
                                    )
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name} {$record->variant}")
                                    ->searchable(['name', 'variant'])
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Product::find($state)?->cogs ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', Product::find($state)?->cogs * $get('p_quantity') ?? 0))

                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
                                        $boughtqty = $orderitems->where('product_id', $get('product_id'))->sum('p_quantity');
                                        $soldqty = $orderitems->where('product_id', $get('product_id'))->sum('quantity');
                                        $set('stock_before', $boughtqty - $soldqty);
                                    })
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
                                        $boughtqty = $orderitems->where('product_id', $get('product_id'))->sum('p_quantity');
                                        $soldqty = $orderitems->where('product_id', $get('product_id'))->sum('quantity');
                                        $set('stock_after', $boughtqty - $soldqty + $get('p_quantity'));
                                    })


                                    ->columnSpan(['sm' => 8, 'md' => 8, 'lg' => 8, 'xl' => 8]),

                                TextInput::make('stock_before')
                                    ->label('Stock Before')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(['sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                TextInput::make('stock_after')
                                    ->label('Stock After')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(['sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                Hidden::make('branch_id')
                                    ->default(fn() => Auth::user()->branch_id)
                                    // ->relationship(
                                    //     name: 'branch',
                                    //     modifyQueryUsing: fn(Builder $query) => $query->orderBy('name')->where('is_active', 1),
                                    // )
                                    // ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name}")
                                    // ->disabled()
                                    // ->dehydrated()
                                    ->columnSpan(['sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                TextInput::make('p_quantity')
                                    ->label('Qty In')
                                    ->readOnly()
                                    ->default(0)
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->live(onBlur: true) //->live(debounce: 1000)
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $stbefore = $get('stock_before');
                                        $set('stock_after', $stbefore + $state);
                                    })
                                    ->afterStateHydrated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount')))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('unit_amount')
                                    ->label('Amount In')
                                    ->readOnly()
                                    ->required()
                                    ->dehydrated()
                                    ->numeric()
                                    ->live(onBlur: true) //->live(debounce: 1000)
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('p_quantity')))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('total_amount')
                                    ->label('T.Amount In')
                                    ->required()
                                    ->readOnly()
                                    ->numeric()
                                    ->placeholder(function (Set $set, Get $get) {
                                        $stbefore = $get('stock_before');
                                        $stafter = $get('stock_after');
                                        $value =  $stafter - $stbefore;
                                        $set('total_amount', $value * $get('unit_amount'));
                                    })
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('notes')
                                    ->label('Catatan')
                                    ->readOnly()
                                    ->columnSpan(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),

                                Hidden::make('mutation_type')
                                    ->default('Transfer In'),

                            ])
                            ->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12])
                            ->extraItemActions([
                                Action::make('openProduct')
                                    ->tooltip('Open product')
                                    ->icon('heroicon-m-arrow-top-right-on-square')
                                    ->url(function (array $arguments, Repeater $component): ?string {
                                        $itemData = $component->getRawItemState($arguments['item']);

                                        $product = Product::find($itemData['product_id']);

                                        if (! $product) {
                                            return null;
                                        }

                                        return ProductResource::getUrl('edit', ['record' => $product]);
                                    }, shouldOpenInNewTab: true)
                                    ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
                            ]),

                        Group::make()->schema([

                            Placeholder::make('grand_total_placeholder')
                                ->label('Grand Total')
                                ->content(function (Get $get, Set $set) {

                                    $total = 0;
                                    if (!$repeaters = $get('items')) {
                                        return $total;
                                    }
                                    foreach ($repeaters as $key => $repeater) {
                                        $total += $get("items.{$key}.total_amount");
                                    }

                                    $grandTotal = $total;
                                    $set('grand_total', $grandTotal);
                                    return Number::currency($grandTotal, 'IDR');
                                }),

                            Hidden::make('grand_total')
                                ->default(0)

                        ])->columns(['sm' => 3, 'md' => 3, 'lg' => 3, 'xl' => 3])
                    ])
                ])->columnSpanFull()
                    ->afterStateHydrated(function ($record) {
                        if (!is_null($record)) {
                            if (Auth::user()->branch_id != $record->branch_id) {
                                return redirect('/admin/tr-stk-ins');
                            }
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->modifyQueryUsing(function (Builder $query) {
                return $query->addSelect([
                    'from_branch' => Branch::query()->select('name')
                        ->whereColumn('id', 'from_branch_id'),
                    'to_branch' => Branch::query()->select('name')
                        ->whereColumn('id', 'to_branch_id'),
                    'created' => User::query()->select('name')
                        ->whereColumn('id', 'created_by'),
                    'updated' => User::query()->select('name')
                        ->whereColumn('id', 'updated_by'),
                ]);
            })
            ->columns([

                TextColumn::make('code_tr')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('from_branch')
                    ->label('From')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('to_branch')
                    ->label('To')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                // ->searchable(isIndividual:true),

                TextColumn::make('grand_total')
                    ->numeric(locale: 'nl')->prefix('Rp ')
                    ->sortable()
                    ->alignRight()
                    ->summarize(Sum::make()->numeric(locale: 'nl')->prefix('Rp ')->label('Total')),

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'transfering' => 'Transfering',
                        'done' => 'Done'
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        OrderItem::where('tr_stk_in_id', $record->id)->update(['status' => $state]);
                        $outID = TrStkOut::where('code_tr', Str::replace('TRI', 'TRO', $record->code_tr))->value('id');
                        OrderItem::where('tr_stk_out_id', $outID)->update(['updated_by' => Auth::user()->id, 'status' => $record->status]);
                    })
                    ->searchable()
                    ->sortable()
                    ->selectablePlaceholder(false),

                TextColumn::make('notes')
                    ->label('Catatan')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('date_order')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->toggleable(isToggledHiddenByDefault: true)
            ])->defaultSort('created_at', 'desc')

            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make(name: 'Status')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->color('warning')
                        ->icon('heroicon-o-flag')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->status = $data['status'];
                                $record->save();
                                OrderItem::where('tr_stk_in_id', $record->id)->update(['status' => $data['status']]);
                                $outID = TrStkOut::where('code_tr', Str::replace('TRI', 'TRO', $record->code_tr))->value('id');
                                OrderItem::where('tr_stk_out_id', $outID)->update(['updated_by' => Auth::user()->id, 'status' => $record->status]);
                            }
                        })
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'new' => 'New',
                                    'transfering' => 'Transfering',
                                    'done' => 'Done'
                                ])
                                ->required(),
                        ]),
                    // Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
                    // Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->query(function (TrStkIn $query) {
                if (Auth::user()->id != 1) {
                    return $query->where('branch_id', Auth::user()->branch_id);
                } else {
                    return $query;
                };
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
            'index' => Pages\ListTrStkIns::route('/'),
            // 'create' => Pages\CreateTrStkIn::route('/create'),
            'edit' => Pages\EditTrStkIn::route('/{record}/edit'),
        ];
    }
}
