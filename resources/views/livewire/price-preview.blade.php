<div class="price-preview">
    <label for="qty">Quantité</label>
    <input id="qty" type="number" min="1" wire:model.debounce.300ms="qty" class="block mt-1" />

    <div class="mt-2">
        <strong>Prix unitaire : </strong>
        @if($price === null)
            <span class="text-gray-500">Aucun tarif</span>
        @else
            <span class="text-green-600">{{ number_format($price, 2, ',', ' ') }} DH</span>
        @endif
    </div>

    @if($productId === null)
        <div class="mt-1 text-sm text-gray-500 flex items-start gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <div>L'aperçu en direct s'affichera une fois le produit enregistré.</div>
                <div dir="rtl" class="text-sm text-gray-500 mt-0.5">ستظهر المعاينة المباشرة بعد حفظ المنتج.</div>
            </div>
        </div>
    @endif
</div>
