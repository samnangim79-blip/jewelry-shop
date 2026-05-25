<header class="top-header">
    <nav class="navbar navbar-expand">
        <div class="mobile-toggle-icon d-xl-none">
            <i class="bi bi-list"></i>
        </div>

        <div class="top-navbar d-none d-xl-block">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link" wire:navigate href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a>
                </li>
            </ul>
        </div>

        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    @livewire('components.language-switcher')
                </li>
                <li class="nav-item dropdown dropdown-large"
                    x-data="{ open: false }"
                    @click.outside="open = false"
                    @keydown.escape.window="open = false">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret"
                       href="javascript:;"
                       role="button"
                       @click.prevent="open = !open"
                       :aria-expanded="open.toString()">
                        <div class="user-setting d-flex align-items-center gap-1">
                            <img src="{{ asset('assets/backend') }}/assets/images/avatars/avatar-1.png" class="user-img" alt="">
                            <div class="user-name d-none d-sm-block">{{ auth()->user()?->name }}</div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" :class="open ? 'show' : ''">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/backend') }}/assets/images/avatars/avatar-1.png" alt="" class="rounded-circle" width="60" height="60">
                                    <div class="ms-3">
                                        <h6 class="mb-0 dropdown-user-name">{{ auth()->user()?->name }}</h6>
                                        <small class="mb-0 dropdown-user-designation text-secondary">{{ ucwords(str_replace('_', ' ', auth()->user()?->user_type ?? '')) }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="setting-icon"><i class="bi bi-person-fill"></i></div>
                                    <div class="setting-text ms-3"><span>{{ __('messages.profile') }}</span></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="setting-icon"><i class="bi bi-gear-fill"></i></div>
                                    <div class="setting-text ms-3"><span>{{ __('messages.settings') }}</span></div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                                    <div class="d-flex align-items-center">
                                        <div class="setting-icon"><i class="bi bi-lock-fill"></i></div>
                                        <div class="setting-text ms-3"><span>{{ __('messages.logout') }}</span></div>
                                    </div>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
