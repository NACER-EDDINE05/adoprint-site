import posthog from 'posthog-js';

/**
 * Mesure d'audience ADO PRINT (PostHog).
 *
 * La clé publique du projet est injectée dans le document par la vue
 * layouts.storefront (window.__adoPosthog, alimenté par config/services.php).
 * Sans clé configurée — poste local, recette — le module ne fait rien :
 * aucune requête n'est émise et aucune bannière n'est affichée.
 *
 * Consentement : PostHog n'est initialisé qu'après un accord explicite
 * (bannière partials.analytics-consent). Le choix est mémorisé dans le
 * navigateur, l'option respect_dnt est active, et les événements émis avant
 * la réponse du visiteur restent en file d'attente sur la page : ils sont
 * envoyés à l'acceptation, abandonnés au refus.
 *
 * Identification : les visiteurs restent anonymes (identifiant PostHog
 * persistant) jusqu'à une commande, où l'identifiant devient l'empreinte
 * SHA-256 de l'email (aucune donnée directement identifiante n'est envoyée).
 */

const CONSENT_KEY = 'ado:analytics-consent';
const config = window.__adoPosthog || {};
const configured = Boolean(config.key);

let client = null;
let consent = null;
const pending = [];

const readConsent = () => {
    try {
        return localStorage.getItem(CONSENT_KEY);
    } catch (error) {
        return null;
    }
};

const writeConsent = (value) => {
    try {
        localStorage.setItem(CONSENT_KEY, value);
    } catch (error) {
        // Navigation privée : le choix vaut pour la page en cours uniquement.
    }
};

const send = (method, args) => {
    if (!configured || consent === 'denied') return;

    if (client) {
        client[method](...args);
        return;
    }

    if (consent === null) {
        pending.push([method, args]);
    }
};

const flush = () => {
    if (!client) return;

    while (pending.length) {
        const [method, args] = pending.shift();
        client[method](...args);
    }
};

const start = () => {
    if (!configured || client) return;

    posthog.init(config.key, {
        api_host: config.host,
        // Événements explicites uniquement : pas d'autocapture du texte des
        // formulaires (noms, emails, adresses) ni d'enregistrement de session.
        capture_pageview: false,
        capture_pageleave: false,
        autocapture: false,
        disable_session_recording: true,
        person_profiles: 'identified_only',
        persistence: 'localStorage+cookie',
        respect_dnt: true,
        loaded: (ph) => {
            ph.register({
                site: 'ado-print-boutique',
                environment: config.environment,
            });
        },
    });

    client = posthog;

    client.capture('$pageview', {
        $current_url: window.location.href,
        $pathname: window.location.pathname,
    });

    flush();
};

const banner = document.querySelector('[data-analytics-consent-banner]');

const paintBanner = () => {
    if (banner) banner.hidden = consent !== null;
};

const decide = (choice) => {
    consent = choice;
    writeConsent(choice);

    if (choice === 'granted') {
        start();
    } else {
        pending.length = 0;
    }

    paintBanner();
};

const api = {
    get consent() {
        return consent;
    },
    capture: (event, properties = {}) => send('capture', [event, properties]),
    identify: (distinctId, properties = {}) => send('identify', [distinctId, properties]),
    grant: () => decide('granted'),
    deny: () => decide('denied'),
};

// Événements poussés par les vues avant le chargement de ce bundle.
const early = Array.isArray(window.adoAnalytics?.queue) ? window.adoAnalytics.queue : [];
window.adoAnalytics = api;
early.forEach((entry) => {
    if (Array.isArray(entry) && (entry[0] === 'capture' || entry[0] === 'identify')) {
        pending.push([entry[0], entry.slice(1)]);
    }
});

const stored = readConsent();
consent = stored === 'granted' || stored === 'denied' ? stored : null;

if (banner) {
    banner.querySelectorAll('[data-analytics-consent]').forEach((button) => {
        button.addEventListener('click', () => decide(button.dataset.analyticsConsent === 'granted' ? 'granted' : 'denied'));
    });
}

paintBanner();

if (consent === 'granted') {
    start();
}
