<?php

namespace App\Livewire\Admin\Branches;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
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

    #[Url(as: 'company', except: '')]
    public string $companyFilter = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $company_id = null;

    public string $name = '';

    public string $code = '';

    public ?string $phone = null;

    public ?string $email = null;

    public ?string $address = null;

    public ?int $manager_id = null;

    public bool $is_main = false;

    public string $status = 'active';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        Gate::authorize('permission', 'branches.create');

        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        Gate::authorize('permission', 'branches.update');

        $branch = Branch::findOrFail($id);
        $this->editingId = $branch->id;
        $this->company_id = $branch->company_id;
        $this->name = $branch->name;
        $this->code = $branch->code;
        $this->phone = $branch->phone;
        $this->email = $branch->email;
        $this->address = $branch->address;
        $this->manager_id = $branch->manager_id;
        $this->is_main = (bool) $branch->is_main;
        $this->status = $branch->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('branches', 'code')
                    ->where(fn ($q) => $q->where('company_id', $this->company_id))
                    ->ignore($this->editingId),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'is_main' => ['boolean'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($this->editingId) {
            Gate::authorize('permission', 'branches.update');
            $branch = Branch::findOrFail($this->editingId);
        } else {
            Gate::authorize('permission', 'branches.create');
            $branch = new Branch;
        }

        $branch->fill($data)->save();

        flash()->success($this->editingId ? __('messages.updated_success') : __('messages.created_success'));

        $this->showModal = false;
        $this->resetForm();
    }

    #[On('delete-branch')]
    public function delete(int $id): void
    {
        Gate::authorize('permission', 'branches.delete');

        Branch::findOrFail($id)->delete();

        flash()->success(__('messages.deleted_success'));
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->company_id = Company::query()->orderBy('id')->value('id');
        $this->name = '';
        $this->code = '';
        $this->phone = null;
        $this->email = null;
        $this->address = null;
        $this->manager_id = null;
        $this->is_main = false;
        $this->status = 'active';
        $this->resetErrorBag();
    }

    public function render()
    {
        $branches = Branch::query()
            ->with(['company', 'manager'])
            ->when($this->companyFilter, fn ($q) => $q->where('company_id', $this->companyFilter))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('code', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.branches.index', [
            'branches' => $branches,
            'companies' => Company::orderBy('name')->get(['id', 'name']),
            'users' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
