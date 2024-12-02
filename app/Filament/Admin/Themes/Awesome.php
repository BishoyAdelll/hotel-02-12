<?php

namespace App\Filament\Admin\Themes;

use Filament\Panel;
use Hasnayeen\Themes\Contracts\CanModifyPanelConfig;
use Hasnayeen\Themes\Contracts\Theme;
use Hasnayeen\Themes\Contracts\HasChangeableColor;

class Awesome implements CanModifyPanelConfig, Theme , HasChangeableColor
{
    public static function getName(): string
    {
        return 'awesome';
    }
    public function getPrimaryColor(): array
    {
        return [
            'primary' => '#000',
        ];
    }
    public static function getPath(): string
    {
        return 'resources/css/filament/admin/themes/awesome.css';
    }


    public function getThemeColor(): array
    {
        return [
            'primary' => '#000',
            'secondary' => '#fff',
        ];
    }

    public function modifyPanelConfig(Panel $panel): Panel
    {
        return $panel
            ->viteTheme($this->getPath())
            ->topNavigation();
    }

}
