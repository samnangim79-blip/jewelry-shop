import Swal from 'sweetalert2';
import flatpickr from 'flatpickr';
import TomSelect from 'tom-select';

window.Swal = Swal;
window.flatpickr = flatpickr;
window.TomSelect = TomSelect;

/**
 * Auto-init flatpickr & Tom Select after Livewire DOM updates.
 * Inputs are opt-in via `data-flatpickr` and `data-tom-select` attributes.
 */
function initPlugins(root = document) {
    root.querySelectorAll('input[data-flatpickr]:not(.flatpickr-initialized)').forEach((el) => {
        const opts = el.dataset.flatpickrOptions ? JSON.parse(el.dataset.flatpickrOptions) : {};
        flatpickr(el, {
            enableTime: el.dataset.flatpickr === 'datetime',
            dateFormat: el.dataset.flatpickr === 'datetime' ? 'Y-m-d H:i' : 'Y-m-d',
            ...opts,
        });
        el.classList.add('flatpickr-initialized');
    });

    root.querySelectorAll('select[data-tom-select]:not(.ts-initialized)').forEach((el) => {
        const opts = el.dataset.tomSelectOptions ? JSON.parse(el.dataset.tomSelectOptions) : {};
        new TomSelect(el, {
            allowEmptyOption: true,
            create: false,
            ...opts,
        });
        el.classList.add('ts-initialized');
    });
}

document.addEventListener('DOMContentLoaded', () => initPlugins());

document.addEventListener('livewire:init', () => {
    if (window.Livewire) {
        window.Livewire.hook('morph.added', ({ el }) => initPlugins(el));
        window.Livewire.hook('commit', ({ succeed }) => {
            succeed(() => queueMicrotask(() => initPlugins()));
        });
    }
});

/**
 * Confirm-delete helper triggered by `wire:click="$dispatch('confirm-delete', { method: ..., params: { ... } })"`.
 * The dispatch is handled in resources/views/admin/layouts/admin_partials/scripts.blade.php.
 */
