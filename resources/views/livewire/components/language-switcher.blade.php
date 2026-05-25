<div class="dropdown"
     x-data="{ open: false }"
     @click.outside="open = false"
     @keydown.escape.window="open = false"
     wire:ignore.self>
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret d-flex align-items-center gap-2"
       href="javascript:;"
       role="button"
       @click.prevent="open = !open"
       :aria-expanded="open.toString()">
        <i class="bi bi-translate"></i>
        <span class="d-none d-md-inline">{{ $currentLocale === 'km' ? 'ខ្មែរ' : 'EN' }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" :class="open ? 'show' : ''">
        <li>
            <button type="button"
                    class="dropdown-item d-flex align-items-center gap-2 {{ $currentLocale === 'en' ? 'active' : '' }}"
                    @click="open = false"
                    wire:click="switchLocale('en')">
                <img src="{{ asset('assets/backend') }}/assets/flags/1x1/us.svg" width="18" height="18" class="rounded-circle" alt="EN">
                <span>{{ __('messages.english') }}</span>
            </button>
        </li>
        <li>
            <button type="button"
                    class="dropdown-item d-flex align-items-center gap-2 {{ $currentLocale === 'km' ? 'active' : '' }}"
                    @click="open = false"
                    wire:click="switchLocale('km')">
                <img src="{{ asset('assets/backend') }}/assets/flags/1x1/kh.svg" width="18" height="18" class="rounded-circle" alt="KH">
                <span>{{ __('messages.khmer') }}</span>
            </button>
        </li>
    </ul>
</div>
