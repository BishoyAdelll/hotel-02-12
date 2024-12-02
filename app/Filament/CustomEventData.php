<?php
namespace App\Filament;

use Saade\FilamentFullCalendar\Data\EventData;

class CustomEventData extends EventData
{
    public function tooltip(): string
    {
    // Your tooltip content here
    return $this->title . "\n" . $this->start->format('Y-m-d H:i') . "\n" . $this->end->format('Y-m-d H:i');
    }
}
