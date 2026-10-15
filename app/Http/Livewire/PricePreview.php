<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\PriceService;

class PricePreview extends Component
{
    public ?int $productId = null;
    public int $qty = 1;
    public ?float $price = null;

    protected PriceService $service;

    public function mount(?int $productId = null, int $qty = 1)
    {
        $this->productId = $productId;
        $this->qty = $qty;
        $this->service = app(PriceService::class);
        $this->loadPrice();
    }

    public function updatedQty()
    {
        $this->loadPrice();
    }

    public function loadPrice(): void
    {
        if ($this->productId === null) {
            $this->price = null;
            return;
        }

        $this->price = $this->service->getPriceForQuantity($this->productId, $this->qty);
    }

    public function render()
    {
        return view('livewire.price-preview');
    }
}
