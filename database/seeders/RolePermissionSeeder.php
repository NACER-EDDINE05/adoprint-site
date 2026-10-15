<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')) {
            $this->command->info('Tables `roles`/`permissions` inexistantes, RolePermissionSeeder ignoré.');
            return;
        }

        $roles = ['super-admin', 'admin', 'operator', 'client'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // exemples de permissions (à adapter en dashboard)
        $perms = ['manage-products','manage-orders','view-reports'];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // assign permissions to roles
        $admin = Role::where('name','admin')->first();
        if ($admin) { $admin->givePermissionTo($perms); }
    }
}
