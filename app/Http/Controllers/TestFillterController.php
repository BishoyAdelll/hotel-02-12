<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class TestFillterController extends Controller
{
    public function index()
    {
        $filterData = [
//            'start_date' => request()->input('start_date'),
//            'end_date' => request()->input('end_date'),
            'start_date' => '2024-10-05',
            'end_date' => '2024-10-06',
        ];
        $nonReservedHalls = Hall::whereDoesntHave('reservations', function ($query) use ($filterData) {
            $query->where('check_in', '<=', $filterData['end_date'])
                ->where('check_out', '>=', $filterData['start_date']);
        })->get();
        dd($nonReservedHalls);

    }
}
