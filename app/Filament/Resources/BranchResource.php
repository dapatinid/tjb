<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Filament\Resources\BranchResource\RelationManagers;
use App\Models\Branch;
use App\Models\Partner;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Str;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationGroup = 'Branch';
    protected static ?int $navigationSort = 22;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Grid::make()
                        ->schema([

                            Hidden::make('created_by')
                                ->default(fn() => Auth::user()->id) ## keperluan untuk memilih user setelah ada auth
                                // ->disabledOn('edit')
                                ->required(),

                            Hidden::make('updated_by')
                                ->default(fn() => Auth::user()->id) ## keperluan untuk memilih user setelah ada auth
                                ->required(),

                            FileUpload::make('logo')
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper()
                                ->maxSize(1024)
                                ->directory(function (Get $get) {
                                    return 'branches/' . $get('id');
                                })
                                ->columnSpanFull(),

                            TextInput::make('name')
                                ->required()
                                // ->readOnly(fn ($record) => !is_null($record)) membuat tidak dapat diedit
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'edit' ? $set('slug', Str::slug($state)) : null)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $namaMitra = Partner::where('id', Auth::user()->partner_id)->value('name');
                                    $set('name_partner', $namaMitra);
                                }),

                            TextInput::make('slug')
                                ->required()
                                ->disabled()
                                ->maxLength(255)
                                ->dehydrated()
                                ->unique(Branch::class, 'slug', ignoreRecord: true),

                            TextInput::make('phone')
                                ->required()
                                ->tel()
                                ->maxLength(20)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $namaMitra = Partner::where('id', Auth::user()->partner_id)->value('name');
                                    $set('name_partner', $namaMitra);
                                }),

                            Textarea::make('street_address')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $namaMitra = Partner::where('id', Auth::user()->partner_id)->value('name');
                                    $set('name_partner', $namaMitra);
                                }),

                            Select::make('type')
                                ->required()
                                ->multiple()
                                ->options([
                                    'digital' => 'Digital',
                                    'crowfunding' => 'Galang Dana',
                                    'investasi' => 'Investasi',
                                    'grosir' => 'Grosir',
                                    'retail' => 'Retail',
                                    'sewa' => 'Sewa',
                                    'transportasi' => 'Transportasi',
                                    'fnb' => 'Makanan & Minuman',
                                    'laundry' => 'Laundry',
                                    'eo' => 'Event Organizer',
                                    'tiket' => 'Tiket',
                                    'bengkel' => 'Bengkel',
                                    'perbaikan' => 'Perawatan & Perbaikan',
                                    'cleaning' => 'Cleaning Service',
                                ]),

                            Select::make('partner_id')
                                ->default(fn() => Auth::user()->partner_id)
                                ->required()
                                ->options(function (Get $get): SupportCollection {
                                    return Partner::query()->orderBy('name', 'ASC')->pluck('name', 'id');
                                })
                                ->searchable(),

                            Hidden::make('name_partner')
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                        ]),

                    FileUpload::make('image')
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->directory(function (Get $get) {
                            return 'branches/' . $get('id');
                        }),

                    Grid::make()
                        ->schema([
                            Toggle::make('is_open')
                                ->required()
                                ->default(true),
                            Toggle::make('is_active')
                                ->required()
                                ->default(true)
                        ])


                ])
                    ->afterStateHydrated(function ($record) {
                        if (!is_null($record)) {
                            if (Auth::user()->partner_id != $record->partner_id) {
                                return redirect('/admin/branches');
                            }
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->addSelect([
                    'created' => User::query()->select('name')
                        ->whereColumn('id', 'created_by'),
                    'updated' => User::query()->select('name')
                        ->whereColumn('id', 'updated_by'),
                ]);
            })
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_open')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->boolean(),
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
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (Collection
                    $records) {
                        foreach ($records as $key => $value) {
                            if ($value->image) {
                                Storage::disk('public')->delete($value->image);
                            }
                        }
                    }),
                ]),
            ])
            ->query(function (Branch $query) {
                if (Auth::user()->id != 1) {
                    return $query->where('partner_id', Auth::user()->partner_id);
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
