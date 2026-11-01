<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Services\ShippingDocumentService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return 'Commande CMD-'.str_pad((string) $this->record->id, 5, '0', STR_PAD_LEFT);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateStatus')
                ->label('Changer le statut')
                ->icon('heroicon-m-arrow-path')
                ->color('primary')
                ->form([
                    Select::make('status')
                        ->label('Nouveau statut')
                        ->options(OrderResource::STATUS_LABELS)
                        ->default(fn () => $this->record->status)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->record->update(['status' => $data['status']]);

                    Notification::make()
                        ->title('Statut de la commande n°'.$this->record->id.' mis à jour : '.OrderResource::statusLabel($data['status']))
                        ->success()
                        ->send();
                }),

            ActionGroup::make([
                Action::make('downloadLabel')
                    ->label('Étiquette d\'expédition (PDF)')
                    ->icon('heroicon-m-tag')
                    ->color('gray')
                    ->url(fn () => route('orders.shipping.label', $this->record))
                    ->openUrlInNewTab(),
                Action::make('downloadSheet')
                    ->label('Fiche d\'expédition (PDF)')
                    ->icon('heroicon-m-document-text')
                    ->color('gray')
                    ->url(fn () => route('orders.shipping.sheet', $this->record))
                    ->openUrlInNewTab(),
                Action::make('downloadCsv')
                    ->label('Données expédition complètes (CSV)')
                    ->icon('heroicon-m-table-cells')
                    ->color('success')
                    ->url(fn () => route('orders.shipping.csv', $this->record))
                    ->openUrlInNewTab(),
            ])
                ->label('Documents & Export')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('gray')
                ->button(),
        ];
    }
}
