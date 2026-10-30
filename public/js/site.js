/**
 * Boutique publique (copie fidèle de la boutique de référence)
 * © Studio 24hprint — réplique autorisée pour ADO PRINT
 */

/* ------------------------------------------------------------------
   Menu mobile : le bouton burger ouvre un panneau latéral
   contenant une copie de la navigation par familles.
   ------------------------------------------------------------------ */
(function initMobileMenu() {
    const btn = document.getElementById('ap24-menu-btn');
    const overlay = document.getElementById('ap24-menu-overlay');
    if (!btn || !overlay) return;

    const closeBtn = overlay.querySelector('.ap24-menu-overlay__close');
    const body = overlay.querySelector('.ap24-menu-overlay__body');
    const nav = document.querySelector('.ap24-header .ap24-nav');
    let cloned = false;

    function openMenu() {
        if (nav && !cloned && body) {
            body.appendChild(nav.cloneNode(true));
            cloned = true;
        }
        overlay.style.display = 'block';
        overlay.setAttribute('aria-hidden', 'false');
        btn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        overlay.style.display = 'none';
        overlay.setAttribute('aria-hidden', 'true');
        btn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    btn.addEventListener('click', openMenu);
    closeBtn?.addEventListener('click', closeMenu);
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeMenu();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay.style.display === 'block') closeMenu();
    });
    body?.addEventListener('click', (e) => {
        if (e.target.closest('a')) closeMenu();
    });
})();

/* ------------------------------------------------------------------
   Panier latéral : affichage instantané et transition fluide
   ------------------------------------------------------------------ */
const cartDrawer = document.querySelector('#cart-drawer');
const cartOverlay = document.querySelector('#cart-overlay');
const cartBadge = document.querySelector('#cart-count');

let cartVisible = false;

function updateCartBadge(count) {
    if (!cartBadge) return;
    cartBadge.textContent = count;
    cartBadge.classList.toggle('is-hidden', count <= 0);
}

function openCart() {
    if (!cartDrawer || !cartOverlay) return;
    cartVisible = true;
    cartDrawer.classList.remove('is-hidden');
    cartOverlay.classList.remove('is-hidden');
    document.body.style.overflow = 'hidden';
    cartDrawer.addEventListener('transitionend', function onClose() {
        if (!cartDrawer.classList.contains('is-hidden')) {
            cartDrawer.removeEventListener('transitionend', onClose);
        }
    });
}

function closeCart() {
    if (!cartDrawer || !cartOverlay) return;
    cartVisible = false;
    cartDrawer.classList.add('is-hidden');
    cartOverlay.classList.add('is-hidden');
    document.body.style.overflow = '';
}

function drawCart(cart) {
    if (!cart || !cart.items || cart.items.length === 0) {
        if (!cartDrawer) return;
        const empty = cartDrawer.querySelector('.cart-empty');
        const list = cartDrawer.querySelector('.cart-items');
        const footer = cartDrawer.querySelector('.cart-footer');
        if (empty) empty.classList.remove('is-hidden');
        if (list) list.innerHTML = '';
        if (footer) footer.classList.add('is-hidden');
        updateCartBadge(0);
        return;
    }

    const empty = cartDrawer.querySelector('.cart-empty');
    const list = cartDrawer.querySelector('.cart-items');
    const footer = cartDrawer.querySelector('.cart-footer');
    if (empty) empty.classList.add('is-hidden');

    list.innerHTML = cart.items.map((item) => {
        const qty = item.qty || 1;
        const price = (item.price || 0) * qty;
        return `
            <li class="cart-item">
                <figure class="cart-item__media">
                    <img src="${item.image || '/img/placeholder.png'}"
                         alt="${item.name || ''}"
                         width="72" height="72" loading="lazy" decoding="async">
                </figure>
                <div class="cart-item__body">
                    <h4 class="cart-item__name">${item.name || ''}
                        <a href="/produit/${item.slug || ''}" class="cart-item__link">Voir le produit</a>
                    </h4>
                    <p class="cart-item__meta">Réf. ${item.reference || '—'} · ${item.finishing || ''}</p>
                    <div class="cart-item__row">
                        <button class="cart-item__qty cart-item__qty--minus" data-action="dec">-</button>
                        <input type="number" class="cart-item__qty cart-item__qty--input"
                               value="${qty}" min="1" data-ref="${item.reference || ''}">
                        <button class="cart-item__qty cart-item__qty--plus" data-action="inc">+</button>
                    </div>
                </div>
                <div class="cart-item__price">${price.toFixed(2)} DH</div>
                <button class="cart-item__remove" data-action="remove" aria-label="Retirer du panier">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </button>
            </li>`;
    }).join('');

    const totals = cartDrawer.querySelector('.cart-totals');
    if (totals) {
        totals.innerHTML = `
            <div class="cart-totals__row">
                <span>Sous-total HT</span>
                <span>${cart.subtotal.toFixed(2)} DH</span>
            </div>
            <div class="cart-totals__row">
                <span>Frais de livraison</span>
                <span class="cart-totals__free">Gratuits dès 500 DH HT</span>
            </div>
            <div class="cart-totals__row cart-totals__row--total">
                <span>Total HT</span>
                <span>${cart.subtotal.toFixed(2)} DH</span>
            </div>`;
    }

    updateCartBadge(cart.count);
}

