<?php

namespace App\Livewire;

// use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Saade\FilamentFullCalendar\Actions;
use App\Enums\Status;
use App\Filament\Resources\AppointmentResource;
use App\Filament\Resources\RecordResource;
use App\Filament\Resources\TaskResource;
use App\Models\Appointment;
use App\Models\Record;
use App\Models\RecordTime;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class TaskCalendar extends FullCalendarWidget
{

    public function fetchEvents(array $fetchInfo): array
    {

        return
            Task::query()
            ->where('start_date', '>=', $fetchInfo['start'])
            ->where('end_date', '<=', $fetchInfo['end'])
            ->when(!auth()->user()->is_admin, function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->get()
            ->map(
                fn (Task $task) => EventData::make()
                    ->id($task->id)
                    ->title( $task->hall )
                    ->allDay()
                    ->start($task->start_date)
                    ->end($task->end_date)
                    ->backgroundColor($task->status === Status::Booked->value ? '#38bdf8' : ($task->status === Status::Cancelled->value ? '#FE0000' : '#15F5BA'))
                    ->borderColor($task->status === Status::Booked->value ? '#38bdf8' : ($task->status === Status::Cancelled->value ? '#FE0000' : '#15F5BA'))
                    ->textColor('primary')
                    ->url(TaskResource::getUrl('view', [$task->id]))
                    ->toArray()
            )
            ->toArray();
    }


}
