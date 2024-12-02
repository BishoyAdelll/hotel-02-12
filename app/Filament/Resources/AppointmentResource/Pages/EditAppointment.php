<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Enums\Status;
use App\Filament\Resources\AppointmentResource;
use App\Models\Collection;
use App\Models\Hall;
use App\Models\Reservation;
use App\Models\Task;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidDateException;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

//    protected function handleRecordUpdate(Model $record, array $data): Model
//    {
//       if($data['is_edited'] ==true){
//
//           $checkInDate = Carbon::parse($data['check_in_date']);
//           $checkOutDate = Carbon::parse($data['check_out_date']);
//           try {
//               // Validate check-out date (can be moved to a validation rule)
//               if ($checkOutDate->isBefore($checkInDate)) {
//                   throw new InvalidDateException(__('The check-out date cannot be before the check-in date.'),null);
//               }
//
//               // Access existing appointment (adjust based on your setup)
//               $appointment = $record;
//
//               // Detach existing halls (optional)
//               $appointment->halls()->detach();
//
//               $appointment->fill($data);
//               $appointment->save();
//
//               // Attach the newly selected halls
//               $appointment->halls()->attach($data['hall_id']);
//
//               return $appointment;
//           } catch (InvalidDateException $e) {
//               throw $e; // Re-throw the exception for handling at a higher level
//           }
//       }else{
//           return $data;
//       }
//
//
//    }

    public function afterSave(): void
    {
        $record =$this->record;
        $reservation= Reservation::where('appointment_id',$record->id)->get();
        $collections= Collection::where('appointment_id',$record->id)->get();

        $task= Task::where('appointment_id',$record->id)->first();
        if($record['status'] === Status::Cancelled->value){
                foreach ($reservation as $item){
                    $item->delete();
                }
                foreach ($collections as $item){
                    $item->delete();
                }
                $task->update([
                    'status' => Status::Cancelled->value
                ]);
        }

        if($record->is_edited == true)
        {
            $hallNumbers = "";

            foreach ($record->halls as $hall) {
                $hallNumbers .= $hall->number . ' , ';

                // Remove the trailing comma
                $hallNumbers = rtrim($hallNumbers, ',');

            }

            $record->halls->each(function ($hall) use ($record) {
                $hall->reservations()->updateOrInsert([
                    'appointment_id' => $record->id,
                    'hall_id' =>$hall->id,
                    'check_in' => $record->check_in_date,
                    'check_out' => $record->check_out_date,
                ]);
            });


            // dd( $hallNumbers);

            // dd($this->record->halls);
//            $hall=Hall::where('id',$record->hall_id)->first();
            $task->update([
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
