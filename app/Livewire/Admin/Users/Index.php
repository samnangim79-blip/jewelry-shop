<?php

namespace App\Livewire\Admin\Users;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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

    #[Url(as: 'type', except: '')]
    public string $typeFilter = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $company_id = null;

    public ?int $branch_id = null;

    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public string $password = '';

    public string $password_confirmation = '';

    public string $user_type = 'cashier';

    public string $status = 'active';

    public array $role_ids = [];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        Gate::authorize('permission', 'users.create');

        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        Gate::authorize('permission', 'users.update');

        $user = User::with('roles')->findOrFail($id);
        $this->editingId = $user->id;
        $this->company_id = $user->company_id;
        $this->branch_id = $user->branch_id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->user_type = $user->user_type;
        $this->status = $user->status;
        $this->role_ids = $user->roles->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'phone' => ['nullable', 'string', 'max:50'],
            'user_type' => ['required', 'in:super_admin,admin,manager,cashier,stock,accountant,goldsmith,auditor'],
            'status' => ['required', 'in:active,inactive,blocked'],
            'role_ids' => ['array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];

        if ($this->editingId === null) {
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        } elseif ($this->password !== '') {
            $rules['password'] = ['min:6', 'confirmed'];
        }

        $data = $this->validate($rules);

        $payload = [
            'company_id' => $data['company_id'] ?: null,
            'branch_id' => $data['branch_id'] ?: null,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?: null,
            'user_type' => $data['user_type'],
            'status' => $data['status'],
        ];

        if ($this->editingId) {
            Gate::authorize('permission', 'users.update');
            $user = User::findOrFail($this->editingId);
            if ($this->password !== '') {
                $payload['password'] = Hash::make($this->password);
            }
        } else {
            Gate::authorize('permission', 'users.create');
            $user = new User;
            $payload['password'] = Hash::make($this->password);
        }

        $user->fill($payload)->save();
        $user->roles()->sync($this->role_ids);

        flash()->success($this->editingId ? __('messages.updated_success') : __('messages.created_success'));

        $this->showModal = false;
        $this->resetForm();
    }

    #[On('delete-user')]
    public function delete(int $id): void
    {
        Gate::authorize('permission', 'users.delete');

        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            flash()->error(__('messages.unauthorized'));

            return;
        }

        $user->delete();

        flash()->success(__('messages.deleted_success'));
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->company_id = Company::query()->orderBy('id')->value('id');
        $this->branch_id = null;
        $this->name = '';
        $this->email = '';
        $this->phone = null;
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_type = 'cashier';
        $this->status = 'active';
        $this->role_ids = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::query()
            ->with(['company', 'branch', 'roles'])
            ->when($this->typeFilter, fn ($q) => $q->where('user_type', $this->typeFilter))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.users.index', [
            'users' => $users,
            'companies' => Company::orderBy('name')->get(['id', 'name']),
            'branches' => Branch::orderBy('name')->get(['id', 'name', 'company_id']),
            'roles' => Role::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
