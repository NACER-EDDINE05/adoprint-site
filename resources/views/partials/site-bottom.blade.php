    @include('partials.footer')

    {{-- Message de succès global --}}
    @if (session('success'))
        <div class="fixed bottom-6 left-1/2 z-[60] w-[92%] max-w-md -translate-x-1/2 rounded-xl border border-green-200 bg-white p-4 shadow-xl">
            <div class="flex items-start gap-3">
                <svg class="h-5 w-5 shrink-0 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                <p class="text-sm text-slate-700">{{ session('success') }}</p>
                <button type="button" onclick="this.closest('div.fixed').remove()" class="ml-auto text-slate-400 hover:text-slate-600" aria-label="Fermer">✕</button>
            </div>
        </div>
    @endif

    {{-- Bouton flottant service client (WhatsApp) --}}
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->get('company.whatsapp', $siteSettings->get('company.phone', ''))) }}"
       target="_blank" rel="noopener"
       class="fixed bottom-5 right-5 z-50 grid h-14 w-14 place-items-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105"
       title="Service client WhatsApp">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2a9 9 0 0 1 9 9"/><path d="M13 6a5 5 0 0 1 5 5"/><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
    </a>
</body>
</html>
