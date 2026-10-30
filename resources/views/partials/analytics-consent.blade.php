{{-- Bannière de consentement — mesure d'audience PostHog (resources/js/analytics.js).
     Masquée par défaut : le module l'affiche uniquement si aucun choix n'a encore
     été mémorisé, et seulement quand une clé PostHog est configurée. --}}
@php($posthogKey = config('services.posthog.key'))

@if ($posthogKey)
    <div data-analytics-consent-banner hidden
         class="fixed bottom-5 left-5 z-[70] w-[92%] max-w-sm rounded-xl border border-slate-200 bg-white p-4 shadow-xl"
         role="dialog" aria-label="Consentement mesure d'audience">
        <p class="text-sm font-extrabold text-slate-900">Mesure d'audience</p>
        <p class="mt-1 text-xs leading-relaxed text-slate-600">
            Nous aimerions mesurer l'utilisation du site (pages vues, produits consultés, commandes)
            avec PostHog afin d'améliorer le catalogue et le parcours de commande.
            Aucune donnée n'est envoyée sans votre accord.
        </p>
        <div class="mt-3 flex flex-wrap gap-2">
            <button type="button" data-analytics-consent="granted"
                    class="ap24-btn ap24-btn--yellow ap24-btn--sm ap24-btn--flat">J'accepte</button>
            <button type="button" data-analytics-consent="denied"
                    class="ap24-btn ap24-btn--ghost ap24-btn--sm ap24-btn--flat">Je refuse</button>
        </div>
    </div>
@endif
