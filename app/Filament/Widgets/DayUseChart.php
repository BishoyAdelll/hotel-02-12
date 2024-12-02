<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Appointment;
use App\Models\Hall;
use App\Models\Record;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DayUseChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Day Use';

    protected static ?int $sort=3;

    protected function getData(): array
    {
//        $start = $this->filters['startDate'];
//        $end = $this->filters['endDate'];

        for ($i = 1; $i < 13; $i++) {
            $test= Application::query()->whereMonth('created_at',$i)->count();
            $bishoy[]=$test;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Appointments For every Month',
                    'data' => $bishoy,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];

//        $data = Trend::model(Appointment::class)
//            ->between(
//                start: $start ?Carbon::parse($start) : now()->subMonth(6),
//                end: $end ?Carbon::parse($end) : now(),
////                end: now(),
//            )
//            ->perMonth()
//            ->count();
//
//        return [
//            'datasets' => [
//                [
//                    'label' => 'Blog posts',
//                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
//                ],
//            ],
//            'labels' => $data->map(fn (TrendValue $value) => $value->date),
//        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
