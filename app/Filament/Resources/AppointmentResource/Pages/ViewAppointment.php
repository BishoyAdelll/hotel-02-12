<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Addition;
use App\Models\Appointment;
use App\Models\AppointmentAddition;
use App\Models\AppointmentHall;
use App\Models\Hall;
use App\Models\Time;
use Filament\Actions;
use Filament\Forms\Components\Split;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Split::make([
                    \Filament\Infolists\Components\Section::make('Booking Details')
                        ->description('The Details of Booking ')
                        ->schema([
                            TextEntry::make('the_numbers_of_days')->label('duration')->inlineLabel()->listWithLineBreaks()->bulleted()->suffix('  days'),
                            TextEntry::make('hall_price')->inlineLabel()->listWithLineBreaks()->bulleted(),
                            TextEntry::make('discount')->inlineLabel()->listWithLineBreaks()->bulleted(),
                            TextEntry::make('tax')->inlineLabel()->listWithLineBreaks()->bulleted(),
                            TextEntry::make('paid')->inlineLabel()->listWithLineBreaks()->bulleted(),
                            TextEntry::make('grand_total')->inlineLabel()->listWithLineBreaks()->bulleted(),
                        ]),
                ]),
                \Filament\Infolists\Components\Split::make([
                Section::make('additions')

                    ->schema([
                        RepeatableEntry::make('additions')
                            ->label('additions')
                            ->schema([
                                TextEntry::make('value')
                                    ->label(fn (AppointmentAddition $record)=>Addition::query()->where('id',$record->addition_id)->value('name'))
                                    ->state(function (AppointmentAddition $record) {
                                        return $record->price .' Booked At ' .$record->booked_at ;
                                    })
                                    ->openUrlInNewTab()
                                    ->inlineLabel()

                            ]),
                    ]),

                ]),


                    \Filament\Infolists\Components\Section::make('Date and Time  Details')
                        ->description('The Appointment chosen ')
                        ->schema([
                            TextEntry::make('check_in_date')->inlineLabel()->date(),
                            TextEntry::make('check_out_date')->inlineLabel()->date(),
                        ])->columns(2),
                        Section::make('halls')

                    ->schema([
                        RepeatableEntry::make('halls')
                            ->label('halls')
                            ->schema([
                                TextEntry::make('number')

                                    ->inlineLabel()
                            ])->columns(2),
                    ]),

                ]);

    }
}
