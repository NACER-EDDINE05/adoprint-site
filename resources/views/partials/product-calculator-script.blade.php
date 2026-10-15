{{-- Calculateur de prix en direct (API /api/price) --}}
<script type="module">
    import { getPrice } from '/js/price.js';

    const root = document.querySelector('[data-calculator]');
    if (root) {
        const productId = Number(root.dataset.productId);
        const input = root.querySelector('#qty');
        const unitEl = root.querySelector('#unit-price');
        const totalEl = root.querySelector('#total-price');
        const devisLink = root.querySelector('[data-devis-link]');
        const baseDevisUrl = devisLink ? devisLink.href.split('?')[0] : '';
        const fmt = (v) => new Intl.NumberFormat('fr-MA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(v) + ' DH';
        let timer = null;

        const refresh = async () => {
            const qty = Math.max(1, parseInt(input.value, 10) || 1);
            unitEl.textContent = '…';
            totalEl.textContent = '…';
            const unit = await getPrice(productId, qty);
            if (unit === null) {
                unitEl.textContent = 'Sur devis';
                totalEl.textContent = 'Sur devis';
            } else {
                unitEl.textContent = fmt(unit);
                totalEl.textContent = fmt(unit * qty);
            }
            if (devisLink) devisLink.href = baseDevisUrl + '?produit={{ $product->slug }}&quantite=' + qty;
        };

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(refresh, 250);
        });

        root.querySelectorAll('[data-qty-step]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const step = Number(btn.dataset.qtyStep);
                const value = parseInt(input.value, 10) || 0;
                const factor = step < 0 && value > 100 ? 50 : step > 0 && value >= 100 ? 50 : 1;
                input.value = Math.max(1, value + step * factor);
                refresh();
            });
        });

        refresh();
    }
</script>
