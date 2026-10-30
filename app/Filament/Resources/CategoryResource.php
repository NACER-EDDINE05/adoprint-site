<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $modelLabel = 'catégorie';
    protected static ?string $pluralModelLabel = 'catégories';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.categories.label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nom')->required(),
                TextInput::make('slug')->label('Slug (URL)')->required(),
                Textarea::make('description')->label('Description')->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('slug'),
                IconColumn::make('active')->boolean()->label('Active'),
            ])
            ->emptyStateHeading('Aucune catégorie pour le moment')
            ->emptyStateDescription('Cliquez sur « Nouvelle catégorie » pour organiser le catalogue.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
    }
}
