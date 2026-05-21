<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
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

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $company_id = null;

    public string $name = '';

    public string $slug = '';

    public ?string $description = null;

    public string $status = 'active';

    public array $permission_ids = [];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedName(string $value): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreate(): void
    {
        Gate::authorize('permission', 'roles.create');

        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        Gate::authorize('permission', 'roles.update');

        $role = Role::with('permissions')->findOrFail($id);
        $this->editingId = $role->id;
        $this->company_id = $role->company_id;
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->description = $role->description;
        $this->status = $role->status;
        $this->permission_ids = $role->permissions->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required', 'string', 'max:100',
                Rule::unique('roles', 'slug')
                    ->where(fn ($q) => $q->where('company_id', $this->company_id))
                    ->ignore($this->editingId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive'],
            'permission_ids' => ['array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        if ($this->editingId) {
            Gate::authorize('permission', 'roles.update');
            $role = Role::findOrFail($this->editingId);
        } else {
            Gate::authorize('permission', 'roles.create');
            $role = new Role;
        }

        $role->fill([
            'company_id' => $data['company_id'] ?: null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?: null,
            'status' => $data['status'],
        ])->save();

        if (auth()->user()?->hasPermission('permissions.assign') || auth()->user()?->isSuperAdmin()) {
            $role->permissions()->sync($this->permission_ids);
        }

        flash()->success($this->editingId ? __('messages.updated_success') : __('messages.created_success'));

        $this->showModal = false;
        $this->resetForm();
    }

    #[On('delete-role')]
    public function delete(int $id): void
    {
        Gate::authorize('permission', 'roles.delete');

        $role = Role::findOrFail($id);
        if ($role->users()->exists()) {
            flash()->error(__('messages.unauthorized'));

            return;
        }

        $role->delete();

        flash()->success(__('messages.deleted_success'));
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->company_id = Company::query()->orderBy('id')->value('id');
        $this->name = '';
        $this->slug = '';
        $this->description = null;
        $this->status = 'active';
        $this->permission_ids = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $roles = Role::query()
            ->with(['company', 'permissions'])
            ->withCount('users')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('slug', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
            'companies' => Company::orderBy('name')->get(['id', 'name']),
            'permissionsGrouped' => Permission::orderBy('module')->orderBy('slug')->get()->groupBy('module'),
        ]);
    }
}
