<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Permissions\Index;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PermissionsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_renders(): void
    {
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $user = User::create([
            'name' => 'Super',
            'email' => 'route@test.local',
            'password' => bcrypt('secret123'),
            'user_type' => 'super_admin',
            'status' => 'active',
        ]);
        $this->actingAs($user);
        $this->get(route('admin.permissions.index'))->assertOk()->assertSeeLivewire(Index::class);
    }

    public function test_index_lists_permissions_grouped_by_module(): void
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

        Livewire::test(Index::class)
            ->assertOk()
            ->assertSee('companies')
            ->assertSee('roles')
            ->assertSee('users');
    }

    public function test_module_filter_narrows_results(): void
    {
        $this->seed(PermissionSeeder::class);

        $user = User::create([
            'name' => 'Super',
            'email' => 'super@test.local',
            'password' => bcrypt('secret123'),
            'user_type' => 'super_admin',
            'status' => 'active',
        ]);
        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('moduleFilter', 'sales')
            ->assertSee('sales.view')
            ->assertDontSee('companies.view');
    }
}
