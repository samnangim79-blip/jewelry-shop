<div class="dropdown" wire:ignore.self>
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret d-flex align-items-center gap-2"
       href="javascript:;"
       data-bs-toggle="dropdown"
       aria-expanded="false">
        <i class="bi bi-translate"></i>
        <span class="d-none d-md-inline">{{ $currentLocale === 'km' ? 'ខ្មែរ' : 'EN' }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <button type="button"
                    class="dropdown-item d-flex align-items-center gap-2 {{ $currentLocale === 'en' ? 'active' : '' }}"
                    wire:click="switchLocale('en')">
                <img src="{{ asset('assets/backend') }}/assets/flags/1x1/us.svg" width="18" height="18" class="rounded-circle" alt="EN">
                <span>{{ __('messages.english') }}</span>
            </button>
        </li>
        <li>
            <button type="button"
                    class="dropdown-item d-flex align-items-center gap-2 {{ $currentLocale === 'km' ? 'active' : '' }}"
                    wire:click="switchLocale('km')">
                <img src="{{ asset('assets/backend') }}/assets/flags/1x1/kh.svg" width="18" height="18" class="rounded-circle" alt="KH">
                <span>{{ __('messages.khmer') }}</span>
            </button>
        </li>
    </ul>
</div>
