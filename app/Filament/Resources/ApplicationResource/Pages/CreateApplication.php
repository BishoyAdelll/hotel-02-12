<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Collection;
use App\Models\Task;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;
    protected function afterCreate(): void
    {
        $record=$this->record;
        if($record->attachments)
        {
            foreach ($record->attachments as $attachment){
                $collection = new Collection();
                $collection->attachment_id=$attachment->attachment_id;
                $collection->application_id=$attachment->application_id;
                $collection->start_time=$attachment->start_time;
                $collection->end_time=$attachment->end_time;
                $dateTime = new \DateTime($attachment->start_time);
                $date = $dateTime->format('Y-m-d');
                $collection->booked_at=$date;
                $collection->save();
            }
        }

        Task::create([
            'user_id' => Auth::user()->id,
            'description' =>'order Number '.$record->number.'Customer Name '.$record->customer->name.'church Name '.$record->church_name.'priest Name '.$record->priest_name,
            'start_date' => $record->start_date,
            'end_date' => $record->start_date,
            'application_id' => $record->id,
            'hall' => 'Day use',
            'status' => $record->status
        ]);

    }
}

