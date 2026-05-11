<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        try {
            Role::firstOrCreate(
                ['name' => 'Super Admin'],
                ['name' => 'Super Admin'],
            );

            /******* Admin *******/
            // Se não encontrar o registro, cadastra o registro no BD

            $rolesPermissions = [
                'Super Admin' => [
                    'dashboard',
                    'index-permission', 'show-permission', 'create-permission', 'edit-permission', 'destroy-permission',
                    'index-companie', 'show-companie', 'create-companie', 'edit-companie', 'destroy-companie',
                    'index-category', 'show-category', 'create-category', 'edit-category', 'destroy-category',
                    'index-promotion', 'show-promotion', 'create-promotion', 'edit-promotion', 'destroy-promotion',
                    'index-blog', 'show-blog', 'create-blog', 'edit-blog', 'destroy-blog',
                    'show-profile', 'edit-profile', 'edit-password-profile',
                    'index-user', 'show-user', 'create-user', 'edit-user', 'edit-password-user', 'destroy-user','edit-roles-user',
                    'index-role', 'show-role', 'create-role', 'edit-role', 'destroy-role',
                    'index-role-permission', 'update-role-permission','generate-pdf-user','generate-pdf-users','generate-csv-users',
                ],
                'Coordenador' => [
                    'dashboard',
                    'index-companie', 'show-companie', 'edit-companie',
                    'index-category', 'show-category', 'create-category', 'edit-category',
                    'index-promotion', 'show-promotion', 'create-promotion', 'edit-promotion',
                    'index-blog', 'show-blog', 'create-blog', 'edit-blog',
                    'show-profile', 'edit-profile', 'edit-password-profile',
                    'index-user', 'show-user', 'edit-user','edit-roles-user',
                    'index-role', 'show-role', 'create-role', 'edit-role', 'destroy-role',
                    'index-role-permission', 'update-role-permission','generate-pdf-user','generate-pdf-users',
                ],
                'Supervisor' => [
                    'dashboard',
                    'index-companie', 'show-companie',
                    'index-promotion', 'show-promotion', 'edit-promotion',
                    'index-category', 'show-category',
                    'index-blog', 'show-blog',
                    'show-profile', 'edit-profile', 'edit-password-profile',
                ],
                'Usuario' => [
                    'dashboard',
                    'show-profile', 'edit-profile', 'edit-password-profile',
                ],
            ];

            foreach ($rolesPermissions as $roleName => $permissions) {
                $role = Role::firstOrCreate(['name' => $roleName]);

                foreach ($permissions as $permName) {
                    $permission = Permission::where('name', $permName)->first();
                    if ($permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }

        } catch (Exception $e) {
            Log::notice('Erro ao cadastrar papéis.', ['error' => $e->getMessage()]);
        }
    }
}
