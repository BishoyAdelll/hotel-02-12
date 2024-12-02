<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdditionResource\Pages;
use App\Filament\Resources\AdditionResource\RelationManagers;
use App\Models\Addition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AdditionResource extends Resource
{
    protected static ?string $model = Addition::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $navigationGroup='Partitions';
    public static function getNavigationLabel(): string
    {

        return __('filament.Addition');
    }
    public static function getModelLabel(): string
    {

        return __('filament.Addition');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('filament.addition-name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('description')
                        ->label(__('filament.addition-description'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('price')
                        ->label(__('filament.addition-price'))
                        ->required()
                        
                        ->prefix('EG'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.addition-name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament.addition-description'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('filament.addition-price'))
//                    ->money()
                    ->sortable(),
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
                    ExportBulkAction::make(),
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
            'index' => Pages\ListAdditions::route('/'),
            'create' => Pages\CreateAddition::route('/create'),
            'view' => Pages\ViewAddition::route('/{record}'),
            'edit' => Pages\EditAddition::route('/{record}/edit'),
        ];
    }
}
