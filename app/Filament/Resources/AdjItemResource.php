<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdjItemResource\Pages;
use App\Filament\Resources\AdjItemResource\RelationManagers;
use App\Models\AdjItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class AdjItemResource extends Resource
{
    protected static ?string $model = AdjItem::class;

    protected static ?string $label = 'Adjustment';
    protected static ?string $navigationGroup = 'Product';
    protected static ?int $navigationSort = 20;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Adj. Information')->schema([

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
                            ->default('ADJ' . date('YmdHis') . '-' . Auth::user()->id) ## Jika ingin menggunakan OrderID otomatis
                            ->readOnly()
                            ->columnSpan(4),

                        DateTimePicker::make('date_order')
                            ->label('Date Adj.')
                            ->default(now())
                            ->required()
                            ->readOnly()
                            ->columnSpan(4),

                        Hidden::make('user_id')
                            ->label('User')
                            ->required()
                            ->default(fn() => Auth::user()->id),

                        Select::make('currency')
                            ->default('idr')
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
                                'pending' => 'Pending',
                                'done' => 'Done'
                            ])
                            ->colors([
                                'new' => 'info',
                                'pending' => 'warning',
                                'done' => 'success'
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'pending' => 'heroicon-m-arrow-path',
                                'done' => 'heroicon-m-check-badge'
                            ]),

                        Textarea::make('notes')
                            ->autosize()
                            ->columnSpan(6)
                    ])->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),

                    Section::make('Adj. Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->reorderable()
                            ->schema([

                                Select::make('product_id')
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
                                    ->afterStateUpdated(fn($state, Set $set) => $set('p_unit_amount', Product::find($state)?->cogs ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('p_total_amount', Product::find($state)?->cogs * $get('quantity') ?? 0))

                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        // $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled')->where('order.status', '!=', 'new')->where('porder.status', '!=', 'new')->where('porder.status', '!=', 'processing')->where('porder.status', '!=', 'shipped');
                                        $itemsBoughtP = OrderItem::where('product_id', $get('product_id'))->whereNotNull('porder_id')->where('status', '!=', 'canceled')->where('status', '!=', 'new')->sum('p_quantity');
                                        $itemsSoldP = OrderItem::where('product_id', $get('product_id'))->whereNotNull('order_id')->where('status', '!=', 'canceled')->where('status', '!=', 'new')->sum('p_quantity');
                                        $itemsAdjP = OrderItem::where('product_id', $get('product_id'))->whereNotNull('adj_item_id')->sum('p_quantity');
                                        $itemsProdP = OrderItem::where('product_id', $get('product_id'))->whereNotNull('production_id')->sum('p_quantity');
                                        $itemsTfOutP = OrderItem::where('product_id', $get('product_id'))->whereNotNull('tr_stk_out_id')->sum('p_quantity');
                                        $itemsTfInP = OrderItem::where('product_id', $get('product_id'))->whereNotNull('tr_stk_in_id')->sum('p_quantity');
                                        
                                        $itemsBought = OrderItem::where('product_id', $get('product_id'))->whereNotNull('porder_id')->where('status', '!=', 'canceled')->where('status', '!=', 'new')->sum('quantity');
                                        $itemsSold = OrderItem::where('product_id', $get('product_id'))->whereNotNull('order_id')->where('status', '!=', 'canceled')->where('status', '!=', 'new')->sum('quantity');
                                        $itemsAdj = OrderItem::where('product_id', $get('product_id'))->whereNotNull('adj_item_id')->sum('quantity');
                                        $itemsProd = OrderItem::where('product_id', $get('product_id'))->whereNotNull('production_id')->sum('quantity');
                                        $itemsTfOut = OrderItem::where('product_id', $get('product_id'))->whereNotNull('tr_stk_out_id')->sum('quantity');
                                        $itemsTfIn = OrderItem::where('product_id', $get('product_id'))->whereNotNull('tr_stk_in_id')->sum('quantity');

                                        $orderitems = $itemsBoughtP + $itemsSoldP + $itemsAdjP + $itemsProdP + $itemsTfOutP + $itemsTfInP - $itemsBought - $itemsSold - $itemsAdj - $itemsProd - $itemsTfOut - $itemsTfIn ;

                                        $set('stock_before', $orderitems);
                                    })


                                    ->columnSpan(['sm' => 8, 'md' => 8, 'lg' => 8, 'xl' => 8]),

                                TextInput::make('stock_before')
                                    ->label('Stock Before')
                                    ->live()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(['sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                TextInput::make('stock_after')
                                    ->label('Stock After')
                                    ->numeric()
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $stbefore = $get('stock_before');
                                        $value = $state - $stbefore;
                                        if ($value >= 0) {
                                            $set('p_quantity', $value);
                                        } else {
                                            $set('p_quantity', 0);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $stbefore = $get('stock_before');
                                        $value = $state - $stbefore;
                                        if ($value < 0) {
                                            $set('quantity', $value * -1);
                                        } else {
                                            $set('quantity', 0);
                                        }
                                    })
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
                                    ->label('Qty Get')
                                    ->readOnly()
                                    ->default(0)
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->live(onBlur: true) //->live(debounce: 1000)
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount')))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('unit_amount')
                                    ->label('Amount Get')
                                    ->readOnly()
                                    ->required()
                                    ->dehydrated()
                                    ->numeric()
                                    ->live(onBlur: true) //->live(debounce: 1000)
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('p_quantity')))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('total_amount')
                                    ->label('T.Amount Get')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->placeholder(function (Set $set, Get $get) {
                                        $stbefore = $get('stock_before');
                                        $stafter = $get('stock_after');
                                        $value = $stafter - $stbefore;
                                        if ($value >= 0) {
                                            $set('total_amount', $value * $get('unit_amount'));
                                        } else {
                                            $set('total_amount', 0);
                                        }
                                    })
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('quantity')
                                    ->label('Qty Loss')
                                    ->readOnly()
                                    ->default(0)
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->live(debounce: 1000)
                                    ->afterStateHydrated(fn($state, Set $set, Get $get) => $set('p_total_amount', $state * $get('p_unit_amount')))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('p_unit_amount')
                                    ->label('Amount Loss')
                                    ->readOnly()
                                    ->required()
                                    ->dehydrated()
                                    ->numeric()
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('p_total_amount', $state * $get('quantity')))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('p_total_amount')
                                    ->label('T.Amount Loss')
                                    ->required()
                                    ->disabled()
                                    ->numeric()
                                    ->placeholder(function (Set $set, Get $get) {
                                        $stbefore = $get('stock_before');
                                        $stafter = $get('stock_after');
                                        $value = $stafter - $stbefore;
                                        if ($value < 0) {
                                            $set('p_total_amount', $value * -1 * $get('p_unit_amount'));
                                        } else {
                                            $set('p_total_amount', 0);
                                        }
                                    })
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('notes')
                                    ->label('Catatan')
                                    ->columnSpan(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),

                                Hidden::make('mutation_type')
                                    ->default('Adjusment'),
                                Hidden::make('created_by')
                                    ->default(null),
                                Hidden::make('updated_by')
                                    ->default(null),

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
                                        $total += $get("items.{$key}.total_amount") - $get("items.{$key}.p_total_amount");
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
                                return redirect('/admin/adj-items');
                            }
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->poll('10s')
            ->modifyQueryUsing(function (Builder $query) {
                return $query->addSelect([
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
                        'pending' => 'Pending',
                        'done' => 'Done'
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        OrderItem::where('adj_item_id', $record->id)->update(['status' => $state]);
                    })
                    ->searchable()
                    ->sortable()
                    ->selectablePlaceholder(false),

                TextColumn::make('notes')
                    ->label('Catatan')
                    ->searchable()
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
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')

            ->filters([
                Filter::make('created_at')
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
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
                                OrderItem::where('adj_item_id', $record->id)->update(['status' => $data['status']]);
                            }
                        })
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'new' => 'New',
                                    'pending' => 'Pending',
                                    'done' => 'Done'
                                ])
                                ->required(),
                        ]),

                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                ]),
            ])
            ->query(function (AdjItem $query) {
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
            'index' => Pages\ListAdjItems::route('/'),
            'create' => Pages\CreateAdjItem::route('/create'),
            'view' => Pages\ViewAdjItem::route('/{record}'),
            'edit' => Pages\EditAdjItem::route('/{record}/edit'),
        ];
    }
}
