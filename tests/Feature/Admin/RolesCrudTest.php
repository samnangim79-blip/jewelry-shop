<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Roles\Index;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RolesCrudTest extends TestCase
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
        $this->get(route('admin.roles.index'))->assertOk()->assertSeeLivewire(Index::class);
    }

    public function test_can_create_role_with_permissions(): void
    {
        $this->actingAsSuperAdmin();
        $permIds = Permission::query()->limit(3)->pluck('id')->toArray();

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('name', 'Test Role')
            ->set('slug', 'test-role')
            ->set('status', 'active')
            ->set('permission_ids', $permIds)
            ->call('save')
            ->assertHasNoErrors();

        $role = Role::where('slug', 'test-role')->first();
        $this->assertNotNull($role);
        $this->assertSame(3, $role->permissions()->count());
    }

    public function test_slug_auto_populates_on_name_change(): void
    {
        $this->actingAsSuperAdmin();

        $component = Livewire::test(Index::class)
            ->call('openCreate')
            ->set('name', 'Hello World');

        $this->assertSame('hello-world', $component->get('slug'));
    }

    public function test_role_in_use_cannot_be_deleted(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $role = Role::create(['name' => 'In Use', 'slug' => 'in-use', 'status' => 'active']);
        $admin->roles()->attach($role->id);

        Livewire::test(Index::class)->call('delete', $role->id);

        $this->assertDatabaseHas('roles', ['id' => $role->id, 'deleted_at' => null]);
    }
}
