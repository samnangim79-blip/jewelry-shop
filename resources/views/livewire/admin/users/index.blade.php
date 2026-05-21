<div>
    @section('pageTitle', __('messages.manage_users'))
    @section('pageBreadcrumb', __('messages.users'))
    @section('pageBreadcrumbTitle', __('messages.user_management'))

    @php
        $userTypes = ['super_admin', 'admin', 'manager', 'cashier', 'stock', 'accountant', 'goldsmith', 'auditor'];
    @endphp

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <h5 class="mb-0 me-auto">{{ __('messages.manage_users') }}</h5>

                <select wire:model.live="typeFilter" class="form-select" style="max-width: 200px;">
                    <option value="">{{ __('messages.all') }} - {{ __('messages.user_type') }}</option>
                    @foreach ($userTypes as $t)
                        <option value="{{ $t }}">{{ __('messages.ut_' . $t) }}</option>
                    @endforeach
                </select>

                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                    <input wire:model.live.debounce.300ms="search" type="search" class="form-control" placeholder="{{ __('messages.search') }}...">
                </div>

                @can('permission', 'users.create')
                    <button type="button" class="btn btn-primary" wire:click="openCreate">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('messages.create') }}
                    </button>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px">#</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.user_type') }}</th>
                            <th>{{ __('messages.company') }}</th>
                            <th>{{ __('messages.branch') }}</th>
                            <th>{{ __('messages.roles') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-end" style="width: 140px">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                            <tr wire:key="user-{{ $u->id }}">
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td><span class="badge bg-light text-dark">{{ __('messages.ut_' . $u->user_type) }}</span></td>
                                <td>{{ $u->company?->name ?? '-' }}</td>
                                <td>{{ $u->branch?->name ?? '-' }}</td>
                                <td>
                                    @foreach ($u->roles as $r)
                                        <span class="badge bg-info-subtle text-info-emphasis me-1">{{ $r->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($u->status === 'active')
                                        <span class="badge bg-success">{{ __('messages.active') }}</span>
                                    @elseif ($u->status === 'blocked')
                                        <span class="badge bg-danger">{{ __('messages.blocked') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('permission', 'users.update')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $u->id }})"><i class="bi bi-pencil"></i></button>
                                    @endcan
                                    @can('permission', 'users.delete')
                                        @if ($u->id !== auth()->id())
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="window.dispatchEvent(new CustomEvent('confirm-delete', { detail: [{ method: 'delete-user', params: { id: {{ $u->id }} } }] }))">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $users->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <x-modal name="showModal" :title="$editingId ? __('messages.edit_record') . ' - ' . __('messages.users') : __('messages.create_record') . ' - ' . __('messages.users')" size="modal-lg">
            <form wire:submit="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.phone') }}</label>
                            <input wire:model="phone" type="text" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.user_type') }} <span class="text-danger">*</span></label>
                            <select wire:model="user_type" class="form-select">
                                @foreach ($userTypes as $t)
                                    <option value="{{ $t }}">{{ __('messages.ut_' . $t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.company') }}</label>
                            <select wire:model="company_id" class="form-select">
                                <option value="">{{ __('messages.no_company') }}</option>
                                @foreach ($companies as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.branch') }}</label>
                            <select wire:model="branch_id" class="form-select">
                                <option value="">{{ __('messages.no_branch') }}</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.password') }} {{ $editingId ? '' : '*' }}</label>
                            <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="{{ $editingId ? __('messages.leave_blank_to_keep') : '' }}">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.password_confirmation') }}</label>
                            <input wire:model="password_confirmation" type="password" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                            <select wire:model="status" class="form-select">
                                <option value="active">{{ __('messages.active') }}</option>
                                <option value="inactive">{{ __('messages.inactive') }}</option>
                                <option value="blocked">{{ __('messages.blocked') }}</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">{{ __('messages.roles') }}</label>
                            <div class="d-flex flex-wrap gap-3 border rounded p-2 @error('role_ids') border-danger @enderror">
                                @forelse ($roles as $r)
                                    <div class="form-check">
                                        <input wire:model="role_ids" class="form-check-input" type="checkbox" value="{{ $r->id }}" id="role-{{ $r->id }}">
                                        <label class="form-check-label" for="role-{{ $r->id }}">{{ $r->name }}</label>
                                    </div>
                                @empty
                                    <span class="text-muted">{{ __('messages.no_data') }}</span>
                                @endforelse
                            </div>
                            @error('role_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" @click="open = false">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save"><i class="bi bi-check2 me-1"></i> {{ __('messages.save') }}</span>
                        <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-1"></span></span>
                    </button>
                </div>
            </form>
        </x-modal>
    @endif
</div>
