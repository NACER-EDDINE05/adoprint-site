<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

/**
 * Page d'administration « Apparence du site » : permet de téléverser la
 * photo de fond de la page d'accueil (réglage home.hero_image), le logo
 * d'en-tête (brand.logo) et l'icône du navigateur (brand.icon).
 * Sans image, la boutique affiche les visuels par défaut de la marque.
 */
class ManageAppearance extends Page
{
    protected static string $view = 'filament.pages.manage-appearance';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Paramètres';

    protected static ?string $title = 'Apparence du site';

    protected static ?string $navigationLabel = 'Apparence du site';

    /** Champs réglables : nom du champ => clé du réglage en base. */
    public const KEYS = [
        'hero' => 'home.hero_image',
        'logo' => 'brand.logo',
        'icon' => 'brand.icon',
    ];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            collect(self::KEYS)
                ->mapWithKeys(fn (string $key, string $field) => [$field => Setting::query()->where('key', $key)->value('value')])
                ->all()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Logo & icône du navigateur')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo d’en-tête (PNG transparent)')
                            ->image()
                            ->imageEditor()
                            ->directory('settings')
                            ->disk('public')
                            ->maxSize(4096)
                            ->helperText('Visible dans la barre en haut de toutes les pages. Laissez vide (et enregistrez) pour revenir au logo par défaut.')
                            ->columnSpanFull(),
                        FileUpload::make('icon')
                            ->label('Icône du navigateur (favicon)')
                            ->image()
                            ->imageEditor()
                            ->directory('settings')
                            ->disk('public')
                            ->maxSize(2048)
                            ->helperText('Image carrée ~96×96 px minimum. S’applique aux onglets du site public.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Photo de fond de la page d’accueil')
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
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $labels = [
            'logo' => 'Logo',
            'icon' => 'Icône du navigateur',
            'hero' => 'Photo de fond',
        ];

        foreach (self::KEYS as $field => $key) {
            $value = (array) ($state[$field] ?? []);
            $value = $value[0] ?? null;

            if ($value) {
                Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
            } else {
                Setting::query()->where('key', $key)->delete();
            }

            $this->form->fill([$field => $value]);
        }

        Notification::make()
            ->title('Apparence enregistrée.')
            ->success()
            ->send();
    }
}
