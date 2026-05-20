<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <img src="{{ asset('assets/backend') }}/assets/images/logo-icon.png" width="40" alt="logo">
        </div>
        @livewire('components.language-switcher')
    </div>
    <h4 class="fw-bold">{{ __('messages.sign_in') }}</h4>
    <p class="mb-0">{{ __('messages.welcome_back') }}</p>

    <div class="form-body mt-4">
        <form class="row g-3" wire:submit="authenticate">
            <div class="col-12">
                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    autocomplete="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="jhon@example.com"
                />
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label for="password" class="form-label">{{ __('messages.password') }}</label>
                <div class="input-group" x-data="{ show: false }">
                    <input
                        wire:model="password"
                        :type="show ? 'text' : 'password'"
                        id="password"
                        autocomplete="current-password"
                        class="form-control border-end-0 @error('password') is-invalid @enderror"
                        placeholder="Enter Password"
                    />
                    <button class="input-group-text bg-transparent" type="button" @click="show = !show">
                        <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input wire:model="remember" class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">{{ __('messages.remember_me') }}</label>
                </div>
            </div>
            <div class="col-12">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="authenticate">
                        <span wire:loading.remove wire:target="authenticate">
                            <i class="bi bi-lock-fill me-1"></i> {{ __('messages.sign_in') }}
                        </span>
                        <span wire:loading wire:target="authenticate">
                            <span class="spinner-border spinner-border-sm me-1"></span> ...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
