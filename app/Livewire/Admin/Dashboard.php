<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.admin_layout')]
class Dashboard extends Component
{
    public int $totalCompanies = 0;

    public int $totalBranches = 0;

    public int $totalUsers = 0;

    public function mount(): void
    {
        $this->totalCompanies = Company::count();
        $this->totalBranches = Branch::count();
        $this->totalUsers = User::count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
