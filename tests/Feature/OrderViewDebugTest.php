<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderViewDebugTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_view_page_renders(): void
    {
        $user = User::factory()->create();
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin']);
        $user->assignRole('super-admin');

        $order = Order::create([
            'user_id' => $user->id,
            'status'  => 'pending',
            'total'   => 350,
            'meta'    => [
                'type'     => 'order',
                'customer' => ['nom' => 'Test Client', 'email' => 't@c.ma', 'telephone' => '0600000000'],
                'delivery' => ['adresse' => '1 rue test', 'ville' => 'Casablanca', 'code_postal' => '20000'],
                'notes'    => 'Test note',
                'items'    => [['product' => 'Flyer A4', 'qty' => 250, 'unit' => 1.4, 'total' => 350, 'options' => []]],
            ],
        ]);

        $this->actingAs($user);

        $resp = $this->get("/admin/orders/{$order->id}");

        if ($resp->status() >= 400) {
            $content = $resp->getContent();

            if (preg_match('/<h2[^>]*>([^<]+)/u', $content, $m)) {
                fwrite(STDERR, 'H2: '.strip_tags($m[1])."\n");
            }
            if (preg_match('/<h1[^>]*>([^<]+)/u', $content, $m1)) {
                fwrite(STDERR, 'H1: '.strip_tags($m1[1])."\n");
            }
            if (preg_match('/class="exception-message"[^>]*>([^<]+)/u', $content, $m2)) {
                fwrite(STDERR, 'EXC: '.strip_tags($m2[1])."\n");
            }
            fwrite(STDERR, 'status: '.$resp->status().' len: '.strlen($content)."\n");
            fwrite(STDERR, 'head: '.substr($content, 0, 400)."\n");
            $this->fail('Request failed with '.$resp->status());
        }

        $this->assertSame(200, $resp->status());
        $this->assertStringContainsString('Récapitulatif', $resp->getContent());
    }
}
