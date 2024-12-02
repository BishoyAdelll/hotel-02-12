<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\Hall;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->where('is_admin','=',1)->count())
                ->description('User counter ')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success')
                ,
            Stat::make('Reservations', Appointment::query()->count())
                ->description('Total Reservation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
            Stat::make('Rooms', Hall::query()->count())
                ->description('Hall counter ')
                ->descriptionIcon('heroicon-m-arrows-pointing-out')
                ->color('primary'),
        ];
    }
}
