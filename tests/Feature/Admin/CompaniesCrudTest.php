<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Companies\Index;
use App\Models\Company;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompaniesCrudTest extends TestCase
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
        $this->get(route('admin.companies.index'))->assertOk()->assertSeeLivewire(Index::class);
    }

    public function test_index_loads_with_companies(): void
    {
        $this->actingAsSuperAdmin();
        Company::create(['name' => 'Foo', 'code' => 'FOO', 'currency' => 'USD', 'status' => 'active']);

        Livewire::test(Index::class)
            ->assertOk()
            ->assertSee('Foo')
            ->assertSee('FOO');
    }

    public function test_can_create_company(): void
    {
        $this->actingAsSuperAdmin();

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('name', 'Acme')
            ->set('code', 'ACME')
            ->set('currency', 'USD')
            ->set('status', 'active')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('companies', ['name' => 'Acme', 'code' => 'ACME']);
    }

    public function test_create_requires_name_and_code(): void
    {
        $this->actingAsSuperAdmin();

        Livewire::test(Index::class)
            ->call('openCreate')
            ->call('save')
            ->assertHasErrors(['name', 'code']);
    }

    public function test_can_edit_company(): void
    {
        $this->actingAsSuperAdmin();
        $company = Company::create(['name' => 'Original', 'code' => 'ORG', 'currency' => 'USD', 'status' => 'active']);

        Livewire::test(Index::class)
            ->call('openEdit', $company->id)
            ->assertSet('name', 'Original')
            ->set('name', 'Updated')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Updated', $company->fresh()->name);
    }

    public function test_can_delete_company(): void
    {
        $this->actingAsSuperAdmin();
        $company = Company::create(['name' => 'Delete', 'code' => 'DEL', 'currency' => 'USD', 'status' => 'active']);

        Livewire::test(Index::class)
            ->call('delete', $company->id);

        $this->assertSoftDeleted('companies', ['id' => $company->id]);
    }

    public function test_unauthorized_user_cannot_view(): void
    {
        $this->seed(PermissionSeeder::class);

        $user = User::create([
            'name' => 'Nobody',
            'email' => 'nobody@test.local',
            'password' => bcrypt('secret123'),
            'user_type' => 'cashier',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $this->get(route('admin.companies.index'))
            ->assertForbidden();
    }
}
