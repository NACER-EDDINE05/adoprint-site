<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

/**
 * Panier de commande basé sur la session.
 * Chaque ligne : product_id, qty, options (nom => valeur).
 * Le prix unitaire est TOUJOURS recalculé depuis les grilles tarifaires
 * (PriceService) — jamais stocké dans la session.
 */
class CartService
{
    public const SESSION_KEY = 'cart';

    public function __construct(protected PriceService $prices)
    {
    }

    /** @return array<int, array{row_id:string, product:Product, qty:int, options:array, unit:float, total:float}> */
    public function items(): array
    {
        $rows = Session::get(self::SESSION_KEY, []);
        $items = [];

        foreach ($rows as $row) {
            $product = Product::with('category')->find($row['product_id'] ?? null);
            if (! $product || ! $product->active) {
                continue; // produit supprimé/désactivé : ligne ignorée
            }

            $qty = max(1, (int) ($row['qty'] ?? 1));
            $unit = (float) ($this->prices->getPriceForQuantity($product->id, $qty)
                ?? $product->base_price ?? 0);

            $items[] = [
                'row_id'  => $row['row_id'],
                'product' => $product,
                'qty'     => $qty,
                'options' => $row['options'] ?? [],
                'unit'    => $unit,
                'total'   => $unit * $qty,
            ];
        }

        return $items;
    }

    public function add(Product $product, int $qty, array $options = []): string
    {
        $rows = Session::get(self::SESSION_KEY, []);
        $rowId = uniqid('row_');

        $rows[] = [
            'row_id'     => $rowId,
            'product_id' => $product->id,
            'qty'        => max(1, $qty),
            'options'    => $options,
        ];

        Session::put(self::SESSION_KEY, $rows);

        return $rowId;
    }

    public function update(string $rowId, int $qty): void
    {
        $rows = Session::get(self::SESSION_KEY, []);

        foreach ($rows as $i => $row) {
            if (($row['row_id'] ?? null) === $rowId) {
                if ($qty < 1) {
                    unset($rows[$i]);
                } else {
                    $rows[$i]['qty'] = min($qty, 1000000);
                }
                break;
            }
        }

        Session::put(self::SESSION_KEY, array_values($rows));
    }

    public function remove(string $rowId): void
    {
        $rows = collect(Session::get(self::SESSION_KEY, []))
            ->reject(fn ($row) => ($row['row_id'] ?? null) === $rowId)
            ->values()
            ->all();

        Session::put(self::SESSION_KEY, $rows);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return count($this->items());
    }

    public function total(): float
    {
        return collect($this->items())->sum('total');
    }
}
