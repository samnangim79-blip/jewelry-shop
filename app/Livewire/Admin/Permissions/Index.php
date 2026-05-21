<?php

namespace App\Livewire\Admin\Permissions;

use App\Models\Permission;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('admin.layouts.admin_layout')]
class Index extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'module', except: '')]
    public string $moduleFilter = '';

    public function render()
    {
        $permissions = Permission::query()
            ->when($this->moduleFilter, fn ($q) => $q->where('module', $this->moduleFilter))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('slug', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('module')
            ->orderBy('slug')
            ->get()
            ->groupBy('module');

        return view('livewire.admin.permissions.index', [
            'permissionsGrouped' => $permissions,
            'modules' => Permission::orderBy('module')->distinct()->pluck('module'),
        ]);
    }
}
