<?php

namespace App\Filament\Resources\PorderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Support\Collection;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->required()
                    ->tel()
                    ->maxLength(20),

                TextInput::make('zip_code')
                    ->numeric()
                    ->maxLength(10),

                Select::make('state')
                    ->options(Province::query()->pluck('name', 'code'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('city', null);
                    }),

                Select::make('city')
                    ->options(function (Get $get): Collection {
                        return City::query()->orderBy('name', 'ASC')->where('province_code', $get('state'))->pluck('name', 'code');
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('district', null);
                    }),

                Select::make('district')
                    ->options(function (Get $get): Collection {
                        return District::query()->orderBy('name', 'ASC')->where('city_code', $get('city'))->pluck('name', 'code');
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('village', null);
                    }),

                Select::make('village')
                    ->options(function (Get $get): Collection {
                        return Village::query()->orderBy('name', 'ASC')->where('district_code', $get('district'))->pluck('name', 'code');
                    })
                    ->searchable()
                    ->required(),

                Textarea::make('street_address')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->modifyQueryUsing(function (Builder $query) {
                return $query->addSelect([
                    'provinsi' => Province::query()->select('name')
                        ->whereColumn('code', 'state'),
                    'kabupaten' => City::query()->select('name')
                        ->whereColumn('code', 'city'),
                    'kecamatan' => District::query()->select('name')
                        ->whereColumn('code', 'district'),
                    'desa' => Village::query()->select('name')
                        ->whereColumn('code', 'village'),
                ]);
            })
            ->recordTitleAttribute('street_address')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        TextColumn::make('fullname')
                            ->label('Full Name'),
                        TextColumn::make('phone')->prefix('Phone '),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        TextColumn::make('street_address'),
                        TextColumn::make('zip_code')->prefix('Kode Pos ')->disabled(),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        TextColumn::make('provinsi'),
                        TextColumn::make('kabupaten'),
                        TextColumn::make('kecamatan'),
                        TextColumn::make('desa'),
                    ]),

                ])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
