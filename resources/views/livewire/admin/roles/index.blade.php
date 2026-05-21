<div>
    @section('pageTitle', __('messages.manage_roles'))
    @section('pageBreadcrumb', __('messages.roles'))
    @section('pageBreadcrumbTitle', __('messages.user_management'))

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <h5 class="mb-0 me-auto">{{ __('messages.manage_roles') }}</h5>

                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                    <input wire:model.live.debounce.300ms="search" type="search" class="form-control" placeholder="{{ __('messages.search') }}...">
                </div>

                @can('permission', 'roles.create')
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
                            <th>{{ __('messages.slug') }}</th>
                            <th>{{ __('messages.company') }}</th>
                            <th>{{ __('messages.users') }}</th>
                            <th>{{ __('messages.permissions') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-end" style="width: 140px">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $r)
                            <tr wire:key="role-{{ $r->id }}">
                                <td>{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>
                                <td>{{ $r->name }}</td>
                                <td><code>{{ $r->slug }}</code></td>
                                <td>{{ $r->company?->name ?? __('messages.system') }}</td>
                                <td>{{ $r->users_count }}</td>
                                <td>{{ $r->permissions->count() }}</td>
                                <td>
                                    @if ($r->status === 'active')
                                        <span class="badge bg-success">{{ __('messages.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('permission', 'roles.update')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $r->id }})"><i class="bi bi-pencil"></i></button>
                                    @endcan
                                    @can('permission', 'roles.delete')
                                        @if ($r->users_count === 0)
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="window.dispatchEvent(new CustomEvent('confirm-delete', { detail: [{ method: 'delete-role', params: { id: {{ $r->id }} } }] }))">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $roles->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <x-modal name="showModal" :title="$editingId ? __('messages.edit_record') . ' - ' . __('messages.role') : __('messages.create_record') . ' - ' . __('messages.role')" size="modal-xl">
            <form wire:submit="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input wire:model.live.debounce.500ms="name" type="text" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.slug') }} <span class="text-danger">*</span></label>
                            <input wire:model="slug" type="text" class="form-control @error('slug') is-invalid @enderror">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.company') }}</label>
                            <select wire:model="company_id" class="form-select">
                                <option value="">{{ __('messages.system') }}</option>
                                @foreach ($companies as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">{{ __('messages.description') }}</label>
                            <textarea wire:model="description" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                            <select wire:model="status" class="form-select">
                                <option value="active">{{ __('messages.active') }}</option>
                                <option value="inactive">{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.permissions') }}</label>
                            <div class="border rounded p-3 @error('permission_ids') border-danger @enderror">
                                @foreach ($permissionsGrouped as $module => $perms)
                                    <div class="mb-3">
                                        <h6 class="text-uppercase fs-6 mb-2">{{ str_replace('_', ' ', $module) }}</h6>
                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach ($perms as $p)
                                                <div class="form-check">
                                                    <input wire:model="permission_ids" class="form-check-input" type="checkbox" value="{{ $p->id }}" id="perm-{{ $p->id }}">
                                                    <label class="form-check-label" for="perm-{{ $p->id }}">{{ $p->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permission_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
