<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\AppointmentHall;
use App\Models\Collection;
use App\Models\Hall;
use App\Models\Record;
use App\Models\RecordTime;
use App\Models\Reservation;
use App\Models\Task;
use App\Models\Time;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidDateException;
use DateTime;
use Exception;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($data);
    // }

    protected function handleRecordCreation(array $data): Model
    {
        $checkInDate = Carbon::parse($data['check_in_date']);
        $checkOutDate = Carbon::parse($data['check_out_date']);


        try {
            // Validate check-out date (can be moved to a validation rule)
            if ($checkOutDate->isBefore($checkInDate)) {
                throw new InvalidDateException(__('The check-out date cannot be before the check-in date.'),null);
            }

            $appointment = static::getModel()::create($data);

            // Attach the selected halls
            $appointment->halls()->attach($data['hall_id']);

            return $appointment;
        } catch (InvalidDateException $e) {
            throw $e; // Re-throw the exception for handling at a higher level
        }
    }


    protected function afterCreate(): void
    {
        $record=$this->record;
        // dd($record->attachments);
        if($record->attachments)
        {
            foreach ($record->attachments as $attachment){
                $collection = new Collection();
                $collection->attachment_id=$attachment->attachment_id;
                $collection->appointment_id=$attachment->appointment_id;
                $collection->start_time=$attachment->start_time;
                $collection->end_time=$attachment->end_time;
                $dateTime = new DateTime($attachment->start_time);
                $date = $dateTime->format('Y-m-d');
                $collection->booked_at=$date;
                $collection->save();
            }
        }

        $hallNumbers = "";

        foreach ($record->halls as $hall) {
            $hallNumbers .= $hall->number . ' , ';

        // Remove the trailing comma
        $hallNumbers = rtrim($hallNumbers, ',');

        }

        foreach ($record->halls as $hall) {
            Reservation::create([
                'appointment_id' => $record->id,
                'hall_id' => $hall->id,
                'check_in' => $record->check_in_date,
                'check_out' => $record->check_out_date,
            ]);

        }


        // dd( $hallNumbers);

        // dd($this->record->halls);
        $hall=Hall::where('id',$record->hall_id)->first();
        $task=Task::create([
            'user_id' => Auth::user()->id,
            // 'time' => $record->track,
            'description' =>'order Number '.$record->number.','.' man_name '.$record->man_name.','.' women Name '. $record->women_name ,
            // 'due_date' => $record->date,
            'start_date' => $record->check_in_date,
            'end_date' => $record->check_out_date,
            'appointment_id' => $record->id,
            'hall' => $hallNumbers,
            'status' => $record->status
        ]);

    }
}
