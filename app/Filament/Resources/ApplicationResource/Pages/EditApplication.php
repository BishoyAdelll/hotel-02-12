<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Enums\Status;
use App\Filament\Resources\ApplicationResource;
use App\Models\Task;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;
    public function afterSave(): void
    {
        $record =$this->record;
       
        $task= Task::where('application_id',$record->id)->first();
        if($record['status'] === Status::Cancelled->value){
                $task->update([
                    'status' => Status::Cancelled->value
                ]);
        }


        $task->update([
            'user_id' => Auth::user()->id,
            'description' =>'order Number '.$record->number.'Customer Name '.$record->customer->name.'church Name '.$record->church_name.'priest Name '.$record->priest_name,        
            'start_date' => $record->start_date,
            'end_date' => $record->start_date,
            'application_id' => $record->id,
            'hall' => 'Day use',
            'status' => $record->status
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
