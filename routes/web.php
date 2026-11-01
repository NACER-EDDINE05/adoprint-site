<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MediaFileController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\GuidesController;
use App\Http\Controllers\StorefrontController;
use App\Services\ShippingDocumentService;
use Illuminate\Support\Facades\Route;

// ------------------------------------------------------------------
// Boutique publique
// ------------------------------------------------------------------

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/produits', [StorefrontController::class, 'productsIndex'])->name('products.index');
Route::get('/categorie/{slug}', [StorefrontController::class, 'category'])->name('category.show');
Route::get('/produit/{slug}', [StorefrontController::class, 'product'])->name('product.show');
Route::get('/aide', [StorefrontController::class, 'aide'])->name('aide');
Route::get('/a-propos', [StorefrontController::class, 'apropos'])->name('a-propos');
Route::get('/guides', [GuidesController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuidesController::class, 'show'])->name('guides.show');
Route::get('/contact', [StorefrontController::class, 'contact'])->name('contact');
Route::post('/contact', [DevisController::class, 'contact'])->name('contact.send');
Route::get('/devis', [DevisController::class, 'show'])->name('devis');
Route::post('/devis', [DevisController::class, 'store'])->name('devis.send');

// ------------------------------------------------------------------
// Panier & commande
// ------------------------------------------------------------------

Route::get('/panier', [CheckoutController::class, 'cart'])->name('cart');
Route::post('/panier/ajouter', [CheckoutController::class, 'add'])->name('cart.add');
Route::post('/panier/mettre-a-jour', [CheckoutController::class, 'update'])->name('cart.update');
Route::post('/panier/retirer', [CheckoutController::class, 'remove'])->name('cart.remove');
Route::get('/commander', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/commander', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/commande/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// ------------------------------------------------------------------
// Documents d'expédition
// ------------------------------------------------------------------

Route::middleware(['web', 'auth'])
    ->prefix('admin/medias')
    ->name('admin.media.')
    ->group(function () {
        Route::get('/{media}/telecharger', [MediaFileController::class, 'download'])->name('download');
        Route::get('/{media}/voir', [MediaFileController::class, 'show'])->name('show');
    });

Route::middleware(['web', 'auth'])
    ->prefix('admin/orders')
    ->name('orders.shipping.')
    ->group(function () {
        Route::get('/{order}/etiquette-expedition.pdf', [ShippingDocumentService::class, 'downloadLabel'])->name('label');
        Route::get('/{order}/fiche-expedition.pdf', [ShippingDocumentService::class, 'downloadSheet'])->name('sheet');
        Route::get('/{order}/export-expedition.csv', [ShippingDocumentService::class, 'downloadCsv'])->name('csv');
    });

Route::middleware(['web', 'auth'])
    ->prefix('admin/expedition')
    ->name('admin.expedition.')
    ->group(function () {
        Route::get('/export-csv', function (\Illuminate\Http\Request $request) {
            $ids = $request->query('ids');
            if (is_string($ids)) {
                $ids = explode(',', $ids);
            }
            $orders = \App\Models\Order::whereIn('id', (array) $ids)->orderBy('created_at', 'desc')->get();
            return app(ShippingDocumentService::class)->downloadBulkCsv($orders);
        })->name('export.csv');
    });
