<?php

namespace App\Filament\Resources;

use App\Enums\Payment;
use App\Enums\Status;
use App\Enums\Type;
use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Addition;
use App\Models\Application;
use App\Models\Attachment;
use App\Models\Component;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup='Reservation';
    protected static ?string $navigationLabel = 'Day Use';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->label(__('Order number'))
                            ->readOnly(true)
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->default('OR-' . random_int(100000, 9999999)),

                        Forms\Components\Select::make('customer_id')
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->label('fullName')
                            ->relationship('customer','full_name'),

                        Forms\Components\Select::make('status')
                            ->native(false)
                            ->preload()
                            ->label('status')
                            ->Options(Status::class),
                        Forms\Components\Select::make('payment')
                            ->native(false)
                            ->preload()
                            ->label('Payment')
                            ->Options(Payment::class),
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ,
                    ])->columns(2),
                Forms\Components\Section::make('Reservation Information')
                    ->schema([
                        Forms\Components\Checkbox::make('open_additions')
                            ->label('open Attachments')
                            ->inline()
                            ->live()
                            ->default(0),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('components')
                            ->label(__('components'))
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label(__('type'))
                                    ->options(
                                        Type::class
                                    )
                                    ->live()
                                    ->required(),
                                Forms\Components\Select::make('component_id')
                                    ->label(__('components'))
                                    ->required()
                                    ->options(function (Get $get): Collection {
                                        return Component::query()->where('type',$get('type'))->pluck('title','id');
                                    })
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $addition = Component::find($state);
                                        $set('price', $addition->price);
                                        $set('total', $addition->price);
                                    }),
                                Forms\Components\TextInput::make('price')
                                    ->label(__('price'))
                                    ->required()
                                    ->reactive()
                                    ->readOnly()
                                    ->numeric(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('quantity'))
                                    ->required()
                                    ->afterStateUpdated(function ($set, $get) {
                                        $total = $get('price') * $get('quantity');
                                        $set('total',$total);
                                    })
                                    ->live(true)
                                    ->default(1)
                                    ,
                                Forms\Components\TextInput::make('total')
                                    ->label(__('total'))
                                    ->required()
                                    ->reactive()
                                    ->readOnly()
                                    ->numeric(),
                            ])->columns(2),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('additions')
                            ->label(__('additions'))
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('addition_id')
                                    ->label(__('additions'))
                                    ->required()
                                    ->options(Addition::query()->pluck('name', 'id'))->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $addition = Addition::find($state);
                                        $set('price', $addition->price);
                                        $set('total', $addition->price);
                                    }),
                                Forms\Components\TextInput::make('price')
                                    ->label(__('price'))
                                    ->required()
                                    ->reactive()
                                    ->readOnly()
                                    ->numeric(),Forms\Components\TextInput::make('quantity')
                                    ->label(__('quantity'))
                                    ->required()
                                    ->afterStateUpdated(function ($set, $get) {
                                        $total = $get('price') * $get('quantity');
                                        $set('total',$total);
                                    })
                                    ->live(true)
                                    ->default(1)
                                        ,
                                    Forms\Components\TextInput::make('total')
                                    ->label(__('total'))
                                    ->required()
                                    ->reactive()
                                    ->readOnly()
                                    ->numeric(),
                            ])->columns(2),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('attachments')
                            ->label(__('attachments'))
                            ->relationship()
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_time')
                                    ->label(__('Start Time '))
                                    ->required()
                                    ->seconds(false)
                                    ->live(onBlur: true)
                                    ->time(),
                                Forms\Components\DateTimePicker::make('end_time')
                                    ->label(__('End Time'))
                                    ->required()
                                    ->seconds(false)
                                    ->live(onBlur: true)
                                    ->time(),

                                Forms\Components\Select::make('attachment_id')
                                    ->label(__('attachments'))
                                    // ->relationship('attachment')
                                    ->required()
                                    // ->native(false)
                                    ->live()
                                    ->options(function (Get $get): Collection {
                                        $checkDate = new \DateTime(Carbon::parse($get('start_time'))->toDateString()); // Convert to DateTime object
                                        $checkStartTime = Carbon::parse($get('start_time'));
                                        $checkEndTime = Carbon::parse($get('end_time'));
                                        $nextDay = clone $checkDate; // Create a copy for comparison
                                        $nextDay->modify('+1 day'); // Add 1 day to the copy

                                        return
                                            Attachment::query()
                                                ->whereDoesntHave('collections', function ($query) use ($checkStartTime, $checkEndTime, $checkDate, $nextDay) {
                                                    $query->where(function ($query) use ($checkStartTime, $checkEndTime) {
                                                        $query->where('start_time', '<', $checkStartTime) // Bookings must end before chosen start time
                                                        ->where('end_time', '>', $checkEndTime); // Bookings must start after chosen end time
                                                    })
                                                        ->orWhere(function ($query) use ($checkDate, $checkStartTime, $checkEndTime, $nextDay) {
                                                            $query->where('start_time', '<=', $checkStartTime) // Bookings can start before or at chosen start time
                                                            ->where('end_time', '>=', $checkEndTime); // Bookings can end after or at chosen end time
                                                            $query->where(function ($query) use ($checkDate, $nextDay) {
                                                                $query->where('booked_at', '>=', $checkDate->format('Y-m-d 00:00:00')) // Bookings must be on or after the chosen date's start
                                                                ->where('booked_at', '<', $nextDay->format('Y-m-d 00:00:00')); // Bookings must be before the next day's start
                                                            });
                                                        });
                                                })
                                                ->pluck('name', 'id');
                                    }) ,
                            ])->columns(3),
                    ])->hidden(fn(Get $get): bool => !$get('open_additions') ),


                    Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('insurance')
                        ->label(__('insurance'))
                        ->required()
                        ->default(0),
                    Forms\Components\TextInput::make('discount')
                        ->label(__('discount'))
                        //                        ->label(__('filament.discount'))
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->suffix('%')
                        ->reactive(),
                    Forms\Components\TextInput::make('tax')
                        ->label(__('tax'))
                        //                        ->label('اضافة ضريبة على الفاتورة الكلية ')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->reactive(),
                    Forms\Components\Placeholder::make('grand_total')
                        ->label(__('grand_total'))
                        //                        ->label('Grand Total')
                        ->content(function (callable $get, $set) {
                            $scope = $get('hall_price');
                            $subtotal =  collect($get('additions'))->pluck('total')->sum();
                            $mailTotal =  collect($get('components'))->pluck('total')->sum();
                            $discount = $get('discount');
                            $tax = $get('tax');
                            $paid = $get('deposit');
                            $subtotal = intval($subtotal + $scope + $mailTotal);
                            $discount = intval($discount);
                            $tax = intval($tax);
                            $paid = floatval($paid);

                            $grandTotal = ($subtotal) - ($subtotal * ($discount / 100)) + ($subtotal - ($subtotal * ($discount / 100))) * ($tax / 100);
                            $finalTotal = ($grandTotal) - $paid;
                            $set('grand_total', $finalTotal);
                            $set('after_discount', $grandTotal);
                            return $grandTotal;
                        }),
                    Forms\Components\TextInput::make('deposit')
                        ->label(__('deposit'))
                        ->required()
                        ->numeric()
                        ->default(0.00)
                        ->live(true)
                        ->columns(2),
                    Forms\Components\TextInput::make('grand_total')
                        ->label(__('grand_total'))
                        ->required()
                        ->readOnly()
                        ->reactive(),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.church_name')
                    ->label('church Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.meeting_name')
                    ->label('Meting Name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.responsible_name')
                    ->label('Responsible  Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('payment'),
                Tables\Columns\TextColumn::make('start_date')
                    ->searchable()
                    ->label('date')
                    ->sortable()
                    ->badge()
                    ,

                Tables\Columns\TextColumn::make('is_edited')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('insurance')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deposit')
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('view pdf')
                        ->label('عرض الفاتورة')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Application $record) => route('ViewApplication', $record))
                        ->openUrlInNewTab(),
                 Tables\Actions\DeleteAction::make()
                       ->after(function (Application $record) {
                           Task::query()->where('application_id', $record->id)->delete();
                           Notification::make()
                               ->title('The Task is deleted successfully')
                               ->danger()
                               ->body('this Appointment its well deleted from calender')
                               ->duration(5000)
                               ->send();
                       })
                ])

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
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'view' => Pages\ViewApplication::route('/{record}'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
