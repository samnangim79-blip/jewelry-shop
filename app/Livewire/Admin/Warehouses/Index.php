<?php

namespace App\Livewire\Admin\Warehouses;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.admin_layout')]
class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'branch', except: '')]
    public string $branchFilter = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $company_id = null;

    public ?int $branch_id = null;

    public string $name = '';

    public string $code = '';

    public string $warehouse_type = 'storage';

    public string $status = 'active';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBranchFilter(): void
    {
        $this->resetPage();
    }

    public function updatedCompanyId(): void
    {
        $this->branch_id = null;
    }

    public function openCreate(): void
    {
        Gate::authorize('permission', 'warehouses.create');

        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        Gate::authorize('permission', 'warehouses.update');

        $warehouse = Warehouse::findOrFail($id);
        $this->editingId = $warehouse->id;
        $this->company_id = $warehouse->company_id;
        $this->branch_id = $warehouse->branch_id;
        $this->name = $warehouse->name;
        $this->code = $warehouse->code;
        $this->warehouse_type = $warehouse->warehouse_type;
        $this->status = $warehouse->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('warehouses', 'code')
                    ->where(fn ($q) => $q->where('branch_id', $this->branch_id))
                    ->ignore($this->editingId),
            ],
            'warehouse_type' => ['required', 'in:safe,display,storage,repair'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($this->editingId) {
            Gate::authorize('permission', 'warehouses.update');
            $warehouse = Warehouse::findOrFail($this->editingId);
        } else {
            Gate::authorize('permission', 'warehouses.create');
            $warehouse = new Warehouse;
        }

        $warehouse->fill($data)->save();

        flash()->success($this->editingId ? __('messages.updated_success') : __('messages.created_success'));

        $this->showModal = false;
        $this->resetForm();
    }

    #[On('delete-warehouse')]
    public function delete(int $id): void
    {
        Gate::authorize('permission', 'warehouses.delete');

        Warehouse::findOrFail($id)->delete();

        flash()->success(__('messages.deleted_success'));
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->company_id = Company::query()->orderBy('id')->value('id');
        $this->branch_id = null;
        $this->name = '';
        $this->code = '';
        $this->warehouse_type = 'storage';
        $this->status = 'active';
        $this->resetErrorBag();
    }

    public function render()
    {
        $warehouses = Warehouse::query()
            ->with(['company', 'branch'])
            ->when($this->branchFilter, fn ($q) => $q->where('branch_id', $this->branchFilter))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('code', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.warehouses.index', [
            'warehouses' => $warehouses,
            'companies' => Company::orderBy('name')->get(['id', 'name']),
            'branches' => Branch::query()
                ->when($this->company_id, fn ($q) => $q->where('company_id', $this->company_id))
                ->orderBy('name')
                ->get(['id', 'name', 'company_id']),
            'allBranches' => Branch::orderBy('name')->get(['id', 'name', 'company_id']),
        ]);
    }
}
