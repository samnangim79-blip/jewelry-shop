<div>
    @section('pageTitle', __('messages.manage_warehouses'))
    @section('pageBreadcrumb', __('messages.warehouses'))
    @section('pageBreadcrumbTitle', __('messages.master_data'))

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <h5 class="mb-0 me-auto">{{ __('messages.manage_warehouses') }}</h5>

                <select wire:model.live="branchFilter" class="form-select" style="max-width: 220px;">
                    <option value="">{{ __('messages.all') }} - {{ __('messages.branches') }}</option>
                    @foreach ($allBranches as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>

                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                    <input wire:model.live.debounce.300ms="search" type="search" class="form-control" placeholder="{{ __('messages.search') }}...">
                </div>

                @can('permission', 'warehouses.create')
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
                            <th>{{ __('messages.branch') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.warehouse_type') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-end" style="width: 140px">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warehouses as $warehouse)
                            <tr wire:key="warehouse-{{ $warehouse->id }}">
                                <td>{{ $loop->iteration + ($warehouses->currentPage() - 1) * $warehouses->perPage() }}</td>
                                <td>{{ $warehouse->company?->name ?? '-' }}</td>
                                <td>{{ $warehouse->branch?->name ?? '-' }}</td>
                                <td>{{ $warehouse->name }}</td>
                                <td><code>{{ $warehouse->code }}</code></td>
                                <td><span class="badge bg-light text-dark">{{ __('messages.wt_' . $warehouse->warehouse_type) }}</span></td>
                                <td>
                                    @if ($warehouse->status === 'active')
                                        <span class="badge bg-success">{{ __('messages.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('permission', 'warehouses.update')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $warehouse->id }})"><i class="bi bi-pencil"></i></button>
                                    @endcan
                                    @can('permission', 'warehouses.delete')
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="window.dispatchEvent(new CustomEvent('confirm-delete', { detail: [{ method: 'delete-warehouse', params: { id: {{ $warehouse->id }} } }] }))">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $warehouses->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <x-modal name="showModal" :title="$editingId ? __('messages.edit_record') . ' - ' . __('messages.warehouse') : __('messages.create_record') . ' - ' . __('messages.warehouse')">
            <form wire:submit="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.company') }} <span class="text-danger">*</span></label>
                            <select wire:model.live="company_id" class="form-select @error('company_id') is-invalid @enderror">
                                <option value="">{{ __('messages.select_placeholder') }}</option>
                                @foreach ($companies as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.branch') }} <span class="text-danger">*</span></label>
                            <select wire:model="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                                <option value="">{{ __('messages.select_placeholder') }}</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <label class="form-label">{{ __('messages.warehouse_type') }} <span class="text-danger">*</span></label>
                            <select wire:model="warehouse_type" class="form-select">
                                <option value="safe">{{ __('messages.wt_safe') }}</option>
                                <option value="display">{{ __('messages.wt_display') }}</option>
                                <option value="storage">{{ __('messages.wt_storage') }}</option>
                                <option value="repair">{{ __('messages.wt_repair') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                            <select wire:model="status" class="form-select">
                                <option value="active">{{ __('messages.active') }}</option>
                                <option value="inactive">{{ __('messages.inactive') }}</option>
                            </select>
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
