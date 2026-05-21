<div>
    @section('pageTitle', __('messages.manage_permissions'))
    @section('pageBreadcrumb', __('messages.permissions'))
    @section('pageBreadcrumbTitle', __('messages.user_management'))

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <h5 class="mb-0 me-auto">{{ __('messages.manage_permissions') }}</h5>

                <select wire:model.live="moduleFilter" class="form-select" style="max-width: 240px;">
                    <option value="">{{ __('messages.all') }} - {{ __('messages.module') }}</option>
                    @foreach ($modules as $m)
                        <option value="{{ $m }}">{{ str_replace('_', ' ', $m) }}</option>
                    @endforeach
                </select>

                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                    <input wire:model.live.debounce.300ms="search" type="search" class="form-control" placeholder="{{ __('messages.search') }}...">
                </div>
            </div>

            <div class="alert alert-info py-2 mb-3">
                <i class="bi bi-info-circle me-1"></i>
                {{ __('messages.permissions') }} —
                <span class="text-muted">read-only system permissions; assign them to roles in the {{ __('messages.roles') }} module.</span>
            </div>

            @forelse ($permissionsGrouped as $module => $perms)
                <h6 class="text-uppercase mt-3 mb-2">{{ str_replace('_', ' ', $module) }}</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-2">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.slug') }}</th>
                                <th>{{ __('messages.description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perms as $p)
                                <tr wire:key="perm-{{ $p->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td><code>{{ $p->slug }}</code></td>
                                    <td class="text-muted">{{ $p->description ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="text-center text-muted py-4">{{ __('messages.no_data') }}</div>
            @endforelse
        </div>
    </div>
</div>
