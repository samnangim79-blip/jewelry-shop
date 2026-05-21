<div>
    @section('pageTitle', __('messages.manage_branches'))
    @section('pageBreadcrumb', __('messages.branches'))
    @section('pageBreadcrumbTitle', __('messages.master_data'))

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <h5 class="mb-0 me-auto">{{ __('messages.manage_branches') }}</h5>

                <select wire:model.live="companyFilter" class="form-select" style="max-width: 220px;">
                    <option value="">{{ __('messages.all') }} - {{ __('messages.companies') }}</option>
                    @foreach ($companies as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>

                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                    <input wire:model.live.debounce.300ms="search" type="search" class="form-control" placeholder="{{ __('messages.search') }}...">
                </div>

                @can('permission', 'branches.create')
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
                            <th>{{ __('messages.company') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.manager') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th style="width: 100px">{{ __('messages.is_main') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-end" style="width: 140px">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branches as $branch)
                            <tr wire:key="branch-{{ $branch->id }}">
                                <td>{{ $loop->iteration + ($branches->currentPage() - 1) * $branches->perPage() }}</td>
                                <td>{{ $branch->company?->name ?? '-' }}</td>
                                <td>{{ $branch->name }}</td>
                                <td><code>{{ $branch->code }}</code></td>
                                <td>{{ $branch->manager?->name ?? '-' }}</td>
                                <td>{{ $branch->phone ?? '-' }}</td>
                                <td>
                                    @if ($branch->is_main)
                                        <span class="badge bg-info">{{ __('messages.yes') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($branch->status === 'active')
                                        <span class="badge bg-success">{{ __('messages.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('permission', 'branches.update')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $branch->id }})"><i class="bi bi-pencil"></i></button>
                                    @endcan
                                    @can('permission', 'branches.delete')
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="window.dispatchEvent(new CustomEvent('confirm-delete', { detail: [{ method: 'delete-branch', params: { id: {{ $branch->id }} } }] }))">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $branches->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <x-modal name="showModal" :title="$editingId ? __('messages.edit_record') . ' - ' . __('messages.branch') : __('messages.create_record') . ' - ' . __('messages.branch')" size="modal-lg">
            <form wire:submit="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.company') }} <span class="text-danger">*</span></label>
                            <select wire:model="company_id" class="form-select @error('company_id') is-invalid @enderror">
                                <option value="">{{ __('messages.select_placeholder') }}</option>
                                @foreach ($companies as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.code') }} <span class="text-danger">*</span></label>
                            <input wire:model="code" type="text" class="form-control @error('code') is-invalid @enderror">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.manager') }}</label>
                            <select wire:model="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
                                <option value="">{{ __('messages.select_placeholder') }}</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            @error('manager_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.phone') }}</label>
                            <input wire:model="phone" type="text" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.email') }}</label>
                            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                            <select wire:model="status" class="form-select">
                                <option value="active">{{ __('messages.active') }}</option>
                                <option value="inactive">{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">{{ __('messages.is_main') }}</label>
                            <div class="form-check form-switch">
                                <input wire:model="is_main" class="form-check-input" type="checkbox" id="branch_is_main">
                                <label class="form-check-label" for="branch_is_main">{{ __('messages.yes') }}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.address') }}</label>
                            <textarea wire:model="address" rows="2" class="form-control"></textarea>
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
