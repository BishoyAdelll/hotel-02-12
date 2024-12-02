<?php

namespace App\Livewire;

use App\Models\Capacity;
use App\Models\Hall;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Filter extends Component
{
    public $startDate;
    public $endDate;
    public $reservedRooms = [];
    public $availableHalls = [];
        public $capacity = null ;
//    public function updatedStartDate()
//    {
//        $this->save();
//
//    }


    // ... other methods


    public function updatedEndDate()
    {
        $this->save();
    }

    public function save()
    {
//        dd($this->capacity);
        if (  isset($this->capacity) && $this->capacity != ""){
            $this->availableHalls= Hall::whereDoesntHave('reservations', function ($query) {
                $query->where('check_in', '<=', Carbon::parse($this->endDate))
                    ->where('check_out', '>', Carbon::parse($this->startDate))
                ;
            }) ->where('capacity_id', $this->capacity)->get();
            $this->reservedRooms = Hall::with('reservations')
                ->whereHas('reservations', function ($query) {
                    $query->whereDate('check_in', '<=', Carbon::parse($this->endDate))
                        ->whereDate('check_out', '>', Carbon::parse($this->startDate));
                })->where('capacity_id', $this->capacity)->get();
        }else{
            $this->availableHalls= Hall::whereDoesntHave('reservations', function ($query) {
                $query->where('check_in', '<=', Carbon::parse($this->endDate))
                    ->where('check_out', '>', Carbon::parse($this->startDate))
                ;
            })->get();
            $this->reservedRooms = Hall::with('reservations')
                ->whereHas('reservations', function ($query) {
                    $query->whereDate('check_in', '<=', Carbon::parse($this->endDate))
                        ->whereDate('check_out', '>', Carbon::parse($this->startDate));
                })->get();
        }

    }

    public function render()
    {
        $capacities = Capacity::all();
        return view('livewire.filter', ['availableHalls' => $this->availableHalls,'capacities' => $capacities]);
    }

}
