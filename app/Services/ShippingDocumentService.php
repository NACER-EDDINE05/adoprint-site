<?php

namespace App\Services;

use App\Filament\Resources\OrderResource;
use App\Models\Media;
use App\Models\Order;
use App\Models\Setting;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShippingDocumentService
{
    public const LABEL_W = 100;
    public const LABEL_H = 150;

    public function __construct(
        protected \Picqer\Barcode\BarcodeGeneratorSVG $barcodes = new \Picqer\Barcode\BarcodeGeneratorSVG(),
    ) {
    }

    public function labelPdf(Order $order): string
    {
        return $this->makePdf($this->labelHtml($order), 'label');
    }

    public function sheetPdf(Order $order): string
    {
        return $this->makePdf($this->sheetHtml($order), 'A4');
    }

    public function downloadLabel(Order $order)
    {
        return response()->streamDownload(
            fn () => print $this->labelPdf($order),
            $order->reference.'-etiquette-expedition.pdf',
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function downloadSheet(Order $order)
    {
        return response()->streamDownload(
            fn () => print $this->sheetPdf($order),
            $order->reference.'-fiche-expedition.pdf',
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function downloadCsv(Order $order): StreamedResponse
    {
        $filename = $order->reference.'-donnees-expedition.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->stream(function () use ($order) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($output, [
                'N° Commande',
                'Référence',
                'Statut',
                'Date',
                'Nom/Raison Sociale',
                'Société',
                'Email',
                'Téléphone',
                'Adresse',
                'Code Postal',
                'Ville',
                'Pays',
                'Produits',
                'Quantités',
                'Fichiers',
                'Notes',
                'Total (MAD)',
            ], ';');
            
            $c = $order->meta['customer'] ?? [];
            $d = $order->meta['delivery'] ?? [];
            $items = collect((array) ($order->meta['items'] ?? []));
            $files = $this->filesOf($order);
            
            $nom = $this->cleanCsv($c['nom'] ?? ($order->user?->name ?? ''));
            $societe = $this->cleanCsv($c['societe'] ?? '');
            $email = $this->cleanCsv($c['email'] ?? ($order->user?->email ?? ''));
            $tel = $this->cleanCsv($c['telephone'] ?? '');
            $adresse = $this->cleanCsv($d['adresse'] ?? '');
            $cp = $this->cleanCsv($d['code_postal'] ?? '');
            $ville = $this->cleanCsv($d['ville'] ?? '');
            $notes = $this->cleanCsv(trim((string) ($order->meta['notes'] ?? '')));
            
            $produits = $items->map(fn ($it) => $this->cleanCsv($it['product'] ?? ''))->implode(' | ');
            $quantites = $items->map(fn ($it) => $it['qty'] ?? 1)->implode(' | ');
            $fichiers = $files->map(fn ($f) => $f->filename)->implode(' | ');
            
            fputcsv($output, [
                $order->id,
                $order->reference,
                OrderResource::statusLabel($order->status),
                $order->created_at?->format('d/m/Y H:i'),
                $nom,
                $societe,
                $email,
                $tel,
                $adresse,
                $cp,
                $ville,
                'MAROC',
                $produits,
                $quantites,
                $fichiers,
                $notes,
                number_format((float) $order->total, 2, ',', ''),
            ], ';');
            
            fclose($output);
        }, 200, $headers);
    }

    public function downloadBulkCsv(Collection $orders): StreamedResponse
    {
        $filename = 'export-expedition-'.now()->format('Y-m-d-His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->stream(function () use ($orders) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($output, [
                'N° Commande',
                'Référence',
                'Statut',
                'Date',
                'Nom/Raison Sociale',
                'Société',
                'Email',
                'Téléphone',
                'Adresse',
                'Code Postal',
                'Ville',
                'Pays',
                'Produits',
                'Quantités',
                'Nb Fichiers',
                'Notes',
                'Total (MAD)',
            ], ';');
            
            foreach ($orders as $order) {
                $c = $order->meta['customer'] ?? [];
                $d = $order->meta['delivery'] ?? [];
                $items = collect((array) ($order->meta['items'] ?? []));
                $files = $this->filesOf($order);
                
                $nom = $this->cleanCsv($c['nom'] ?? ($order->user?->name ?? ''));
                $societe = $this->cleanCsv($c['societe'] ?? '');
                $email = $this->cleanCsv($c['email'] ?? ($order->user?->email ?? ''));
                $tel = $this->cleanCsv($c['telephone'] ?? '');
                $adresse = $this->cleanCsv($d['adresse'] ?? '');
                $cp = $this->cleanCsv($d['code_postal'] ?? '');
                $ville = $this->cleanCsv($d['ville'] ?? '');
                $notes = $this->cleanCsv(trim((string) ($order->meta['notes'] ?? '')));
                
                $produits = $items->map(fn ($it) => $this->cleanCsv($it['product'] ?? ''))->implode(' | ');
                $quantites = $items->map(fn ($it) => $it['qty'] ?? 1)->implode(' | ');
                
                fputcsv($output, [
                    $order->id,
                    $order->reference,
                    OrderResource::statusLabel($order->status),
                    $order->created_at?->format('d/m/Y H:i'),
                    $nom,
                    $societe,
                    $email,
                    $tel,
                    $adresse,
                    $cp,
                    $ville,
                    'MAROC',
                    $produits,
                    $quantites,
                    $files->count(),
                    $notes,
                    number_format((float) $order->total, 2, ',', ''),
                ], ';');
            }
            
            fclose($output);
        }, 200, $headers);
    }

    protected function cleanCsv($value): string
    {
        $value = (string) $value;
        $value = str_replace(["\r\n", "\r", "\n"], ' ', $value);
        $value = str_replace('"', '""', $value);
        return $value;
    }

    protected static function mm(float $mm): float
    {
        return $mm * 72 / 25.4;
    }

    protected function makePdf(string $html, string $paper): string
    {
        $dompdf = new Dompdf(new Options([
            'isRemoteEnabled' => false,
            'defaultFont'     => 'Helvetica',
        ]));
        if ($paper === 'label') {
            $dompdf->setPaper([0, 0, self::mm(self::LABEL_W), self::mm(self::LABEL_H)], 'portrait');
        } else {
            $dompdf->setPaper('A4', 'portrait');
        }
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();
        return $dompdf->output();
    }

    protected function labelHtml(Order $order): string
    {
        $c = $order->meta['customer'] ?? [];
        $d = $order->meta['delivery'] ?? [];
        $shop = $this->shopData();
        $items = collect((array) ($order->meta['items'] ?? []));

        $name = $this->t((string) ($c['nom'] ?? ($order->user?->name ?? 'Client')));
        $phone = $this->t((string) ($c['telephone'] ?? ($order->user?->phone ?? '—')));
        $address = $this->t((string) ($d['adresse'] ?? '—'));
        $city = $this->t((string) ($d['ville'] ?? '—'));
        $zip = $this->t((string) ($d['code_postal'] ?? ''));

        $ref = $order->reference;
        $scan = str_replace('-', '', $ref);

        $barcode = $this->barcodes->getBarcode($scan, $this->barcodes::TYPE_CODE_128);
        $barcode = preg_replace('/^<svg/', '<svg preserveAspectRatio="none"', (string) $barcode);
        $barcode = preg_replace('/\s(width="\d+px")/', '', (string) $barcode);
        $barcode = preg_replace('/\s(height="\d+px")/', ' height="32"', (string) $barcode);

        $contentLines = $items->isEmpty()
            ? ['Contenu : voir fiche']
            : $items->take(4)->map(fn (array $it) => ($this->t((string) ($it['product'] ?? 'Article'))).' x'.($this->t((string) ($it['qty'] ?? '1'))))->values()->all();
        $contentHtml = implode('<br>', $contentLines);

        $deposited = $order->created_at?->format('d/m/Y') ?? '-';
        $shopName = $this->t((string) $shop['name']);
        $shopPhone = $this->t((string) $shop['phone']);
        $shopCity = $this->t((string) $shop['city']);

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
@page { size: 100mm 150mm; margin: 0; }
* { margin: 0; padding: 0; box-sizing: border-box; }
html, body { width: 100mm; height: 150mm; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif; }
.label { width: 100mm; height: 150mm; table-layout: fixed; border-collapse: collapse; }
.label td { border: 1px solid #000; padding: 1.5mm 2mm; vertical-align: top; }
.exp { font-size: 7pt; text-transform: uppercase; letter-spacing: 1px; }
.exp-name { font-size: 10pt; font-weight: bold; margin-top: 0.5mm; }
.exp-info { font-size: 6pt; margin-top: 0.5mm; }
.ref-label { font-size: 6pt; text-transform: uppercase; letter-spacing: 1px; }
.ref { font-size: 14pt; font-weight: bold; }
.bar { text-align: center; padding: 1mm 2mm; height: 12mm; }
.bar svg { width: 94mm; height: 10mm; }
.bar-txt { font-family: Courier, monospace; font-size: 7pt; letter-spacing: 2.5px; text-align: center; }
.dest-label { font-size: 6pt; text-transform: uppercase; letter-spacing: 1px; }
.dest-name { font-size: 13pt; font-weight: bold; text-transform: uppercase; margin-top: 1mm; }
.dest-tel { font-size: 11pt; font-weight: bold; margin-top: 1mm; }
.dest-addr { font-size: 8pt; margin-top: 1mm; line-height: 1.2; }
.dest-city { font-size: 10pt; font-weight: bold; margin-top: 1mm; }
.dest-ctry { font-size: 7pt; margin-top: 0.8mm; text-transform: uppercase; }
.cont-label { font-size: 6pt; text-transform: uppercase; letter-spacing: 1px; }
.cont { font-size: 7.5pt; font-weight: bold; margin-top: 0.8mm; line-height: 1.25; }
.cont-meta { font-size: 5.5pt; margin-top: 1mm; color: #333; }
</style>
</head>
<body>
<table class="label">
<tr><td style="height: 16mm;">
<div class="exp">EXPÉDITEUR</div>
<div class="exp-name">{$shopName}</div>
<div class="exp-info">{$shopCity}, Maroc • Tél: {$shopPhone}</div>
</td></tr>
<tr><td style="height: 14mm;">
<div class="ref-label">N° COMMANDE</div>
<div class="ref">{$ref}</div>
</td></tr>
<tr><td style="height: 16mm; padding: 0;">
<div class="bar">{$barcode}</div>
<div class="bar-txt">{$scan}</div>
</td></tr>
<tr><td style="height: 56mm;">
<div class="dest-label">DESTINATAIRE</div>
<div class="dest-name">{$name}</div>
<div class="dest-tel">TÉL: {$phone}</div>
<div class="dest-addr">{$address}</div>
<div class="dest-city">{$zip} {$city}</div>
<div class="dest-ctry">MAROC</div>
</td></tr>
<tr><td style="height: 48mm;">
<div class="cont-label">CONTENU</div>
<div class="cont">{$contentHtml}</div>
<div class="cont-meta">Le: {$deposited} • Règlement à la livraison</div>
</td></tr>
</table>
</body>
</html>
HTML;
    }

    protected function sheetHtml(Order $order): string
    {
        $c = $order->meta['customer'] ?? [];
        $d = $order->meta['delivery'] ?? [];
        $items = collect((array) ($order->meta['items'] ?? []));
        $files = $this->filesOf($order);
        $shop = $this->shopData();

        $name = $this->t((string) ($c['nom'] ?? ($order->user?->name ?? '—')));
        $email = $this->t((string) ($c['email'] ?? ($order->user?->email ?? '—')));
        $phone = $this->t((string) ($c['telephone'] ?? '—'));
        $company = $this->t((string) ($c['societe'] ?? '—'));

        $address = $this->t((string) ($d['adresse'] ?? '—'));
        $city = $this->t((string) ($d['ville'] ?? '—'));
        $zip = $this->t((string) ($d['code_postal'] ?? '—'));

        $ref = $order->reference;
        $date = $order->created_at?->format('d/m/Y à H:i') ?? '—';
        $status = OrderResource::statusLabel($order->status);
        $total = number_format((float) $order->total, 2, ',', ' ').' MAD';

        $shopName = $this->t((string) $shop['name']);
        $shopPhone = $this->t((string) $shop['phone']);
        $shopAddress = $this->t((string) $shop['address']);
        $shopCity = $this->t((string) $shop['city']);

        $itemsRows = $items->isEmpty()
            ? '<tr><td colspan="5" style="text-align:center;padding:6px">Aucun article</td></tr>'
            : $items->map(function (array $it) {
                $product = $this->t((string) ($it['product'] ?? '—'));
                $qty = $this->t((string) ($it['qty'] ?? '—'));
                $options = is_array($it['options'] ?? null) && filled($it['options'])
                    ? $this->t(implode(', ', array_map('strval', array_filter($it['options']))))
                    : '-';
                $unit = number_format((float) ($it['unit'] ?? 0), 2, ',', ' ').' MAD';
                $totalLine = number_format((float) ($it['total'] ?? 0), 2, ',', ' ').' MAD';
                return '<tr><td>'.$product.'</td><td style="text-align:center">'.$qty.'</td><td>'.$options.'</td><td style="text-align:right">'.$unit.'</td><td style="text-align:right">'.$totalLine.'</td></tr>';
            })->implode('');

        $filesRows = $files->isEmpty()
            ? '<tr><td colspan="3" style="text-align:center;padding:6px">Aucun fichier</td></tr>'
            : $files->map(function (Media $f, int $i) {
                $ext = filled($f->filename) ? strtoupper(pathinfo($f->filename, PATHINFO_EXTENSION) ?: 'FILE') : 'FILE';
                $size = number_format(((int) $f->size) / 1024, 0, ',', ' ').' Ko';
                return '<tr><td style="text-align:center">'.($i+1).'</td><td>'.$this->t((string) $f->filename).'</td><td>'.$ext.' / '.$size.'</td></tr>';
            })->implode('');

        $notes = $this->t(trim((string) ($order->meta['notes'] ?? '')));
        $notesHtml = filled($notes) ? nl2br($notes) : '-';

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="utf-8">
<style>
@page { margin: 10mm 10mm; }
body { font-family: Helvetica, Arial, sans-serif; font-size: 9pt; color: #000; }
h1 { font-size: 14pt; margin: 0 0 2mm; }
.header { border-bottom: 2px solid #000; padding-bottom: 3mm; margin-bottom: 4mm; }
.info { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
.info td { border: 1px solid #000; padding: 2mm 3mm; }
.info .lbl { background: #f0f0f0; font-weight: bold; width: 25%; }
table.items { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
table.items th { background: #000; color: #fff; padding: 2mm 3mm; text-align: left; }
table.items td { border: 1px solid #000; padding: 2mm 3mm; }
table.files { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
table.files th { background: #f0f0f0; padding: 2mm 3mm; border: 1px solid #000; text-align: left; }
table.files td { border: 1px solid #000; padding: 2mm 3mm; }
.box { border: 1px solid #000; padding: 3mm; margin-bottom: 4mm; }
.box-title { font-weight: bold; margin-bottom: 2mm; text-transform: uppercase; }
.sig { width: 100%; margin-top: 10mm; }
.sig td { width: 50%; padding: 0 3mm; }
.sig-box { border: 1px solid #000; height: 20mm; padding: 2mm; }
.footer { text-align: center; font-size: 7pt; margin-top: 6mm; color: #666; }
</style>
</head>
<body>
<div class="header">
<table style="width:100%"><tr>
<td><h1>{$shopName}</h1><div>{$shopAddress} - {$shopCity}, Maroc - Tél: {$shopPhone}</div></td>
<td style="text-align:right"><h1>FICHE D'EXPÉDITION</h1><div><strong>{$ref}</strong> | {$date} | {$status}</div></td>
</tr></table>
</div>

<table class="info">
<tr><td class="lbl">Client</td><td>{$name}</td><td class="lbl">Société</td><td>{$company}</td></tr>
<tr><td class="lbl">Téléphone</td><td>{$phone}</td><td class="lbl">Email</td><td>{$email}</td></tr>
<tr><td class="lbl">Adresse</td><td colspan="3">{$address}, {$zip} {$city}, MAROC</td></tr>
</table>

<h3 style="margin:0 0 2mm;">ARTICLES</h3>
<table class="items">
<tr><th>Produit</th><th style="width:12%">Qté</th><th style="width:25%">Options</th><th style="width:18%;text-align:right">PU</th><th style="width:18%;text-align:right">Total</th></tr>
{$itemsRows}
<tr><td colspan="4" style="text-align:right;font-weight:bold">TOTAL</td><td style="text-align:right;font-weight:bold">{$total}</td></tr>
</table>

<h3 style="margin:0 0 2mm;">FICHIERS JOINTS</h3>
<table class="files">
<tr><th style="width:8%">N°</th><th>Fichier</th><th style="width:20%">Type/Taille</th></tr>
{$filesRows}
</table>

<div class="box">
<div class="box-title">Instructions</div>
<div>{$notesHtml}</div>
</div>

<table class="sig">
<tr><td><div class="sig-box">Vérifié par / Date: ___________<br><br>Signature: ___________________</div></td><td><div class="sig-box">Préparé / Emballé<br><br>Colis n°: ___________</div></td></tr>
</table>

<div class="footer">{$ref} - Document de préparation - ADO PRINT</div>
</body>
</html>
HTML;
    }

    protected function filesOf(Order $order): \Illuminate\Support\Collection
    {
        try {
            return Media::query()
                ->where('model_type', Order::class)
                ->where('model_id', $order->id)
                ->orderBy('created_at')
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    protected function shopData(): array
    {
        $s = fn (string $k, string $d) => (string) (Setting::query()->where('key', $k)->value('value') ?: $d);

        return [
            'name'    => $s('shop_name', 'ADO PRINT'),
            'phone'   => $s('shop_phone', '0703-201412'),
            'address' => $s('shop_address', 'Casablanca, Maroc'),
            'city'    => $s('shop_city', 'Casablanca'),
        ];
    }

    protected function t(mixed $value): string
    {
        $text = trim((string) ($value ?? ''));
        if ($text === '') {
            return '—';
        }
        return Str::ascii($text, 'fr') ?: '?';
    }
}
