<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('admin.layouts.admin_layout')]
class Index extends Component
{
    use WithFileUploads, WithPagination;

    protected string $paginationTheme = 'bootstrap';

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $code = '';

    public ?string $owner_name = null;

    public ?string $phone = null;

    public ?string $email = null;

    public ?string $website = null;

    public ?string $address = null;

    public ?string $tax_no = null;

    public string $currency = 'USD';

    public string $status = 'active';

    public $logo = null;

    public ?string $existingLogo = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        Gate::authorize('permission', 'companies.create');

        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        Gate::authorize('permission', 'companies.update');

        $company = Company::findOrFail($id);
        $this->editingId = $company->id;
        $this->name = $company->name;
        $this->code = $company->code;
        $this->owner_name = $company->owner_name;
        $this->phone = $company->phone;
        $this->email = $company->email;
        $this->website = $company->website;
        $this->address = $company->address;
        $this->tax_no = $company->tax_no;
        $this->currency = $company->currency ?? 'USD';
        $this->status = $company->status;
        $this->logo = null;
        $this->existingLogo = $company->logo;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('companies', 'code')->ignore($this->editingId)],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'tax_no' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
            'status' => ['required', 'in:active,inactive'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($this->editingId) {
            Gate::authorize('permission', 'companies.update');
            $company = Company::findOrFail($this->editingId);
        } else {
            Gate::authorize('permission', 'companies.create');
            $company = new Company;
        }

        if ($this->logo) {
            $data['logo'] = $this->logo->store('companies', 'public');
        } else {
            unset($data['logo']);
        }

        $company->fill($data)->save();

        flash()->success($this->editingId ? __('messages.updated_success') : __('messages.created_success'));

        $this->showModal = false;
        $this->resetForm();
    }

    #[On('delete-company')]
    public function delete(int $id): void
    {
        Gate::authorize('permission', 'companies.delete');

        $company = Company::findOrFail($id);
        $company->delete();

        flash()->success(__('messages.deleted_success'));
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->code = '';
        $this->owner_name = null;
        $this->phone = null;
        $this->email = null;
        $this->website = null;
        $this->address = null;
        $this->tax_no = null;
        $this->currency = 'USD';
        $this->status = 'active';
        $this->logo = null;
        $this->existingLogo = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $companies = Company::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('code', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('owner_name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.companies.index', [
            'companies' => $companies,
        ]);
    }
}
