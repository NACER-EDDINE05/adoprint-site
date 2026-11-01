/* ==========================================================================
   ADO PRINT — réplique : comportements du gabarit
   (menus déroulants au survol, panneau image méga-menu, recherche mobile)
   ========================================================================== */
(function ($) {
    'use strict';

    var isDesktopNav = function () {
        return $(window).width() >= 992;
    };

    /* ------------------------------------------------------------------
     * 1. Ouverture des mega-menus au survol (desktop)
     * ------------------------------------------------------------------ */
    $('.navbar-expand-lg .navbar-nav .nav-item.dropdown').each(function () {
        var item = $(this);
        var timer = null;
        var delay = 180;

        item.on('mouseenter.megamenu', function () {
            if (!isDesktopNav()) return;
            clearTimeout(timer);
            timer = setTimeout(function () {
                item.addClass('show');
                item.children('.nav-link').attr('aria-expanded', 'true');
                item.find('.dropdown-menu').addClass('show');
                item.trigger('shown.bs.dropdown');
                restoreMegamenuDefault(item);
            }, delay);
        });

        item.on('mouseleave.megamenu', function () {
            if (!isDesktopNav()) return;
            clearTimeout(timer);
            timer = setTimeout(function () {
                item.removeClass('show');
                item.children('.nav-link').attr('aria-expanded', 'false');
                item.find('.dropdown-menu').removeClass('show');
                item.trigger('hidden.bs.dropdown');
            }, delay);
        });
    });

    /* ------------------------------------------------------------------
     * 2. Panneau image du méga-menu : swap au survol des liens
     * ------------------------------------------------------------------ */
    function megamenuDisplay($container, data) {
        if (!$container.length || !data) return;

        var show = !!(data.img || data.desc);
        var $link = $container.find('.megamenu-image-link');
        var $img = $container.find('.megamenu-image');
        var $name = $container.find('.megamenu-image-name');
        var $desc = $container.find('.megamenu-image-description');
        var $btn = $container.find('.megamenu-image-btn');

        if (data.img) {
            $link.removeClass('d-none');
            $img.attr({ src: data.img, alt: data.name || '' });
        } else {
            $link.addClass('d-none');
        }

        $name.text(data.name || '');
        $desc.html(data.desc || '');

        var url = data.url || '#';
        if (show) {
            $btn.attr('href', url).removeClass('d-none');
            $name.removeClass('d-none');
            $desc.removeClass('d-none');
            if (data.img) $link.removeClass('d-none');
        } else {
            $btn.addClass('d-none');
            $name.addClass('d-none');
            $desc.addClass('d-none');
        }
    }

    function megamenuDefaults($item) {
        var $panel = $item.find('.megamenu-image-container');
        if (!$panel.length) return null;
        return {
            img: $panel.attr('data-default-img') || '',
            name: $panel.attr('data-default-name') || '',
            desc: $panel.attr('data-default-desc') || '',
            url: $panel.attr('data-default-url') || '#'
        };
    }

    function restoreMegamenuDefault($item) {
        var $panel = $item.find('.megamenu-image-container');
        if (!$panel.length) return;
        var d = megamenuDefaults($item);
        if (d) megamenuDisplay($panel, d);
    }

    $(document).on('mouseenter.megamenu',
        '.megamenu-category-link, .megamenu-product-link', function () {
        if (!isDesktopNav()) return;
        var $a = $(this);
        var data = {
            img: $a.attr('data-img') || '',
            name: $a.attr('data-name') || '',
            desc: $a.attr('data-desc') || '',
            url: $a.attr('data-url') || '#'
        };
        megamenuDisplay($a.closest('.dropdown-menu').find('.megamenu-image-container'), data);
    });

    /* ------------------------------------------------------------------
     * 3. Recherche : bouton mobile + soumission
     * ------------------------------------------------------------------ */
    $('#search-toggle-btn').on('click', function () {
        var $panel = $(this).siblings('.search-toggle');
        $panel.toggle();
        if ($panel.is(':visible')) {
            $panel.find('input[name=q]').trigger('focus');
        }
    });

    /* ------------------------------------------------------------------
     * 4. Newsletter du pied de page -> page contact (démo sans backend dédié)
     * ------------------------------------------------------------------ */
    window.footerSubscribe = function (form) {
        var email = $(form).find('input[type=email]').val();
        if (!email || email.indexOf('@') === -1) {
            $(form).find('input[type=email]').addClass('is-invalid');
            return false;
        }
        window.location = $('meta[name=route-contact]').attr('content') || '/contact';
        return false;
    };

    /* ------------------------------------------------------------------
     * 5. Menu mobile (hamburger) : drawer latéral gauche (moitié écran)
     *    Ouverture/fermeture fluide (glissement), fond dimmed sans flou.
     *    - clic sur le bouton hamburger -> ouvre/ferme
     *    - clic sur un lien du panneau -> ferme
     *    - touche Echap -> ferme
     *    - clic sur le fond dimmed ou à l'extérieur du panneau -> ferme
     * ------------------------------------------------------------------ */
    (function () {
        'use strict';
        var $toggler = $('.navbar-toggler[data-target="#navbarText"]');
        var $navbarText = $('#navbarText');
        var $body = $('body');
        var isOpen = false;
        var closeTimer = null;

        // On désactive le collapse Bootstrap natif pour piloter le glissement nous-mêmes
        $toggler.removeAttr('data-toggle');

        function setState(open) {
            isOpen = open;
            $toggler.attr('aria-expanded', open ? 'true' : 'false');
            if (closeTimer) { clearTimeout(closeTimer); closeTimer = null; }

            if (open) {
                $body.addClass('navbar-open');
                $navbarText.addClass('nav-open');
                $navbarText.css({ display: 'block', transform: 'translateX(0)', opacity: '1', visibility: 'visible' });
            } else {
                $body.removeClass('navbar-open');
                $navbarText.removeClass('nav-open');
                $navbarText.css({ transform: 'translateX(-100%)', opacity: '0', visibility: 'hidden' });
                closeTimer = setTimeout(function () {
                    $navbarText.css('display', 'none');
                    closeTimer = null;
                }, 420);
            }
        }

        function toggle() {
            setState(!isOpen);
        }

        // Clic sur le bouton hamburger
        $toggler.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggle();
        });

        // Fermer sur un lien du panneau
        $navbarText.on('click', '.nav-link', function () {
            if (!isDesktopNav()) setState(false);
        });

        // Fermer à Echap
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') setState(false);
        });

        // Fermer en cliquant sur le fond dimmed ou à l'extérieur du panneau
        $(document).on('click', function (e) {
            if (!isOpen || isDesktopNav()) return;
            if (!$navbarText.is(e.target) && $navbarText.has(e.target).length === 0 &&
                !$toggler.is(e.target) && $toggler.has(e.target).length === 0) {
                setState(false);
            }
        });
    })();

})(jQuery);
