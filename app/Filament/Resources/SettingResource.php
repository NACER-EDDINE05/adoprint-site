<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $modelLabel = 'réglage';
    protected static ?string $pluralModelLabel = 'réglages';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.settings.label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('key')->label('Clé')->required(),
            TextInput::make('value')->label('Valeur'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')->label('Clé'),
            TextColumn::make('value')->label('Valeur'),
        ])
            ->emptyStateHeading('Aucun réglage pour le moment')
            ->emptyStateDescription('Les réglages techniques du site (clé → valeur) apparaîtront ici.')
            ->emptyStateIcon('heroicon-o-cog');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
    }
}
