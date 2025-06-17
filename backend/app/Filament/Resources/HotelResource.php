<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Filament\Resources\HotelResource\RelationManagers;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Название отеля')
                    ->required()
                    ->maxLength(255),
                TextInput::make('city')
                    ->label('Город')
                    ->required()
                    ->maxLength(255),
                // Если есть другие поля, добавьте их, например:
                // TextInput::make('address')
                //     ->label('Адрес')
                //     ->maxLength(255),
                // Forms\Components\Select::make('stars')
                //     ->label('Звёзды')
                //     ->options([
                //         1 => '1 звезда',
                //         2 => '2 звезды',
                //         3 => '3 звезды',
                //         4 => '4 звезды',
                //         5 => '5 звёзд',
                //     ]),
                // Forms\Components\Textarea::make('description')
                //     ->label('Описание')
                //     ->rows(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->label('Hotel Name')->sortable()->searchable(),
                TextColumn::make('city')->label('City')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime()->label('Created'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}
