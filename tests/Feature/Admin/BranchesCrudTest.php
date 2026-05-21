<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Branches\Index;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BranchesCrudTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsSuperAdmin(): User
    {
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $user = User::create([
            'name' => 'Super',
            'email' => 'super@test.local',
            'password' => bcrypt('secret123'),
            'user_type' => 'super_admin',
            'status' => 'active',
        ]);
        $this->actingAs($user);

        return $user;
    }

    public function test_route_renders(): void
    {
        $this->actingAsSuperAdmin();
        $this->get(route('admin.branches.index'))->assertOk()->assertSeeLivewire(Index::class);
    }

    public function test_can_create_branch(): void
    {
        $this->actingAsSuperAdmin();
        $company = Company::create(['name' => 'A', 'code' => 'A', 'currency' => 'USD', 'status' => 'active']);

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('company_id', $company->id)
            ->set('name', 'Main')
            ->set('code', 'MAIN')
            ->set('is_main', true)
            ->set('status', 'active')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('branches', ['name' => 'Main', 'code' => 'MAIN', 'is_main' => true]);
    }

    public function test_branch_code_is_unique_within_company(): void
    {
        $this->actingAsSuperAdmin();
        $company = Company::create(['name' => 'A', 'code' => 'A', 'currency' => 'USD', 'status' => 'active']);
        Branch::create(['company_id' => $company->id, 'name' => 'Main', 'code' => 'MAIN', 'status' => 'active']);

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('company_id', $company->id)
            ->set('name', 'Other')
            ->set('code', 'MAIN')
            ->call('save')
            ->assertHasErrors(['code']);
    }

    public function test_can_delete_branch(): void
    {
        $this->actingAsSuperAdmin();
        $company = Company::create(['name' => 'A', 'code' => 'A', 'currency' => 'USD', 'status' => 'active']);
        $branch = Branch::create(['company_id' => $company->id, 'name' => 'Del', 'code' => 'DEL', 'status' => 'active']);

        Livewire::test(Index::class)->call('delete', $branch->id);

        $this->assertSoftDeleted('branches', ['id' => $branch->id]);
    }
}
