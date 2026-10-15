<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoClientsSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('users')) {
            $this->command->info('Table `users` inexistante, DemoClientsSeeder ignoré.');
            return;
        }

        $users = [
            ['name' => 'Client Un', 'email' => 'client1@demo.local', 'password' => Hash::make('clientpass')],
            ['name' => 'Client Deux', 'email' => 'client2@demo.local', 'password' => Hash::make('clientpass')],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(['email' => $u['email']], $u);
        }
    }
}
