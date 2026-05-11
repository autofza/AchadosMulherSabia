<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $permissions = [
                // Dashboard
                ['title'=> 'Dashboard', 'name' => 'dashboard'],

                // Permissões
                ['title' => 'Visualizar a permissão', 'name' => 'index-permission'],
                ['title' => 'Visualizar a permissão', 'name' => 'show-permission'],
                ['title' => 'Cadastrar a permissão', 'name' => 'create-permission'],
                ['title' => 'Editar a permissão', 'name' => 'edit-permission'],
                ['title' => 'Apagar a permissão', 'name' => 'destroy-permission'],

                // Empresas
                ['title' => 'Visualizar o companie', 'name' => 'index-company'],
                ['title' => 'Visualizar o companie', 'name' => 'show-company'],
                ['title' => 'Cadastrar o companie', 'name' => 'create-company'],
                ['title' => 'Editar o companie', 'name' => 'edit-company'],
                ['title' => 'Apagar o companie', 'name' => 'destroy-company'],

                // Categorias
                ['title' => 'Visualizar o category', 'name' => 'index-category'],
                ['title' => 'Visualizar o category', 'name' => 'show-category'],
                ['title' => 'Cadastrar o category', 'name' => 'create-category'],
                ['title' => 'Editar o category', 'name' => 'edit-category'],
                ['title' => 'Apagar o category', 'name' => 'destroy-category'],

                // Promoções
                ['title' => 'Visualizar o promotion', 'name' => 'index-promotion'],
                ['title' => 'Visualizar o promotion', 'name' => 'show-promotion'],
                ['title' => 'Cadastrar o promotion', 'name' => 'create-promotion'],
                ['title' => 'Editar o promotion', 'name' => 'edit-promotion'],
                ['title' => 'Apagar o promotion', 'name' => 'destroy-promotion'],

                // Blog
                ['title' => 'Visualizar o blog', 'name' => 'index-blog'],
                ['title' => 'Visualizar o blog', 'name' => 'show-blog'],
                ['title' => 'Cadastrar o blog', 'name' => 'create-blog'],
                ['title' => 'Editar o blog', 'name' => 'edit-blog'],
                ['title' => 'Apagar o blog', 'name' => 'destroy-blog'],

                // Perfil (próprio usuário)
                ['title' => 'Visualizar o perfil', 'name' => 'show-profile'],
                ['title' => 'Visualizar o perfil', 'name' => 'edit-profile'],
                ['title' => 'Editar a senha do perfil', 'name' => 'edit-password-profile'],

                // Usuários (gestão)
                ['title'=> 'Listar os usuários', 'name' => 'index-user'],
                ['title'=> 'Visualizar o usuário', 'name' => 'show-user'],
                ['title'=> 'Cadastrar o usuário', 'name' => 'create-user'],
                ['title'=> 'Editar o usuário', 'name' => 'edit-user'],
                ['title'=> 'Editar a senha do usuário', 'name' => 'edit-password-user'],
                ['title'=> 'Apagar o usuário', 'name' => 'destroy-user'],
                ['title'=> 'Editar papéis do usuário', 'name' => 'edit-roles-user'],
                ['title'=> 'Gerar PDF do usuário', 'name' => 'generate-pdf-user'],
                ['title'=> 'Gerar PDF dos usuários', 'name' => 'generate-pdf-users'],
                ['title'=> 'Gerar CSV dos usuários', 'name' => 'generate-csv-users'],

                ['title' => 'Listar os papéis', 'name' => 'index-role'],
                ['title' => 'Visualizar o papel', 'name' => 'show-role'],
                ['title' => 'Cadastrar o papel', 'name' => 'create-role'],
                ['title' => 'Editar o papel', 'name' => 'edit-role'],

                ['title' => 'Listar as permissões do papel', 'name' => 'index-role-permission'],
                ['title' => 'Listar as permissões do papel', 'name' => 'update-role-permission'],

                ['title' => 'Listar as permissões', 'name' => 'index-permission'],
                ['title' => 'Visualizar a permissão', 'name' => 'show-permission'],
                ['title' => 'Cadastrar a permissão', 'name' => 'create-permission'],
                ['title' => 'Editar a permissão', 'name' => 'edit-permission'],
                ['title' => 'Apagar a permissão', 'name' => 'destroy-permission'],
            ];

            foreach ($permissions as $permission) {
                // Se não encontrar o registro, cadastra o registro no BD
                Permission::firstOrCreate(
                    ['title' => $permission['title'], 'name' => $permission['name']],
                    [
                        'title' => $permission['title'],
                        'name' => $permission['name'],
                        'guard_name' => 'web'
                    ],
                );
            }
        } catch (Exception $e) {
            Log::notice('Permissões não cadastradas.', ['error' => $e->getMessage()]);
        }
    }
}
