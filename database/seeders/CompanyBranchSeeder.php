<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanyBranchSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['code' => 'JWL-001'],
            [
                'name' => 'Jewelry Shop Main',
                'owner_name' => 'Demo Owner',
                'phone' => '000-000-0000',
                'email' => 'owner@jewelry.local',
                'address' => 'Phnom Penh, Cambodia',
                'status' => 'active',
            ]
        );

        Branch::firstOrCreate(
            ['company_id' => $company->id, 'code' => 'BR-MAIN'],
            [
                'name' => 'Main Branch',
                'phone' => '000-000-0001',
                'email' => 'main@jewelry.local',
                'address' => 'Phnom Penh, Cambodia',
                'is_main' => true,
                'status' => 'active',
            ]
        );
    }
}
