<?php

namespace App\Livewire;

use App\Models\Attachment;
use App\Models\Hall;
use Illuminate\Support\Carbon;
use Livewire\Component;

class FilterAttachments extends Component
{
    public $startDate;
    public $endDate;
    public $reservedAttachments = [];



    public function updatedEndDate()
    {
        $this->save();
    }

    public function save()
    {
        $this->reservedAttachments = \App\Models\Collection::whereBetween('start_time', [$this->startDate, $this->endDate])
            ->with('attachment')
            ->get();
    }

    public function render()
    {
        return view('livewire.filter-attachments');
    }
}
