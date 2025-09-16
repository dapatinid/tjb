<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Htmlable;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $label = 'Sale Order';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 5;


    protected static ?string $recordTitleAttribute = 'code_tr';
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->code_tr;
    }
    // public static function getGloballySearchableAttributes(): array
    // {
    //     return ['code_tr', 'grand_total'];
    // }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->user->name,
            'grand_total' => @number_format($record->grand_total),
        ];
    }




    public static function getGloballySearchableAttributes(): array
    {
        return ['code_tr'];
    }


    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([

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

                        Hidden::make('q')
                            ->default(null),

                        TextInput::make('code_tr')
                            ->label('No. Transsaction')
                            ->default('ORD' . date('YmdHis') . '-' . Auth::user()->id . '-' . Order::where('branch_id', auth()->user()->branch_id)->where('created_by', auth()->user()->id)->where('created_at', 'like', "%" . Carbon::now()->format('Y-m-d') . "%")->count() + 1) ## Jika ingin menggunakan OrderID otomatis
                            ->readOnly()
                            ->columnSpan(6),

                        DateTimePicker::make('date_order')
                            ->default(now())
                            ->required()
                            ->columnSpan(6),

                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship(
                                name: 'user',
                                modifyQueryUsing: fn(Builder $query) => $query->orderBy('name'),
                            )
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->id == 2 ? "Customer Umum" : "{$record->name}")
                            ->searchable(['name'])
                            ->preload()
                            ->required()
                            ->columnSpan(12),

                        ToggleButtons::make('sales_type')
                            ->required()
                            ->grouped()
                            ->options([
                                // 'dine_in' => 'Dine In',
                                'self_pickup' => 'Self Pickup',
                                'delivery' => 'Delivery'
                            ])
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                if ($get('sales_type') == 'self_pickup') {
                                    $set('shipping_method', 'self_pickup');
                                } else {
                                    $set('shipping_method', '');
                                }
                            })
                            ->columnSpan(['default' => 12, 'sm' => 6, 'md' => 6, 'lg' => 6, 'xl' => 6]),

                        Select::make('shipping_method')
                            ->dehydrated()
                            ->options(User::query()->where('is_admin', true)->whereNull('level')->pluck('name', 'id'))
                            ->required(function (Get $get) {
                                if ($get('sales_type') == 'delivery') {
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                            ->columnSpan(['default' => 12, 'sm' => 6, 'md' => 6, 'lg' => 6, 'xl' => 6]),

                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            // ->columnSpanFull()
                            ->columnSpan(12)
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'canceled' => 'Canceled'
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'gray',
                                'delivered' => 'success',
                                'canceled' => 'danger'
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'canceled' => 'heroicon-m-x-circle'
                            ]),

                        Textarea::make('notes')
                            ->autosize()
                            ->columnSpan(12)
                    ])->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->reorderable()
                            ->deleteAction(
                                fn(Action $action) => $action->hidden(fn() => Auth::user()->roles[0]->name === 'Kasir'),
                            )
                            ->schema([

                                Select::make('product_id')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->relationship(
                                        name: 'product',
                                        modifyQueryUsing: fn(Builder $query) => $query->orderBy('name')->orderBy('variant')->where('branch_id', Auth::user()->branch_id),
                                    )
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name} {$record->variant} Rp" . Number::format($record->price, locale: 'de'))
                                    ->searchable(['name', 'variant'])
                                    ->preload()
                                    ->required()
                                    ->live()
                                    // ->distinct()
                                    // ->disableOptionsWhenSelectedInSiblingRepeaterItems() ## ini jika item tidak ingin berulang
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', Product::find($state)?->price * $get('quantity') ?? 0))
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->columnSpan(['default' => 12, 'sm' => 6, 'md' => 6, 'lg' => 6, 'xl' => 6]),

                                TextInput::make('quantity')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->numeric()
                                    ->required()
                                    // ->maxValue(function (Get $get) {
                                    //     $boughtqty = OrderItem::query()->where('product_id', $get('product_id'))->sum('p_quantity');
                                    //     $soldqty = OrderItem::query()->where('product_id', $get('product_id'))->sum('quantity');
                                    //     return $boughtqty - $soldqty;
                                    // }) #ini untuk membatasi quantity sesuai stok yang ada
                                    // ->minValue(1)
                                    // ->live(debounce: 1000) ## ini untuk klik di luar field lalu ada perubahan
                                    ->live(onBlur: true) //->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount')))
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->afterStateUpdated(fn(Set $set, Get $get) => $set('poin', (Product::find($get('product_id'))?->poin ?? 0) * $get('quantity')))
                                    ->columnSpan(['default' => 3, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                TextInput::make('unit_amount')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->label('Amount')
                                    ->required()
                                    ->dehydrated()
                                    ->numeric()
                                    ->live(onBlur: true) //->live(debounce: 1000)
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('quantity')))
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->columnSpan(['default' => 4, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                TextInput::make('total_amount')
                                    ->label('Total')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->columnSpan(['default' => 5, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                Hidden::make('branch_id')
                                    ->default(fn() => Auth::user()->branch_id)
                                    // ->relationship(
                                    //     name: 'branch',
                                    //     modifyQueryUsing: fn(Builder $query) => $query->orderBy('name')->where('is_active', 1),
                                    // )
                                    // ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name}")
                                    // ->disabled()
                                    // ->dehydrated()
                                    ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                                Hidden::make('poin')
                                    ->default(null),
                                Hidden::make('mutation_type')
                                    ->default('Sales'),
                                Hidden::make('created_by')
                                    ->default(null),
                                Hidden::make('updated_by')
                                    ->default(null),

                            ])
                            ->columns(['default' => 12, 'sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12])
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

                            TextInput::make(name: 'discount')
                                ->default(0)
                                ->required()
                                ->numeric()
                                ->live(debounce: 1000),
                            TextInput::make(name: 'shipping_amount')
                                ->default(0)
                                ->required()
                                ->numeric()
                                ->live(debounce: 1000),

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

                                    $discount = $get('discount');
                                    if ($discount != null) {
                                        $discount = $get('discount');
                                    } else {
                                        $discount = 0;
                                    }
                                    $shipping_amount = $get('shipping_amount');
                                    if ($shipping_amount != null) {
                                        $shipping_amount = $get('shipping_amount');
                                    } else {
                                        $shipping_amount = 0;
                                    }
                                    $bytambahan = $shipping_amount - $discount;
                                    $grandTotal = $total + $bytambahan;
                                    $set('grand_total', $grandTotal);
                                    return Number::currency($grandTotal, 'IDR');
                                }),

                            Hidden::make('grand_total')
                                ->default(0)

                        ])->columns(['sm' => 3, 'md' => 3, 'lg' => 3, 'xl' => 3])
                    ]),
                    Section::make('Payments')->schema([
                        Repeater::make('payments')
                            ->relationship(
                                name: 'payments',
                                modifyQueryUsing: fn(Builder $query) => $query->where('mutation_type', 'Sales'),
                            )
                            // ->reorderable()
                            ->deleteAction(
                                fn(Action $action) => $action->hidden(fn() => Auth::user()->roles[0]->name === 'Kasir'),
                            )
                            ->schema([

                                Hidden::make('created_by')
                                    ->default(fn() => Auth::user()->id)
                                    ->required(),

                                Hidden::make('updated_by')
                                    ->default(null),

                                Hidden::make('mutation_type')
                                    ->default('Sales'),

                                DateTimePicker::make('date_payment')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->default(now())
                                    ->required()
                                    ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->columnSpan(['sm' => 8, 'md' => 8, 'lg' => 8, 'xl' => 8]),

                                Select::make('currency')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->default('idr')
                                    ->required()
                                    ->options([
                                        'idr' => 'IDR',
                                        'usd' => 'USD',
                                        'eur' => 'EUR'
                                    ])
                                    ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                ToggleButtons::make('payment_method')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->options([
                                        'cash' => 'Cash',
                                        'transfer' => 'Transfer',
                                    ])
                                    ->required()
                                    ->grouped()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->afterStateUpdated(fn(Set $set) => $set('rekening', null))
                                    ->columnSpan(['sm' => 5, 'md' => 5, 'lg' => 5, 'xl' => 5]),

                                Select::make('rekening')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->required()
                                    ->options(fn(Get $get): array => match ($get('payment_method')) {
                                        'cash' => [
                                            'KAS UTAMA' => 'KAS UTAMA',
                                            'KAS KASIR' => 'KAS KASIR',
                                            'KAS KECIL' => 'KAS KECIL',
                                        ],
                                        'transfer' => [
                                            'BANK BCA' => 'BANK BCA',
                                            'BANK BRI' => 'BANK BRI',
                                        ],
                                        default => [
                                            'KAS UTAMA' => 'KAS UTAMA',
                                            'KAS KASIR' => 'KAS KASIR',
                                            'KAS KECIL' => 'KAS KECIL',
                                            'BANK BCA' => 'BANK BCA',
                                            'BANK BRI' => 'BANK BRI',
                                        ],
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                                TextInput::make('nominal_plus')
                                    ->disabled(function (Get $get) {
                                        if (Auth::user()->roles[0]->name === 'Kasir' && $get('id') != null) {
                                            return true;
                                        }
                                    })
                                    ->label('Nominal')
                                    ->default(0)
                                    ->required()
                                    ->numeric()
                                    ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                                    ->columnSpan(['sm' => 3, 'md' => 3, 'lg' => 3, 'xl' => 3]),

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

                            ])
                            ->minItems(1)
                            ->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),

                        Group::make()->schema([

                            TextInput::make('paid_at')
                                ->disabled()
                                ->dehydrated(),

                            Toggle::make('is_paid')
                                ->required()
                                ->label('Paid?')
                                ->disabled()
                                ->dehydrated(),

                            Placeholder::make('total_payment_placeholder')
                                ->label('Total Payment')
                                ->content(function (Get $get, Set $set) {

                                    $subtotal_payment = 0;
                                    if (!$repeaters = $get('payments')) {
                                        return $subtotal_payment;
                                    }
                                    foreach ($repeaters as $key => $repeater) {
                                        $subtotal_payment += $get("payments.{$key}.nominal_plus");
                                    }

                                    $grand_total = $get('grand_total');
                                    if ($grand_total != null) {
                                        $grand_total = $get('grand_total');
                                    } else {
                                        $grand_total = 0;
                                    }

                                    $set('total_payment', $subtotal_payment);
                                    return Number::currency($subtotal_payment, 'IDR');
                                }),

                            Placeholder::make('total_cashback_placeholder')
                                ->label('Total Cashback')
                                ->content(function (Get $get, Set $set) {

                                    $subtotal_payment = 0;
                                    if (!$repeaters = $get('payments')) {
                                        return $subtotal_payment;
                                    }
                                    foreach ($repeaters as $key => $repeater) {
                                        $subtotal_payment += $get("payments.{$key}.nominal_plus");
                                    }

                                    $grand_total = $get('grand_total');
                                    if ($grand_total != null) {
                                        $grand_total = $get('grand_total');
                                    } else {
                                        $grand_total = 0;
                                    }

                                    $total_cashback = $subtotal_payment - $grand_total;
                                    if ($total_cashback >= 0) {
                                        $set('is_paid', true);
                                    } else {
                                        $set('is_paid', false);
                                    }
                                    $set('total_cashback', $total_cashback);
                                    return Number::currency($total_cashback, 'IDR');
                                }),

                            Hidden::make('total_cashback')
                                ->default(0),

                            Hidden::make('total_payment')
                                ->default(0)

                        ])->columns(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4])
                    ])
                ])->columnSpanFull()
                    ->afterStateHydrated(function ($record) {
                        if (!is_null($record)) {
                            if (Auth::user()->branch_id != $record->branch_id) {
                                return redirect('/admin/orders');
                            }
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('55s')
            ->modifyQueryUsing(function (Builder $query) {
                return $query->addSelect([
                    'created' => User::query()->select('name')
                        ->whereColumn('id', 'created_by'),
                    'updated' => User::query()->select('name')
                        ->whereColumn('id', 'updated_by'),
                ]);
            })

            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('q')
                    ->label('Q')
                    ->sortable(),

                IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                // ->searchable(isIndividual: true),

                TextColumn::make('grand_total')
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->sortable()
                    ->alignRight()
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total')),
                TextColumn::make('total_payment')
                    ->label('Terbayar')
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->sortable()
                    ->alignRight()
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total')),
                TextColumn::make('total_cashback')
                    ->label('Kurang/Kembali')
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->sortable()
                    ->alignRight()
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total')),

                TextColumn::make('payments.payment_method')
                    ->label('Mtd')
                    ->listWithLineBreaks()
                    ->bulleted()
                // ->sortable()
                ,
                TextColumn::make('payments.rekening')
                    ->label('Rek')
                    ->listWithLineBreaks()
                    ->bulleted()
                // ->sortable()
                ,

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'canceled' => 'Canceled'
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        OrderItem::where('order_id', $record->id)->update(['status' => $state]);
                    })

                    ->sortable()
                    ->selectablePlaceholder(false),

                TextColumn::make('courier.name')

                    ->sortable(),
                TextColumn::make('user.rute')
                    ->label('Rute')

                    ->sortable(),
                TextColumn::make('total_weight')
                    ->numeric()->suffix(' kg')
                    ->sortable()
                    ->alignRight()
                    ->summarize(Sum::make()->numeric()->suffix(' kg')->label('Weight')),

                // TextColumn::make('address.fullname')
                //     ->label('for')
                //     ->sortable(),
                TextColumn::make('address.first_name')
                    ->label('FName')
                    // ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address.last_name')
                    ->label('LName')
                    // ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address.phone')
                    ->label('phone')
                    ->copyable()
                    ->copyMessage('Number phone copied')
                    ->copyMessageDuration(1500)
                    // ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('code_tr')
                    ->searchable()
                    ->sortable(),

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

                TextColumn::make('userCre.name')
                    ->label('Created by')
                    ->numeric()
                    ->sortable()

                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('userUpd.name')
                    ->label('Updated by')
                    ->numeric()
                    ->sortable()

                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('code_tr', 'desc')
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

                Filter::make('is_paid')
                    ->label('Paid')
                    ->query(fn(Builder $query): Builder => $query->where('is_paid', true)),

                Filter::make('Unpaid')
                    ->label('Unpaid')
                    ->query(fn(Builder $query): Builder => $query->where('is_paid', false)->where('status', '!=', 'canceled')),

                Tables\Filters\TrashedFilter::make()

            ])

            ->actions([
                ActionGroup::make([
                    Actions\ButtonAction::make('print')
                        ->label('Print Invoice')
                        ->url(fn(Order $record) => route('printorder', $record))
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-printer'),
                    Actions\ButtonAction::make('printdelivery')
                        ->label('Print Delivery Note')
                        ->url(fn(Order $record) => route('printorderprocess', $record))
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-truck'),
                    Actions\ButtonAction::make('phone')
                        ->openUrlInNewTab()
                        // ->hidden(fn(Order $record): bool => ! $record->exists())
                        ->url(function (Order $record) {
                            if ($record->address->phone ?? null) {
                                $phonewa = 'https://wa.me/+62' . $record->address->phone;
                            } else {
                                $phonewa = '#';
                            }
                            return url("{$phonewa}");
                        })
                        ->icon('heroicon-o-phone'),
                    Actions\ButtonAction::make('simpleview')
                        ->label('Simple View')
                        ->url(fn(Order $record) => '/my-orders/' . $record->id)
                        // ->openUrlInNewTab()
                        ->icon('heroicon-o-document'),
                    ViewAction::make(),
                    EditAction::make(),
                    RestoreAction::make(),
                ])
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\BulkAction::make(name: 'Paid?')
                        ->requiresConfirmation()
                        ->color('info')
                        ->icon('heroicon-o-credit-card')
                        ->form([
                            DateTimePicker::make('date_payment')
                                ->label('Date')
                                ->required(),
                            // Toggle::make('is_paid')
                            //     ->label('Paid ?')
                            //     ->required(),
                            ToggleButtons::make('payment_method')
                                ->label('Method')
                                ->options([
                                    'cash' => 'Cash',
                                    'transfer' => 'Transfer',
                                ])
                                ->live()
                                ->afterStateUpdated(fn(Set $set) => $set('rekening', null))
                                ->required()
                                ->grouped(),
                            Select::make('rekening')
                                ->options(fn(Get $get): array => match ($get('payment_method')) {
                                    'cash' => [
                                        'KAS UTAMA' => 'KAS UTAMA',
                                        'KAS KASIR' => 'KAS KASIR',
                                        'KAS KECIL' => 'KAS KECIL',
                                    ],
                                    'transfer' => [
                                        'BANK BCA' => 'BANK BCA',
                                        'BANK BRI' => 'BANK BRI',
                                    ],
                                    default => [
                                        'KAS UTAMA' => 'KAS UTAMA',
                                        'KAS KASIR' => 'KAS KASIR',
                                        'KAS KECIL' => 'KAS KECIL',
                                        'BANK BCA' => 'BANK BCA',
                                        'BANK BRI' => 'BANK BRI',
                                    ],
                                })
                                ->required()
                                ->searchable()
                                ->preload(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                // if ($data['is_paid'] == 1) {
                                if ($record->total_cashback < 0) {
                                    $paymentsNull = Payment::where('paymentable_id', $record->id)->where('paymentable_type', Order::class)->where('mutation_type', "Sales")->where('nominal_plus', 0)->get();
                                    foreach ($paymentsNull as $payment) {
                                        $payment->delete();
                                    }

                                    $payment = new Payment();
                                    $payment->date_payment = $data['date_payment'];
                                    $payment->currency = 'idr';
                                    $payment->payment_method = $data['payment_method'];
                                    $payment->rekening = $data['rekening'];
                                    $payment->nominal_plus = $record->total_cashback * -1;
                                    $payment->mutation_type = 'Sales';
                                    // $payment->debit = 'NR-DB-B-1100 CASH / BANK';
                                    // $payment->kredit = 'NR-DB-B-3000 Piutang Penjualan Barang';
                                    $payment->created_by = auth()->user()->id;
                                    $payment->updated_by = auth()->user()->id;
                                    $payment->user_id = $record->user_id;
                                    $payment->branch_id = auth()->user()->branch_id;
                                    $payment->paymentable_id = $record->id;
                                    $payment->paymentable_type = 'App\Models\Order';
                                    $payment->save();

                                    $paymentsSUM = Payment::where('paymentable_id', $record->id)->where('paymentable_type', Order::class)->where('mutation_type', "Sales")->sum('nominal_plus');
                                    // $record->is_paid = $data['is_paid'];
                                    $record->is_paid = true;
                                    $record->total_payment = $paymentsSUM;
                                    $record->total_cashback = $paymentsSUM - $record->grand_total;
                                    $record->paid_at = now();
                                    $record->save();
                                }
                                // }
                            }
                        }),

                    Tables\Actions\BulkAction::make(name: 'Status')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->color('warning')
                        ->icon('heroicon-o-flag')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->status = $data['status'];
                                $record->save();
                                OrderItem::where('order_id', $record->id)->update(['status' => $data['status']]);
                            }
                        })
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'new' => 'New',
                                    'processing' => 'Processing',
                                    'shipped' => 'Shipped',
                                    'delivered' => 'Delivered',
                                    'canceled' => 'Canceled'
                                ])
                                ->required(),
                        ]),

                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('date_order')
                    ->label('Order Date')
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('user.name')
                    ->label('Customer')
                    ->collapsible(),
            ])
            ->selectCurrentPageOnly()
            // ->recordUrl(fn(Model $record): string => OrderResource::getUrl('edit', ['record' => $record])) #menuju record ketika row di click
            ->recordUrl(null)
            ->query(function (Order $query) {
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
            AddressRelationManager::class
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // return static::getModel()::count(); ## jika ingin hitung semua
        if (Auth::user()->id != 1) {
            return static::getModel()::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 0)->count();
        } else {
            return static::getModel()::query()->where('is_paid', 0)->count();
        };
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 0)->count() > 10 ? 'danger' : 'success';
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
