<?php

namespace App\Livewire;

use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListRequest extends Component
{
    public $Requests;

    public function mount()
    {
        $this->Requests=Request::where('user_id',Auth::user()->id)->get();
    }
    public function render()
    {
        return view('livewire.list-request');
    }


}
