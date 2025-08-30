<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Str;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Product';
    protected static ?int $navigationSort = 18;
    protected static ?string $recordTitleAttribute = 'alias';
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->slug;
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'variant', 'alias', 'slug'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->name,
            'variant' => $record->variant,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product Information')->schema([

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

                        TextInput::make('sku')
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->readOnly(fn($record) => !is_null($record)) # tidak dapat diedit setelah terisi
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('sku', Str::upper(Str::slug($state))) : null)
                            ->afterStateUpdated(
                                function (string $operation, $state, Set $set) {
                                    // Query Count
                                    $jumlahSKU_cek = Product::where('branch_id', Auth::user()->branch_id)->where('sku', $state)->count();
                                    if ($operation === 'create' && $jumlahSKU_cek > 0) {
                                        $set('sku', null);
                                        Notification::make()
                                            ->title('Kode produk sudah digunakan. Ganti KODE lain!')
                                            ->icon('heroicon-o-x-circle')
                                            ->iconColor('danger')
                                            ->send();
                                    }
                                },
                            ),

                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->maxLength(255),

                        TextInput::make('variant')
                            ->live(onBlur: true)
                            ->maxLength(255),

                        TextInput::make('alias')
                            ->placeholder(function (Set $set, Get $get) {
                                $namanya = $get('name');
                                $variannya = $get('variant');
                                $cabangnya = $get('branch_id');
                                if ($namanya == null) {
                                    $set('alias', '');
                                } else {
                                    $set('alias', $namanya . " " . $variannya . " " . $cabangnya);
                                }
                            })
                            ->required()
                            ->maxLength(255)
                            ->readOnly(),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(function (Set $set, Get $get) {
                                $namanya = $get('name');
                                $variannya = $get('variant');
                                $cabangnya = $get('branch_id');
                                $slugnya = $namanya . " " . $variannya . " " . $cabangnya;
                                if ($slugnya == null) {
                                    $set('slug', '');
                                } else {
                                    $set('slug', Str::slug($slugnya));
                                }
                            })
                            ->readOnly()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        TextInput::make('unit_name')
                            ->live(onBlur: true)
                            ->maxLength(255),

                        TextInput::make('weight')
                            ->default(0)
                            ->stripCharacters(',')
                            ->numeric()
                            ->required()
                            ->suffix('kg'),

                        TagsInput::make('contain')
                            ->separator(',')
                            ->reorderable(),

                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                    ])->columns(2),

                    Section::make('Images & Videos')->schema([
                        FileUpload::make('images')
                            ->image()
                            ->imageEditor()
                            ->directory(function (Get $get) {
                                return 'products/' . $get('branch_id');
                            })
                            ->maxFiles(20)
                            ->maxSize(2048)
                            ->multiple()
                            ->reorderable()
                            ->appendFiles(),



                        TagsInput::make('embed_videos')
                            ->separator(',')
                            ->reorderable(),
                    ])
                ])->columnSpan(['sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                Group::make()->schema([

                    Section::make('Associations')->schema([
                        Select::make('branch_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('branch', 'name')
                            ->default(Auth::user()->branch_id),

                        Select::make('category_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('category', 'name'),

                        Select::make('brand_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('brand', 'name'),

                        TagsInput::make('tags')
                            ->separator(',')
                            ->reorderable(),
                    ]),

                    Section::make('Price')->schema([
                        TextInput::make('cogs')
                            ->label('Cost')
                            ->default(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->live(onBlur: true)
                            ->numeric()
                            ->required()
                            ->prefix('Rp'),

                        TextInput::make('price')
                            ->default(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->live(onBlur: true)
                            ->numeric()
                            ->required()
                            ->prefix('Rp'),

                        TextInput::make('strikethroughprice')
                            ->default(0)
                            ->label('Harga Coret')
                            ->placeholder('harga coret')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $hargacoret = $get('strikethroughprice');
                                if ($hargacoret == null || $hargacoret <= 0) {
                                    $set('promo', false);
                                } else {
                                    $set('promo', true);
                                }
                            })
                            ->numeric()
                            ->prefix('Rp'),

                        TextInput::make('poin')
                            ->default(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->live(onBlur: true)
                            ->numeric()
                            ->required(),
                    ]),

                    Section::make('Status')->schema([
                        // TextInput::make('id')
                        //     ->default(Product::query()->where('id', 'id'))
                        //     ->readOnly(),
                        TextInput::make('stock')
                            ->readOnly()
                            ->placeholder(function (Get $get) {
                                $boughtqty = OrderItem::query()->where('product_id', $get('id'))->sum('p_quantity');
                                $soldqty = OrderItem::query()->where('product_id', $get('id'))->sum('quantity');
                                return $boughtqty - $soldqty;
                            }),
                        TextInput::make('low_alert')
                            ->default(1)
                            ->numeric(),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true),
                        Toggle::make('in_stock')
                            ->required()
                            ->default(true),
                        Toggle::make('is_featured')
                            ->required(),
                        Toggle::make('promo')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        TextInput::make('rating')
                            ->numeric()
                            ->maxValue(5)
                            ->required(),
                    ]),
                ])->columnSpan(['sm' => 1, 'md' => 1, 'lg' => 1, 'xl' => 1])
                    ->afterStateHydrated(function ($record) {
                        if (!is_null($record)) {
                            if (Auth::user()->branch_id != $record->branch_id) {
                                return redirect('/admin/products');
                            }
                        }
                    }),
            ])->columns(['sm' => 3, 'md' => 3, 'lg' => 3, 'xl' => 3]);
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

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                ToggleColumn::make('in_stock')
                    ->label('Stock')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('variant')
                    ->searchable()
                    ->sortable(),

                TagsColumn::make('tags')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TagsColumn::make('contain')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('category.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('brand.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('cogs')
                    ->label('Cost')
                    ->alignRight()
                    ->numeric(locale: 'nl')->prefix('Rp ')
                    ->summarize(Sum::make()->numeric(locale: 'nl')->prefix('Rp ')->label('Total'))
                    ->sortable(),
                TextColumn::make('price')
                    ->alignRight()
                    ->numeric(locale: 'nl')->prefix('Rp ')
                    ->summarize(Sum::make()->numeric(locale: 'nl')->prefix('Rp ')->label('Total'))
                    ->sortable(),

                // TextColumn::make('stock')
                //     ->state(function (Product $record): string {
                //         $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
                //         $beli = $orderitems->where('product_id', $record->id)->sum('p_quantity');
                //         $jual = $orderitems->where('product_id', $record->id)->sum('quantity');
                //         return $beli - $jual;
                //     }),

                // TextColumn::make('alert')
                //     ->state(function (Product $record): string {
                //         $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
                //         $beli = $orderitems->where('product_id', $record->id)->sum('p_quantity');
                //         $jual = $orderitems->where('product_id', $record->id)->sum('quantity');
                //         $hasil = $beli - $jual - $record->low_alert;
                //         if ($hasil <= 0) {
                //             return "LOW";
                //         } else {
                //             return "aman";
                //         }
                //     }),

                // TextColumn::make('sumpquantity')
                //     ->label('B.Qty')
                //     ->state(function (Product $record): string {
                //         $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
                //         $beli = $orderitems->where('product_id', $record->id)->sum('p_quantity');
                //         return $beli * 1;
                //     }),
                // ->summarize(Sum::make()->formatStateUsing(fn(string $state): string => $state * 1)),

                // TextColumn::make('sumquantity')
                //     ->label('S.Qty')
                //     ->state(function (Product $record): string {
                //         $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
                //         $jual = $orderitems->where('product_id', $record->id)->sum('quantity');
                //         return $jual * -1;
                //     }),
                // ->summarize(Sum::make()->formatStateUsing(fn(string $state): string => $state * -1)),

                // TextColumn::make('pquantities_sum_p_total_amount')->sum('pquantities', 'p_total_amount')
                //     ->label('Avg.Bought')
                //     ->prefix('Rp ')
                //     ->formatStateUsing(fn(string $state): string => $state * -1)
                //     ->state(function (Product $record): float {
                //         $qta = $record->pquantities_sum_p_total_amount;
                //         $qtt = $record->pquantities_sum_p_quantity;
                //         if ($qtt < 1) {
                //             $qt = 1;
                //         } else {
                //             $qt = $qtt;
                //         }
                //         return $qta / $qt;
                //     }),

                // TextColumn::make('quantities_sum_total_amount')->sum('quantities', 'total_amount')
                //     ->label('Avg.Sold')
                //     ->prefix('Rp ')
                //     ->formatStateUsing(fn(string $state): string => $state * 1)
                //     ->state(function (Product $record): float {
                //         $qta = $record->quantities_sum_total_amount;
                //         $qtt = $record->quantities_sum_quantity;
                //         if ($qtt < 1) {
                //             $qt = 1;
                //         } else {
                //             $qt = $qtt;
                //         }
                //         return $qta / $qt;
                //     }),

                // TextColumn::make('ptquantities_sum_p_total_amount')->sum('ptquantities', 'p_total_amount')
                //     ->label('T.Bought')
                //     ->prefix('Rp ')
                //     ->formatStateUsing(fn(string $state): string => $state * -1),

                // TextColumn::make('tquantities_sum_total_amount')->sum('tquantities', 'total_amount')
                //     ->label('T.Sold')
                //     ->prefix('Rp ')
                //     ->formatStateUsing(fn(string $state): string => $state * 1),

                // TextColumn::make('margin')
                //     ->prefix('Rp ')
                //     ->state(function (Product $record): float {
                //         return $record->tquantities_sum_total_amount - $record->ptquantities_sum_p_total_amount;
                //     }),

                IconColumn::make('is_featured')
                    ->sortable()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('promo')
                    ->sortable()
                    ->boolean()
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

            ])
            // ->defaultGroup('name')
            // ->groupsOnly()
            ->filters([
                Filter::make('is_active')
                    ->label('non-active')
                    ->query(fn(Builder $query): Builder => $query->where('is_active', false)),
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\BulkAction::make(name: 'Active?')
                        ->requiresConfirmation()
                        ->color('info')
                        ->icon('heroicon-o-rocket-launch')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->is_active = $data['is_active'];
                                $record->save();
                            }
                        })
                        ->form([
                            Toggle::make('is_active')
                                ->label('Active?')
                                ->required(),
                        ]),

                    Tables\Actions\BulkAction::make(name: 'Stock?')
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-cube')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->in_stock = $data['in_stock'];
                                $record->save();
                            }
                        })
                        ->form([
                            Toggle::make('in_stock')
                                ->label('Stock?')
                                ->required(),
                        ]),

                    Tables\Actions\DeleteBulkAction::make()->after(function (Collection
                    $records) {
                        foreach ($records as $key => $value) {
                            if ($value->images) {
                                Storage::disk('public')->delete($value->images);
                            }
                        }
                    }),

                ]),
            ])
            ->recordAction(Tables\Actions\ViewAction::class)
            ->recordUrl(null)
            ->query(function (Product $query) {
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
