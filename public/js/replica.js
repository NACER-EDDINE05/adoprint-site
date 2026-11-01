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
     * 5. Menu mobile (hamburger) : fermeture propre du panneau #navbarText
     *    - clic sur un lien du panneau -> le menu se ferme
     *    - touche Echap -> le menu se ferme
     *    - clic a l'exterieur du panneau et du bouton hamburger -> ferme
     * ------------------------------------------------------------------ */
    (function () {
        'use strict';
        var $navbarText = $('#navbarText');
        var $toggler = $('.navbar-toggler[data-target="#navbarText"]');

        function isOpen() {
            return $navbarText.length && $navbarText.hasClass('show');
        }

        function closeMenu() {
            if (!isOpen()) return;
            $toggler.trigger('click'); // Bootstrap bascule le panneau et met a jour aria-expanded
        }

        // Fermer quand on clique sur un lien du panneau mobile
        $navbarText.on('click.collapseMobile', '.nav-link', function () {
            if (!isDesktopNav()) closeMenu();
        });

        // Fermer a la touche Echap
        $(document).on('keydown.collapseMobile', function (e) {
            if (e.key === 'Escape') closeMenu();
        });

        // Fermer en cliquant a l'exterieur (panneau et bouton exclus)
        $(document).on('click.collapseMobile', function (e) {
            if (isDesktopNav() || !isOpen()) return;
            if (!$navbarText.is(e.target) && $navbarText.has(e.target).length === 0 &&
                !$toggler.is(e.target) && $toggler.has(e.target).length === 0) {
                closeMenu();
            }
        });
    })();

})(jQuery);
