<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\GuidesController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

// ------------------------------------------------------------------
// Boutique publique (structure inspirée d'une imprimerie en ligne)
// ------------------------------------------------------------------

Route::get('/', [StorefrontController::class, 'home'])->name('home');

Route::get('/produits', [StorefrontController::class, 'productsIndex'])->name('products.index');

Route::get('/categorie/{slug}', [StorefrontController::class, 'category'])->name('category.show');

Route::get('/produit/{slug}', [StorefrontController::class, 'product'])->name('product.show');

Route::get('/aide', [StorefrontController::class, 'aide'])->name('aide');

// À propos — présentation de l'imprimerie ADO PRINT
Route::get('/a-propos', [StorefrontController::class, 'apropos'])->name('apropos');

// Ressources & conseils (guides d'impression)
Route::get('/guides', [GuidesController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuidesController::class, 'show'])->name('guides.show');

Route::get('/contact', [StorefrontController::class, 'contact'])->name('contact');
Route::post('/contact', [DevisController::class, 'contact'])->name('contact.send');

Route::get('/devis', [DevisController::class, 'show'])->name('devis');
Route::post('/devis', [DevisController::class, 'store'])->name('devis.send');

// ------------------------------------------------------------------
// Panier & commande en ligne
// ------------------------------------------------------------------

Route::get('/panier', [CheckoutController::class, 'cart'])->name('cart');
Route::post('/panier/ajouter', [CheckoutController::class, 'add'])->name('cart.add');
Route::post('/panier/mettre-a-jour', [CheckoutController::class, 'update'])->name('cart.update');
Route::post('/panier/retirer', [CheckoutController::class, 'remove'])->name('cart.remove');

Route::get('/commander', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/commander', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/commande/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// L' espace /admin est servi par le panneau Filament (voir App\Providers\Filament\AdminPanelProvider),
// qui gère son propre routage, son authentification et sa page de connexion.

