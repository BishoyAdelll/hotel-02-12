<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FilterRoom extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.filter-room';
    protected static ?string $navigationLabel = 'Available Rooms';

}
