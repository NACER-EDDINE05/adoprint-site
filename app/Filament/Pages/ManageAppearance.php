<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

/**
 * Page d'administration « Apparence du site » : permet de téléverser la
 * photo de fond de la page d'accueil (réglage home.hero_image).
 * Sans photo, la boutique affiche un visuel de substitution aux couleurs
 * de la marque.
 */
class ManageAppearance extends Page
{
    protected static string $view = 'filament.pages.manage-appearance';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Paramètres';

    protected static ?string $title = 'Apparence du site';

    protected static ?string $navigationLabel = 'Apparence du site';

    /** Chemin du réglage qui porte la photo de fond de l'accueil. */
    public const HERO_KEY = 'home.hero_image';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hero' => Setting::query()->where('key', self::HERO_KEY)->value('value'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('hero')
                    ->label('Photo de fond de la page d’accueil')
                    ->image()
                    ->imageEditor()
                    ->directory('settings')
                    ->disk('public')
                    ->maxSize(6144)
                    ->helperText('Format paysage, ~1920 px de large idéal. Le texte reste lisible : la photo s’estompe vers le blanc sous les titres. Laissez vide pour revenir au visuel de substitution.')
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $paths = (array) ($this->form->getState()['hero'] ?? []);
        $value = $paths[0] ?? null;

        if ($value) {
            Setting::query()->updateOrCreate(
                ['key' => self::HERO_KEY],
                ['value' => $value]
            );
        } else {
            Setting::query()->where('key', self::HERO_KEY)->delete();
        }

        Notification::make()
            ->title($value ? 'Photo de fond enregistrée.' : 'Photo de fond retirée — visuel de substitution actif.')
            ->success()
            ->send();

        $this->form->fill(['hero' => $value]);
    }
}
