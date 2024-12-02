<?php

namespace App\Filament\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Pages\Page;
use Saade\FilamentFullCalendar\Data\EventData;

class TaskCleandar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string $view = 'filament.pages.task-cleandar';
//    protected static ?string $navigationGroup = 'Task Calender';

    public static function getNavigationLabel(): string
    {

        return __('filament.taskCalendar');
    }
    public static function getModelLabel(): string
    {

        return __('filament.taskCalendar');
    }




}
