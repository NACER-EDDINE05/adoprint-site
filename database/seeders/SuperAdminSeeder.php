<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('users')) {
            $this->command->info('Table `users` inexistante, SuperAdminSeeder ignoré.');
            return;
        }

        $role = Role::firstOrCreate(['name' => 'super-admin']);

        $userModel = config('auth.providers.users.model', \App\Models\User::class);

        $user = $userModel::firstOrCreate(
            ['email' => 'admin@imprimerie.local'],
            [
                'name' => 'Admin Demo',
                'password' => Hash::make('password123'),
            ]
        );

        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role);
        }
    }
}
