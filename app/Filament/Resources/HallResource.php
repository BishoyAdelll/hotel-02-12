<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HallResource\Pages;
use App\Filament\Resources\HallResource\RelationManagers;
use App\Models\Hall;
use App\Models\Structure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class HallResource extends Resource
{
    protected static ?string $model = Hall::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup='Partitions';

    public static function getNavigationLabel(): string
    {

        return __('filament.hall');
    }
    public static function getModelLabel(): string
    {

        return __('filament.hall');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('structure_id')
                    ->native(false)
                    ->preload()
                    ->relationship('structure','code'),
                    Forms\Components\Select::make('capacity_id')
                    ->native(false)
                    ->preload()
                    ->relationship('capacity','capacity'),
                    Forms\Components\TextInput::make('number')
                        // ->label(__('filament.hall_name'))
                        ->live()
                        ,


                    Forms\Components\TextInput::make('floor')
                        // ->label(__('filament.hall_address'))
                        ->required()
                        ->maxLength(255)
                        ,

                    Forms\Components\TextInput::make('room_price')
                        // ->label(__('filament.hall_hall_price'))
                        ->required()
                        ->numeric()
                        ,

                ])->columns(2),
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        // ->label(__('filament.hall_image'))
                        ->multiple()
                        ->downloadable()
                        ->imageEditor()
                        ->image()
                        ->openable()
                        ,
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    // ->label(__('filament.hall_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('floor')
                    // ->label(__('filament.hall_slug'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('structure.details')
                    // ->label(__('filament.hall_slug'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('room_price')
                    // ->label(__('filament.hall_address'))
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHalls::route('/'),
            'create' => Pages\CreateHall::route('/create'),
            'view' => Pages\ViewHall::route('/{record}'),
            'edit' => Pages\EditHall::route('/{record}/edit'),
        ];
    }
}
