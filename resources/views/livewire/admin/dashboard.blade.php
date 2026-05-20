<div>
    @section('pageBreadcrumb', __('messages.dashboard'))
    @section('pageBreadcrumbTitle', __('messages.dashboard'))

    <h5 class="mb-3">{{ __('messages.welcome_back') }}, {{ auth()->user()?->name }}!</h5>

    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('messages.total_companies') }}</p>
                            <h4 class="my-1 text-info">{{ $totalCompanies }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('messages.total_branches') }}</p>
                            <h4 class="my-1 text-warning">{{ $totalBranches }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                            <i class="bi bi-shop-window"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('messages.total_users') }}</p>
                            <h4 class="my-1 text-success">{{ $totalUsers }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Phase 1 Foundation</h6>
            <p class="text-muted mb-0">
                This dashboard scaffold confirms the layout, auth, RBAC tables, Livewire wiring, KH/EN switcher,
                SweetAlert2, PHPFlasher, flatpickr, Tom Select, and Bootstrap 5 pagination are ready.
                CRUD modules will be delivered in subsequent phases.
            </p>
        </div>
    </div>
</div>
