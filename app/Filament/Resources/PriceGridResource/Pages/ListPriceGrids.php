<?php

namespace App\Filament\Resources\PriceGridResource\Pages;

use App\Filament\Resources\PriceGridResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPriceGrids extends ListRecords
{
    protected static string $resource = PriceGridResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Nouvelle grille tarifaire')];
    }
}