/* Rendu initial depuis le HTML serveur et écoute des événements du panier */
(function initCartUI() {
    const drawer = document.getElementById('cart-drawer');
    if (!drawer) return;

    const badge = drawer.querySelector('.cart-badge');
    const count = drawer.querySelector('.cart-count');
    const list = drawer.querySelector('.cart-items');
    const qtyInputs = drawer.querySelectorAll('.cart-item__qty--input');
    const decButtons = drawer.querySelectorAll('.cart-item__qty--minus');
    const incButtons = drawer.querySelectorAll('.cart-item__qty--plus');
    const removeButtons = drawer.querySelectorAll('.cart-item__remove');

    // Badge initial servi par le serveur
    if (badge) cartBadge = badge;
    if (count) updateCartBadge(parseInt(count.textContent, 10) || 0);

    // Fermeture : bouton « Fermer » et overlay
    const closeBtns = drawer.querySelectorAll('[data-close-cart]');
    closeBtns.forEach(btn => btn.addEventListener('click', closeCart));
    if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

    // Quantité : +/- boutons
    decButtons.forEach(btn => btn.addEventListener('click', function() {
        const input = btn.closest('.cart-item__row')?.querySelector('.cart-item__qty--input');
        if (!input) return;
        let val = parseInt(input.value, 10) || 1;
        if (val > 1) {
            input.value = val - 1;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }));

    incButtons.forEach(btn => btn.addEventListener('click', function() {
        const input = btn.closest('.cart-item__row')?.querySelector('.cart-item__qty--input');
        if (!input) return;
        let val = parseInt(input.value, 10) || 1;
        input.value = val + 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }));

    // Retirer : bouton croix
    removeButtons.forEach(btn => btn.addEventListener('click', function() {
        const row = btn.closest('.cart-item');
        if (!row) return;
        const ref = row.querySelector('.cart-item__qty--input')?.dataset.ref;
        if (ref) {
            row.style.transition = 'opacity .2s ease, transform .2s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            setTimeout(() => {
                row.remove();
                const items = list?.querySelectorAll('.cart-item') || [];
                if (items.length === 0) {
                    const empty = drawer.querySelector('.cart-empty');
                    if (empty) empty.classList.remove('is-hidden');
                    if (list) list.innerHTML = '';
                    const footer = drawer.querySelector('.cart-footer');
                    if (footer) footer.classList.add('is-hidden');
                    updateCartBadge(0);
                }
            }, 200);
        }
    }));

    // Quand le serveur injecte le panier dans le HTML, on le dessine une première fois
    const preloaded = drawer.querySelector('[data-cart-preload]');
    if (preloaded) {
        try {
            const data = JSON.parse(preloaded.dataset.cartPreload);
            if (data && data.items) drawCart(data);
        } catch (e) {
            // le serveur n'a pas encore injecté le panier
        }
    }

    // Observateur : à chaque mise à jour du serveur, on rafraîchit l'affichage
    if (list) {
        const observer = new MutationObserver(() => {
            const items = list.querySelectorAll('.cart-item');
            if (items.length === 0) {
                const empty = drawer.querySelector('.cart-empty');
                if (empty) empty.classList.remove('is-hidden');
                const footer = drawer.querySelector('.cart-footer');
                if (footer) footer.classList.add('is-hidden');
                updateCartBadge(0);
            } else {
                const countEl = drawer.querySelector('.cart-count');
                updateCartBadge(parseInt(countEl?.textContent, 10) || items.length);
            }
        });
        observer.observe(list, { childList: true, subtree: true });
    }
})();
