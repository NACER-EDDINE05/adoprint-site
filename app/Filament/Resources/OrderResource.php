<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Media;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry\TextEntrySize;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $modelLabel = 'commande';
    protected static ?string $pluralModelLabel = 'commandes';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.orders.label');
    }

    public const STATUS_LABELS = [
        'pending'   => 'En attente',
        'quote'     => 'Devis',
        'shipped'   => 'Expédiée',
        'delivered' => 'Livrée',
        'cancelled' => 'Annulée',
    ];

    public static function statusLabel(?string $status): string
    {
        return self::STATUS_LABELS[$status] ?? (string) $status;
    }

    public static function statusColor(?string $status): string
    {
        return match ($status) {
            'pending'   => 'bg-amber-100 text-amber-800',
            'quote'     => 'bg-sky-100 text-sky-800',
            'shipped'   => 'bg-primary-100 text-primary-800',
            'delivered' => 'bg-emerald-100 text-emerald-800',
            'cancelled' => 'bg-red-100 text-red-700',
            default     => 'bg-gray-100 text-gray-700',
        };
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')
                    ->label('N°')
                    ->disabled(),
                Select::make('status')
                    ->label('Statut')
                    ->options(self::STATUS_LABELS)
                    ->disabled(),
                TextInput::make('total')
                    ->label('Total (MAD)')
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('N°')->sortable(),
            TextColumn::make('client')
                ->label('Client')
                ->state(fn (Order $record) => $record->meta['customer']['nom'] ?? $record->user?->name ?? '—')
                ->description(fn (Order $record) => $record->meta['customer']['email'] ?? $record->user?->email ?? '')
                ->searchable(query: fn ($query, string $search) => $query->orWhere('meta->customer->email', 'like', "%{$search}%")),
            TextColumn::make('status')
                ->label('Statut')
                ->badge()
                ->formatStateUsing(fn ($state) => self::statusLabel($state))
                ->color(fn ($state) => match ($state) {
                    'pending'   => 'warning',
                    'quote'     => 'info',
                    'shipped'   => 'primary',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                    default     => 'secondary',
                }),
            TextColumn::make('total')
                ->label('Total')
                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', ' ') . ' MAD')
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Reçue le')
                ->dateTime('d/m/Y H:i', 'Africa/Casablanca')
                ->sortable(),
        ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options(self::STATUS_LABELS),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('shippingLabel')
                        ->label('Étiquette d\'expédition (PDF)')
                        ->icon('heroicon-m-tag')
                        ->color('gray')
                        ->url(fn (Order $record) => route('orders.shipping.label', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('shippingSheet')
                        ->label('Fiche d\'expédition (PDF)')
                        ->icon('heroicon-m-document-text')
                        ->color('gray')
                        ->url(fn (Order $record) => route('orders.shipping.sheet', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('shippingCsv')
                        ->label('Données expédition (CSV/Excel)')
                        ->icon('heroicon-m-table-cells')
                        ->color('success')
                        ->url(fn (Order $record) => route('orders.shipping.csv', $record))
                        ->openUrlInNewTab(),
                ])
                    ->label('Documents / Export')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('gray'),
                Action::make('updateStatus')
                    ->label('Mettre à jour le statut')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->form([
                        Select::make('status')
                            ->label('Nouveau statut')
                            ->options(self::STATUS_LABELS)
                            ->default(fn (Order $record) => $record->status)
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update(['status' => $data['status']]);

                        Notification::make()
                            ->title('Statut de la commande n°' . $record->id . ' mis à jour : ' . self::statusLabel($data['status']))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->label('Détail'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('exportShippingCsv')
                    ->label('Exporter données expédition (CSV)')
                    ->icon('heroicon-m-table-cells')
                    ->color('success')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        $ids = $records->pluck('id')->implode(',');
                        return redirect()->route('admin.expedition.export.csv', ['ids' => $ids]);
                    }),
            ])
            ->emptyStateHeading('Aucune commande pour le moment')
            ->emptyStateDescription('Les commandes passées depuis la boutique apparaîtront ici.')
            ->emptyStateIcon('heroicon-o-receipt-percent');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Récapitulatif')
                ->icon('heroicon-o-receipt-percent')
                ->schema([
                    TextEntry::make('num')
                        ->label('N° de commande')
                        ->state(fn (Order $record) => 'CMD-'.str_pad((string) $record->id, 5, '0', STR_PAD_LEFT))
                        ->badge()
                        ->color('primary')
                        ->size(TextEntrySize::Large),
                    TextEntry::make('status')
                        ->label('Statut')
                        ->badge()
                        ->formatStateUsing(fn ($state) => self::statusLabel($state))
                        ->color(fn ($state) => match ($state) {
                            'pending'   => 'warning',
                            'quote'     => 'info',
                            'shipped'   => 'primary',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                            default     => 'secondary',
                        }),
                    TextEntry::make('total')
                        ->label('Total')
                        ->state(fn (Order $record) => number_format((float) $record->total, 2, ',', ' ').' MAD')
                        ->weight(FontWeight::Bold),
                    TextEntry::make('created_at')
                        ->label('Reçue le')
                        ->dateTime('d/m/Y à H:i', 'Africa/Casablanca'),
                ])
                ->columns(4),

            Section::make('Client')
                ->icon('heroicon-o-user')
                ->schema([
                    TextEntry::make('nom')
                        ->label('Nom')
                        ->state(fn (Order $record) => $record->meta['customer']['nom'] ?? $record->user?->name ?? '—'),
                    TextEntry::make('email')
                        ->label('Email')
                        ->state(fn (Order $record) => $record->meta['customer']['email'] ?? $record->user?->email ?? '—')
                        ->copyable(),
                    TextEntry::make('telephone')
                        ->label('Téléphone')
                        ->state(fn (Order $record) => $record->meta['customer']['telephone'] ?? '—')
                        ->copyable(),
                    TextEntry::make('societe')
                        ->label('Société')
                        ->state(fn (Order $record) => $record->meta['customer']['societe'] ?? '—'),
                ])
                ->columns(4),

            Section::make('Livraison')
                ->icon('heroicon-o-truck')
                ->schema([
                    TextEntry::make('adresse')
                        ->label('Adresse')
                        ->state(fn (Order $record) => $record->meta['delivery']['adresse'] ?? '—'),
                    TextEntry::make('ville')
                        ->label('Ville')
                        ->state(fn (Order $record) => $record->meta['delivery']['ville'] ?? '—'),
                    TextEntry::make('code_postal')
                        ->label('Code postal')
                        ->state(fn (Order $record) => $record->meta['delivery']['code_postal'] ?? '—'),
                ])
                ->columns(3),

            Section::make('Articles commandés')
                ->icon('heroicon-o-cube')
                ->schema([
                    RepeatableEntry::make('meta.items')
                        ->label(false)
                        ->schema([
                            TextEntry::make('product')->label('Produit')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('qty')->label('Quantité'),
                            TextEntry::make('options')
                                ->label('Options')
                                ->formatStateUsing(fn ($state) => is_array($state)
                                    ? (implode(' · ', array_filter($state)) ?: '—')
                                    : (filled($state) ? (string) $state : '—'))
                                ->default('—')
                                ->columnSpanFull(),
                            TextEntry::make('unit')
                                ->label('Prix unitaire')
                                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', ' ').' MAD'),
                            TextEntry::make('total')
                                ->label('Total ligne')
                                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', ' ').' MAD'),
                        ])
                        ->columns(4)
                        ->columnSpanFull(),
                ])
                ->visible(fn (Order $record) => filled($record->meta['items'] ?? null)),

            Section::make('Instructions particulières')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->schema([
                    TextEntry::make('notes')
                        ->label(false)
                        ->state(fn (Order $record) => $record->meta['notes'] ?: 'Aucune instruction particulière.')
                        ->prose(),
                ]),

            Section::make('Fichiers joints')
                ->icon('heroicon-o-paper-clip')
                ->description('Fichiers fournis par le client lors de la commande.')
                ->schema([
                    TextEntry::make('fichiers')
                        ->label(false)
                        ->state(function (Order $record) {
                            try {
                                $files = Media::query()
                                    ->where('model_type', Order::class)
                                    ->where('model_id', $record->id)
                                    ->get();
                            } catch (\Throwable) {
                                return ['Fichiers indisponibles pour le moment.'];
                            }

                            if ($files->isEmpty()) {
                                return ['Aucun fichier joint à cette commande.'];
                            }

                            // Un lien par fichier : prévisualisation (s'ouvre dans
                            // un onglet) + téléchargement avec le nom d'origine.
                            return $files->map(fn (Media $f) => blank($f->path)
                                ? new \Illuminate\Support\HtmlString(
                                    '<span class="⚠">📎 '.e($f->filename)
                                    .' — stockage indisponible (fichier à récupérer par email)</span>'
                                )
                                : new \Illuminate\Support\HtmlString(
                                    '<a href="'.e(route('admin.media.show', $f))
                                    .'" target="_blank" rel="noopener" class="font-bold text-primary-600 hover:underline">'
                                    .'📎 '.e($f->filename).'</a>'
                                    .' <span class="text-sm text-gray-500">'
                                    .number_format(((int) $f->size) / 1024, 0, ',', ' ').' Ko</span>'
                                    .' <a href="'.e(route('admin.media.download', $f))
                                    .'" class="font-bold text-primary-600 hover:underline">⬇ Télécharger</a>'
                                ))
                                ->all();
                        })
                        ->listWithLineBreaks(),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
    }
}
