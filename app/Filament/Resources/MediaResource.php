<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $modelLabel = 'fichier média';
    protected static ?string $pluralModelLabel = 'fichiers média';

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('aperçu')
                ->label('Aperçu')
                ->getStateUsing(fn (Media $record) => blank($record->path) ? null : '/storage/'.$record->path),
            TextColumn::make('filename')->label('Fichier'),
            TextColumn::make('path')->label('Chemin'),
        ])
            ->actions([
                Action::make('voir')
                    ->label('Voir')
                    ->icon('heroicon-m-eye')
                    ->color('gray')
                    ->visible(fn (Media $record) => ! blank($record->path))
                    ->url(fn (Media $record) => route('admin.media.show', $record), shouldOpenInNewTab: true),
                Action::make('télécharger')
                    ->label('Télécharger')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (Media $record) => ! blank($record->path))
                    ->url(fn (Media $record) => route('admin.media.download', $record)),
            ])
            ->emptyStateHeading('Aucun média pour le moment')
            ->emptyStateDescription('Les images du site se gèrent dans « Apparence du site » et dans la photo de chaque produit.')
            ->emptyStateIcon('heroicon-o-photo');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.media.label');
    }
}
