<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $branch = Branch::where('is_main', true)->first() ?? Branch::first();

        $super = User::updateOrCreate(
            ['email' => 'admin@jewelry.local'],
            [
                'company_id' => $company?->id,
                'branch_id' => $branch?->id,
                'name' => 'Super Admin',
                'phone' => '000-000-9999',
                'password' => 'password',
                'user_type' => 'super_admin',
                'status' => 'active',
            ]
        );

        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $super->assignRole($adminRole);
        }
    }
}
