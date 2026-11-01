<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingDocumentsTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        \Spatie\Permission\Models\Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
        );
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        return $user;
    }

    protected function order(): Order
    {
        $order = Order::create([
            'user_id' => null,
            'status'  => 'pending',
            'total'   => 350,
            'meta'    => [
                'customer' => [
                    'nom'       => 'Yassine Amrani',
                    'email'     => 'yassine@example.ma',
                    'telephone' => '0703-201412',
                    'societe'   => 'Société Café Azul',
                ],
                'delivery' => [
                    'adresse'     => '15 Rue Hafid Ibrahim, Hay Mohammadi',
                    'ville'       => 'Casablanca',
                    'code_postal' => '20060',
                ],
                'items' => [
                    ['product' => 'Flyer A4', 'qty' => 250, 'options' => [], 'unit' => 1.4, 'total' => 350],
                ],
                'notes' => "Livrer avant vendredi.\nMerci d'appeler avant de passer.",
            ],
        ]);

        // Un fichier joint comme le ferait le checkout.
        $storagePath = 'orders/'.uniqid('test-').'.pdf';
        \Illuminate\Support\Facades\Storage::disk('local')->put($storagePath, '%PDF-1.4 test');

        Media::create([
            'model_type' => Order::class,
            'model_id'   => $order->id,
            'path'       => '',
            'filename'   => 'flyer-a4-bat.pdf',
            'mime'       => 'application/pdf',
            'size'       => 2048,
        ]);

        return $order;
    }

    public function test_label_pdf_downloads_with_correct_content_type(): void
    {
        $order = $this->order();

        $this->actingAs($this->admin())
            ->get('/admin/orders/'.$order->id.'/etiquette-expedition.pdf')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload();
    }

    public function test_sheet_pdf_downloads_with_correct_content_type(): void
    {
        $order = $this->order();

        $this->actingAs($this->admin())
            ->get('/admin/orders/'.$order->id.'/fiche-expedition.pdf')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload();
    }

    public function test_guest_is_redirect_to_login(): void
    {
        $order = $this->order();

        $this->get('/admin/orders/'.$order->id.'/etiquette-expedition.pdf')
            ->assertRedirect('/admin/login');
    }

    public function test_pdf_contains_customer_name_address_phone_and_filename(): void
    {
        $order = $this->order();
        $svc = app(\App\Services\ShippingDocumentService::class);

        $label = $svc->labelPdf($order);
        $sheet = $svc->sheetPdf($order);

        // Le PDF est un vrai PDF.
        foreach ([$label, $sheet] as $pdf) {
            $this->assertTrue(str_starts_with($pdf, '%PDF'), 'Le document doit être un PDF.');
        }

        // L'étiquette est bien au format transporteur 10×15 cm (100×150 mm
        // = 283.465×425.197 pt) et la fiche au format A4 (595×842 pt).
        $this->assertStringContainsString('283.465', $label, "L'étiquette doit faire 100 mm de large.");
        $this->assertStringContainsString('425.197', $label, "L'étiquette doit faire 150 mm de haut.");
        $this->assertStringContainsString('595.28', $sheet, 'La fiche doit être au format A4.');

        // Le contenu rendu (HTML consommé par Dompdf) porte bien chaque
        // information d'expédition attendue.
        $labelHtml = (new \ReflectionMethod($svc, 'labelHtml'))->invoke($svc, $order);
        $sheetHtml = (new \ReflectionMethod($svc, 'sheetHtml'))->invoke($svc, $order);

        foreach (['CMD-00001', 'Yassine Amrani', '0703-201412', 'Hafid Ibrahim', '20060', 'Casablanca', 'MAROC', '<svg'] as $needle) {
            $this->assertTrue(str_contains($labelHtml, $needle), "$needle doit apparaître sur l'étiquette.");
        }

        foreach (['CMD-00001', 'Yassine Amrani', '0703-201412', 'yassine@example.ma', 'Hafid Ibrahim', 'Casablanca', 'flyer-a4-bat', 'Livrer avant vendredi', 'appeler avant de passer'] as $needle) {
            $this->assertTrue(str_contains($sheetHtml, $needle), "$needle doit apparaître sur la fiche.");
        }
    }

    public function test_order_reference_accessor_formats_as_cmd(): void
    {
        $this->assertSame('CMD-00042', (new \App\Models\Order)->forceFill(['id' => 42])->reference);
    }

}
