<div>
    @section('pageTitle', __('messages.manage_companies'))
    @section('pageBreadcrumb', __('messages.companies'))
    @section('pageBreadcrumbTitle', __('messages.master_data'))

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <h5 class="mb-0 me-auto">{{ __('messages.manage_companies') }}</h5>

                <div class="input-group" style="max-width: 280px;">
                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="search"
                        class="form-control"
                        placeholder="{{ __('messages.search') }}..."
                    />
                </div>

                @can('permission', 'companies.create')
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
                            <th style="width: 60px">{{ __('messages.logo') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.owner') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th>{{ __('messages.currency') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-end" style="width: 140px">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                            <tr wire:key="company-{{ $company->id }}">
                                <td>{{ $loop->iteration + ($companies->currentPage() - 1) * $companies->perPage() }}</td>
                                <td>
                                    @if ($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" class="rounded" width="36" height="36" alt="">
                                    @else
                                        <span class="text-muted"><i class="bi bi-building"></i></span>
                                    @endif
                                </td>
                                <td>{{ $company->name }}</td>
                                <td><code>{{ $company->code }}</code></td>
                                <td>{{ $company->owner_name ?? '-' }}</td>
                                <td>{{ $company->phone ?? '-' }}</td>
                                <td>{{ $company->currency }}</td>
                                <td>
                                    @if ($company->status === 'active')
                                        <span class="badge bg-success">{{ __('messages.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('permission', 'companies.update')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $company->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('permission', 'companies.delete')
                                        <button
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="window.dispatchEvent(new CustomEvent('confirm-delete', { detail: [{ method: 'delete-company', params: { id: {{ $company->id }} } }] }))"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $companies->links() }}
            </div>
        </div>
    </div>

    @if ($showModal)
        <x-modal name="showModal" :title="$editingId ? __('messages.edit_record') . ' - ' . __('messages.company') : __('messages.create_record') . ' - ' . __('messages.company')" size="modal-lg">
            <form wire:submit="save">
                <div class="modal-body">
                    <div class="row g-3">
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
                            <label class="form-label">{{ __('messages.owner') }}</label>
                            <input wire:model="owner_name" type="text" class="form-control @error('owner_name') is-invalid @enderror">
                            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <label class="form-label">{{ __('messages.website') }}</label>
                            <input wire:model="website" type="text" class="form-control @error('website') is-invalid @enderror">
                            @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.tax_no') }}</label>
                            <input wire:model="tax_no" type="text" class="form-control @error('tax_no') is-invalid @enderror">
                            @error('tax_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.currency') }} <span class="text-danger">*</span></label>
                            <select wire:model="currency" class="form-select @error('currency') is-invalid @enderror">
                                <option value="USD">USD</option>
                                <option value="KHR">KHR</option>
                                <option value="EUR">EUR</option>
                                <option value="THB">THB</option>
                            </select>
                            @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                            <select wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active">{{ __('messages.active') }}</option>
                                <option value="inactive">{{ __('messages.inactive') }}</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.logo') }}</label>
                            <input wire:model="logo" type="file" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($existingLogo && ! $logo)
                                <small class="text-muted">
                                    <img src="{{ asset('storage/' . $existingLogo) }}" width="40" height="40" class="rounded mt-1">
                                </small>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.address') }}</label>
                            <textarea wire:model="address" rows="2" class="form-control @error('address') is-invalid @enderror"></textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" @click="open = false">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save,logo">
                        <span wire:loading.remove wire:target="save"><i class="bi bi-check2 me-1"></i> {{ __('messages.save') }}</span>
                        <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-1"></span></span>
                    </button>
                </div>
            </form>
        </x-modal>
    @endif
</div>
