<?php

namespace App\Filament\Resources;

use App\Enums\Payment;
use App\Enums\Status;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Addition;
use App\Models\Appointment;
use App\Models\Capacity;
use App\Models\Hall;
use App\Models\Meal;
use App\Models\Post;
use App\Models\Record;
use App\Models\RecordTime;
use App\Models\Reservation;
use App\Models\Snack;
use App\Models\Task;
use App\Models\Time;
use App\Models\Tool;
use Carbon\Carbon;
use DateTime;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Mpdf\Tag\Section;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use PHPUnit\Metadata\Group;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Structure;
use App\Models\Attachment;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup='Reservation';
    public static function getNavigationLabel(): string
    {

        return __('auth.Appointment');
    }
    public static function getModelLabel(): string
    {

        return __('auth.Appointment');
    }

    //    protected static ?string $navigationLabel='Appointment';
    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'customer.full_name', 'customer.phone'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $roomNumbers= "";
        foreach ($record->halls as $hall)
        {
            $roomNumbers .= $hall->number. ", ";
        }
        $roomNumbers= trim($roomNumbers,", ");

        return [
            'number' => $record->number,
            'full_name' => $record->customer->full_name,
            'rooms' =>$roomNumbers , // Pass the string containing all room numbers
            'check_in_date' => $record->check_in_date,
            'check_out_date' => $record->check_out_date,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->label(__('Order number'))
                            ->readOnly(true)
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
                    ])->columns(2),

                        Forms\Components\Checkbox::make('is_edited')
                            ->label('Individuals')
                            ->inline()
                            ->live()
                            ->default(0)->hiddenOn('edit'),


                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\DatePicker::make('check_in_date')
                            ->required(),
                        Forms\Components\DatePicker::make('check_out_date')
                            ->required(),
                        Forms\Components\Select::make('structure_id')
                            ->options(Structure::all()->pluck('details', "id"))
                            ->live()
                            ->preload()
                            ->native(false)
                            ->required()
                        ,
                        Forms\Components\Select::make('hall_id')

                            ->label(__('filament.hall_id'))
                            ->required()
                            ->live()
                            ->multiple()
                            ->options(function (Get $get): Collection {
                                $structure_id = $get('structure_id') ;
                                $checkInDate = Carbon::parse($get('check_in_date'));
                                $checkOutDate = Carbon::parse($get('check_out_date'));

                                return Hall::query()
                                    ->whereDoesntHave('reservations', function ($query) use ($checkInDate, $checkOutDate,$structure_id) {
                                        $query->where(function ($query) use($checkOutDate,$checkInDate){
                                            $query->where('check_in', '<=', $checkOutDate)
                                                ->where('check_out', '>', $checkInDate);
                                        });
                                    })
                                    ->where(function ($query) use($structure_id){
                                        if(isset($structure_id)){
                                            $query->where('structure_id', $structure_id);
                                        }
                                    })
                                    ->pluck('number', 'id');
                            })
                            ->afterStateUpdated(function ($state, callable $set, $get ) {
                                $totalPrice = 0;
                                $selectedHalls = [];
                                $checkInDate = Carbon::parse($get('check_in_date'));
                                $checkOutDate = Carbon::parse($get('check_out_date'));
                                if(isset($state)){
                                    foreach ($state as $roomId) {
                                        $room = Hall::find($roomId);
                                        $totalPrice += $room->room_price;
                                        $selectedHalls[] = [
                                            'id' => $roomId,
                                            'price' => $room->price,
                                        ];
                                    }
                                }

                                $diffInDays = $checkOutDate->diffInDays($checkInDate);
                                $set('the_numbers_of_days', $diffInDays);
                                $set('hall_price', $totalPrice * $diffInDays);
                                $set('selected_halls', $selectedHalls);
//                                $set('hall_id', $selectedHalls);

                            })

                            ->native(false)
                            ->preload()
                        ,
                        Forms\Components\TextInput::make('hall_price')
                            ->label(__('Room Price'))
                            ->required()
                            ->live()
                            ->readOnly()
                            ->numeric(),
                        Forms\Components\TextInput::make('the_numbers_of_days')
                            ->label(__('duration'))
                            ->required()
                            ->live()
                            ->readOnly()
                            ->numeric(),
                    ])->columns(2)->visible(fn(Get $get): bool => !$get('is_edited') )->hiddenOn('edit'),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\DatePicker::make('check_in_date')
                            ->required(),
                        Forms\Components\DatePicker::make('check_out_date')
                            ->required(),
                        Forms\Components\Select::make('structure_id')
                            ->options(Structure::all()->pluck('details', "id"))
                            ->live()
                            ->preload()
                            ->native(false)
                            ->required()
                        ,
                        Forms\Components\Select::make('hall_id')

                            ->label(__('filament.hall_id'))
                            ->required()
                            ->live()
                            ->multiple()
                            ->options(function (Get $get): Collection {
                                $structure_id = $get('structure_id') ;
                                $checkInDate = Carbon::parse($get('check_in_date'));
                                $checkOutDate = Carbon::parse($get('check_out_date'));

                                return Hall::query()
                                    ->whereDoesntHave('reservations', function ($query) use ($checkInDate, $checkOutDate,$structure_id) {
                                        $query->where(function ($query) use($checkOutDate,$checkInDate){
                                            $query->where('check_in', '<=', $checkOutDate)
                                                ->where('check_out', '>', $checkInDate);
                                        });
                                    })
                                    ->where(function ($query) use($structure_id){
                                       if(isset($structure_id)){
                                           $query->where('structure_id', $structure_id);
                                       }
                                    })
                                    ->pluck('number', 'id');
                            })
                            ->native(false)
                            ->preload(),

                        Forms\Components\TextInput::make('capacity')
                            ->label(__('quantity'))
                            ->required()
                            ->live(true)
                            ->numeric(),
                        Forms\Components\TextInput::make('person_price')
                            ->label(__('person_price'))
                            ->required()
                            ->live(true)
                            ->numeric(),
                        Forms\Components\TextInput::make('hall_price')
                            ->label(__('total_person_price'))
                            ->required()
                            ->live(true)
                            ->readOnly()
                            ->numeric(),
                        Placeholder::make('')
                            ->content(function (callable $get, $set){
                                $capacity = $get('capacity');
                                $person_price = $get('person_price');
                                $set('hall_price' ,$capacity * $person_price );
                            }),

                    ])->columns(2)->visible(fn(Get $get): bool => $get('is_edited') )->hiddenOn('edit'),
                Forms\Components\Checkbox::make('open_additions')
                    ->label('open Addition')
                    ->inline()
                    ->live()
                    ->default(0),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('attachments')
                            ->label(__('attachments'))

                            ->relationship('attachments')
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
//                                 ->relationship('attachment')
                                ->required()
                                // ->native(false)
                                ->live()
                                ->options(function (Get $get): Collection {
                                    $checkDate = new DateTime(Carbon::parse($get('start_time'))->toDateString()); // Convert to DateTime object
                                    $checkStartTime = Carbon::parse($get('start_time'));
                                    $checkEndTime = Carbon::parse($get('end_time'));
                                    $nextDay = clone $checkDate; // Create a copy for comparison
                                    $nextDay->modify('+1 day'); // Add 1 day to the copy
                                        if($checkEndTime->isBefore($checkStartTime))
                                        {
                                          return collect([
                                            'error' => 'the start time must be before the end time',

                                            ]);
                                        }
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
                    ])->hiddenOn('edit'),


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
                                    }),

                                Forms\Components\DateTimePicker::make('booked_at')
                                    ->native(false),

                                Forms\Components\TextInput::make('price')
                                    ->label(__('price'))
                                    ->required()
                                    ->reactive()
                                    ->readOnly()
                                    ->numeric(),

                            ])->columns(2),
                            Forms\Components\Repeater::make('tools')
                            ->relationship('tools')
                            ->schema([
                                Forms\Components\Select::make('tool_id')
                                    ->label(__('meal'))
//                                    ->relationship('tools')
                                    ->required()
                                    ->options(Tool::query()->pluck('name', 'id'))->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $addition = Tool::find($state);
                                        $set('price_male', $addition->price);
                                        $set('total', $addition->price);
                                    }),

                                Forms\Components\DateTimePicker::make('date_time')
                                    ->native(false),

                                Forms\Components\TextInput::make('price_male')
                                    ->label(__('Male Price'))
                                    ->required()
                                    ->readOnly(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('quantity'))
                                    ->live(onBlur: true)
                                    ->default(1)
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $bishoy = $get('price_male') * $state;
                                        $set('total', $bishoy);
                                    }),
                                Forms\Components\TextInput::make('total')
                                    ->label(__('total'))
                                    ->required()
                                    ->reactive()
                                    ->readOnly()
                                    ->numeric(),

                        ])->columns(2),
                    ])->hidden(fn(Get $get): bool => !$get('open_additions') ),
                    Forms\Components\RichEditor::make('program')
                    ->columnSpanFull(),

                Forms\Components\Group::make([
                    Forms\Components\Section::make()
                        ->schema([
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
                                    $subtotal =  collect($get('additions'))->pluck('price')->sum();
                                    $mailTotal =  collect($get('tools'))->pluck('total')->sum();
                                    $discount = $get('discount');
                                    $tax = $get('tax');
                                    $paid = $get('paid');
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
                            Forms\Components\TextInput::make('paid')
                                ->label(__('deposit'))
                                //                        ->label('المبلغ المدفوع')
                                ->required()
                                ->numeric()
                                ->default(0.00)
                                ->live(true)
                                //                        ->hidden(fn (Forms\Get $get): bool =>  $get('cash'))
                                ->columns(2),
                            Forms\Components\TextInput::make('grand_total')
                                ->label(__('grand_total'))
                                //                        ->label(' السعر المطلوب ')
                                ->required()
                                ->readOnly()
                                ->reactive(),
                            Forms\Components\TextInput::make('insurance')
                                ->label(__('insurance'))
                                ->required()
                                ->default(0)
                        ])->columns(2)
                    ,
                ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('receipt_number')
                            ->label(__('receipt_number'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('%')
                            ->reactive(),
                        Forms\Components\FileUpload::make('receipt_images')
                            ->label(__('receipt_images'))

                            ->multiple()
                            ->image(),
                        Forms\Components\TextInput::make('capacity')
                            ->label(__('capacity'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->reactive(),
                    ]),
                ]),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity.capacity')
                    ->badge(),
                Tables\Columns\TextColumn::make('halls.number')
                    ->label('Rooms')
                    ->badge()

                    ->inline(),
                Tables\Columns\TextColumn::make('check_in_date')
                    ->date(),
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(Appointment $record)=>( $record->status == Status::Cancelled->value ? 'danger' : ($record->status == Status::Booked->value ?'warning': 'success') ))
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment')

                    ->badge()
                    ->color(fn(Appointment $record)=>( $record->Payment == Payment::visa->value ? Color::Lime :Color::Amber  ))
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->suffix(' EG')
                    ->searchable(),
                Tables\Columns\TextColumn::make('receipt_number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('receipt_images')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->circular()
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Number Of people')
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
                    Tables\Actions\EditAction::make()->hidden(fn(Appointment $record) => ($record->status == Status::Cancelled->value ?true : false )),
                    Tables\Actions\DeleteAction::make(),
//                    Tables\Actions\DeleteAction::make()
//                        ->after(function (Appointment $record) {
//                            Task::query()->where('appointment_id', $record->id)->delete();
//                            Notification::make()
//                                ->title('The Task is deleted successfully')
//                                ->danger()
//                                ->body('this Appointment its well deleted from calender')
//                                ->duration(5000)
//                                ->send();
//                            Reservation::query()->where('appointment_id', $record->id)->where('check_in', $record->check_in_date)->where('check_out', $record->check_in_date)->delete();
//                            Notification::make()
//                                ->title('The Reservation is deleted successfully')
//                                ->danger()
//                                ->body('this Reservation its well deleted from calender')
//                                ->duration(5000)
//                                ->send();
//                        }),
                    Tables\Actions\Action::make('view pdf')
                        ->label('عرض الفاتورة')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Appointment $record) => route('student.pdf.view', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('Download pdf')
                        ->label('تنزيل الفاتورة')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->url(fn (Appointment $record) => route('student.pdf.dwonload', $record))
                        ->openUrlInNewTab()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
