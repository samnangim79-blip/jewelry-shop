<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Users\Index;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UsersCrudTest extends TestCase
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
        $this->get(route('admin.users.index'))->assertOk()->assertSeeLivewire(Index::class);
    }

    public function test_can_create_user_with_role(): void
    {
        $this->actingAsSuperAdmin();
        $role = Role::first();

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('name', 'New User')
            ->set('email', 'newuser@test.local')
            ->set('password', 'secret123')
            ->set('password_confirmation', 'secret123')
            ->set('user_type', 'cashier')
            ->set('status', 'active')
            ->set('role_ids', [$role->id])
            ->call('save')
            ->assertHasNoErrors();

        $user = User::where('email', 'newuser@test.local')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertTrue($user->roles->contains($role->id));
    }

    public function test_password_required_on_create(): void
    {
        $this->actingAsSuperAdmin();

        Livewire::test(Index::class)
            ->call('openCreate')
            ->set('name', 'New User')
            ->set('email', 'newuser@test.local')
            ->set('user_type', 'cashier')
            ->set('status', 'active')
            ->call('save')
            ->assertHasErrors(['password']);
    }

    public function test_password_optional_on_update(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $target = User::create([
            'name' => 'Target',
            'email' => 'target@test.local',
            'password' => bcrypt('original'),
            'user_type' => 'cashier',
            'status' => 'active',
        ]);

        Livewire::test(Index::class)
            ->call('openEdit', $target->id)
            ->set('name', 'Updated Name')
            ->call('save')
            ->assertHasNoErrors();

        $target->refresh();
        $this->assertSame('Updated Name', $target->name);
        $this->assertTrue(Hash::check('original', $target->password));
    }

    public function test_cannot_delete_self(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::test(Index::class)->call('delete', $admin->id);

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'deleted_at' => null]);
    }
}
