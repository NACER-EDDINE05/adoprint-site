<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="fi-form-actions mt-4 flex items-center gap-3">
            <button type="submit"
                class="fi-btn inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-sm font-bold text-white transition"
                style="background: rgb(0, 174, 239)">
                Enregistrer
            </button>
        </div>

        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
            La photo s’applique immédiatement sur
            <a href="{{ url('/') }}" target="_blank" class="font-semibold" style="color: rgb(0, 137, 193)">la page d’accueil</a>.
        </p>
    </form>
</x-filament-panels::page>
