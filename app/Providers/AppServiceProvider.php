<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\User;
use App\Observers\AppointmentObserver;
use App\Observers\UserObserver;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Health::checks([
//            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
        ]);
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en','fr'])// also accepts a closure
                 ->visible(outsidePanels: true)
                ->outsidePanelRoutes([
                    'profile',
                    'home',
                    // Additional custom routes where the switcher should be visible outside panels
                ])
            ;
        });
        User::observe(UserObserver::class);
        Appointment::observe(AppointmentObserver::class);

    }
}
