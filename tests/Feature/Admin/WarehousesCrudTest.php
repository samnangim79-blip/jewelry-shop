<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Warehouses\Index;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WarehousesCrudTest extends TestCase
{
    use RefreshDatabase;

    private function setupContext(): array
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

        $company = Company::create(['name' => 'A', 'code' => 'A', 'currency' => 'USD', 'status' => 'active']);
        $branch = Branch::create(['company_id' => $company->id, 'name' => 'B', 'code' => 'B', 'status' => 'active']);

        return [$company, $branch];
    }

    public function test_route_renders(): void
    {
        $this->setupContext();
        $this->get(route('admin.warehouses.index'))->assertOk()->assertSeeLivewire(Index::class);
    }

    public function test_can_create_warehouse(): void
    {
        [$company, $branch] = $this->setupContext();

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('company_id', $company->id)
            ->set('branch_id', $branch->id)
            ->set('name', 'WH-1')
            ->set('code', 'WH1')
            ->set('warehouse_type', 'safe')
            ->set('status', 'active')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('warehouses', ['code' => 'WH1', 'warehouse_type' => 'safe']);
    }

    public function test_warehouse_type_must_be_valid(): void
    {
        [$company, $branch] = $this->setupContext();

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('company_id', $company->id)
            ->set('branch_id', $branch->id)
            ->set('name', 'WH-1')
            ->set('code', 'WH1')
            ->set('warehouse_type', 'invalid_type')
            ->call('save')
            ->assertHasErrors(['warehouse_type']);
    }

    public function test_can_delete_warehouse(): void
    {
        [$company, $branch] = $this->setupContext();
        $w = Warehouse::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'name' => 'WH',
            'code' => 'WH',
            'warehouse_type' => 'storage',
            'status' => 'active',
        ]);

        Livewire::test(Index::class)->call('delete', $w->id);

        $this->assertSoftDeleted('warehouses', ['id' => $w->id]);
    }
}
