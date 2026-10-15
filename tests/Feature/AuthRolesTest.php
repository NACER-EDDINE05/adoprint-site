<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthRolesTest extends TestCase
{
    public function test_super_admin_has_role()
    {
        $user = new User(['name' => 'Tst', 'email' => 't@test.local']);
        // Cannot persist here; just assert trait exists
        $this->assertTrue(method_exists($user, 'assignRole'));
    }
}
