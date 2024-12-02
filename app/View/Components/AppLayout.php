<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }







//
//$formattedTimes = Time::query()
//->whereIn('id', $bishoy)
//->pluck('time','time')
//->map(function ($time) {
//    return Carbon::parse($time)->format('h:i:s A'); // Adjust format as needed
//})
//->toArray();
//
//return $formattedTimes;
}
