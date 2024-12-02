<?php

namespace App\Http\Controllers;

use App\Models\Addition;
use App\Models\Application;
use App\Models\ApplicationAddition;
use App\Models\ApplicationComponent;
use App\Models\Appointment;
use App\Models\AppointmentAddition;
use App\Models\AppointmentHall;
use App\Models\AppointmentMeal;
use App\Models\Component;
use App\Models\Hall;
use App\Models\Meal;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDF;

class DownloadBdfController extends Controller
{
    public function viewInvoice(Appointment $record)
    {
        $testArray=null;
        $testArrayMeals=null;
        $testArrayRooms= null;
        $data=['record' => $record];
        $bishoy = Appointment::findOrFail($record->id)->with('tools')->first();
//        dd($bishoy);
        $tests=AppointmentAddition::where('appointment_id',$record->id)->get();
//        dd($tests);
        foreach ($tests as $test){
            $testArray[]=Addition::where('id',$test->addition_id)->first();

        }
//        $meals=AppointmentMeal::where('appointment_id',$record->id)->get();
////        dd($tests);
//        foreach ($meals as $meal){
//            $testArrayMeals[]=Meal::where('id',$meal->meal_id)->first();
//
//        }

        $rooms= AppointmentHall::where('appointment_id',$record->id)->get();
        foreach ($rooms as $room ) {
            $testArrayRooms[]=Hall::where('id',$room->hall_id)->first();
        }
        // $time=Time::find($record->track)->first();


    //    dd($testArrayMeals);
        return view('admin.invoice.generate-invoice',compact('bishoy','record','testArray','testArrayMeals','testArrayRooms'));
    }
    public  function generateInvoice(Appointment $record)
    {
        $testArray=null;
        $testArrayMeals=null;
        $tests=AppointmentAddition::where('appointment_id',$record->id)->get();
//        dd($tests);
        foreach ($tests as $test){
            $testArray[]=Addition::where('id',$test->addition_id)->first();
        }
        $meals=AppointmentMeal::where('appointment_id',$record->id)->get();
        //        dd($tests);
                foreach ($meals as $test){
                    $testArrayMeals[]=Meal::where('id',$test->meal_id)->first();

                }
        // $time=Time::find($record->track)->first();
        $data=['record' => $record];
        $pdf=PDF::loadView('admin.invoice.generate-invoice',compact('record','testArray','testArrayMeals'));
        $todayDate=Carbon::now()->format('d-m-y');
        return $pdf->download('invoice-'.$record->id.'-'.$todayDate.'.pdf');

    }
    public function ViewApplication(Application $record)
    {
        $testArray=null;
        $testArrayMeals=null;
        $data=['record' => $record];
        $tests=ApplicationAddition::where('application_id',$record->id)->get();

        foreach ($tests as $test){
            $testArray[]=Addition::where('id',$test->addition_id)->first();
        }
        $meals=ApplicationComponent::where('application_id',$record->id)->get();
        foreach ($meals as $component){
            $testArrayMeals[]=Component::where('id',$component->component_id)->first();
        }

        return view('admin.invoice.generate-invoice-application',compact('record','testArray','testArrayMeals','meals','tests'));
    }
}
