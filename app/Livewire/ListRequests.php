<?php

namespace App\Livewire;

use App\Models\Request;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Filament\Forms\Form;

class ListRequests extends Component implements  HasForms
{
    use  InteractsWithForms;
    public ?array $data = [];
    public function render()
    {
        return view('livewire.list-requests');
    }

    public function create()
    {

        $data = $this->form->getState();

        $message = Request::create([
            'church' =>$data['church'],
            'email' =>$data['email'],
            'whatsapp' =>$data['whatsapp'],
            'phone' =>$data['phone'],
            'meeting_name' =>$data['meeting_name'],
            'administrator_name' =>$data['administrator_name'],
            'priest' =>$data['priest'],
            'description' =>$data['description'],
            'user_id' =>Auth::user()->id,
        ]);
        return $this->redirect('/dashboard');

    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Section::make()
                            ->description('Please enter the data correctly so that we can contact you')
                            ->schema([
                                TextInput::make('email')
//                                    ->label('البريد الالكترونى')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
//                                    ->label(' التليفون')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('whatsapp')
//                                    ->label(' الواتس اب ')
                                    ->required()
                                    ->maxLength(255),
                            ])->columns(3),

                     Section::make()
                        ->schema([
                            TextInput::make('church')
//                            ->label('اسم الكنيسة')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('meeting_name')
//                            ->label('اسم الاجتماع')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('administrator_name')
//                            ->label('اسم المسؤول')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('priest')
//                            ->label('اسم القس /القمص ')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('description')
//                            ->label(' تفاصيل')
                                ->maxLength(65535),

                        ])->columns(2)
                    ]),

            ])
              ->statePath('data')
            ->model(Request::class);

    }

}
