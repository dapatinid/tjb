<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JumboTronResource\Pages;
use App\Filament\Resources\JumboTronResource\RelationManagers;
use App\Models\JumboTron;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class JumboTronResource extends Resource
{
    protected static ?string $model = JumboTron::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Grid::make()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                // ->readOnly(fn ($record) => !is_null($record)) membuat tidak dapat diedit
                                ->maxLength(255),

                            Select::make('target')
                                ->required()
                                ->options([
                                    'home' => 'Home',
                                    'category' => 'Category'
                                ])
                        ]),

                    TextInput::make('link')
                        ->default('#')
                        ->maxLength(255),

                    FileUpload::make('image')
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->directory('jumbotron'),

                    Toggle::make('is_active')
                        ->required()
                        ->default(true)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('link')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            ]);
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
            'index' => Pages\ListJumboTrons::route('/'),
            'create' => Pages\CreateJumboTron::route('/create'),
            'edit' => Pages\EditJumboTron::route('/{record}/edit'),
        ];
    }
}
