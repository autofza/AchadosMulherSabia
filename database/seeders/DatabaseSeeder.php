<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,  // 1. Criar permissões
            RoleSeeder::class,        // 2. Criar roles e associar permissões
            UserSeeder::class,        // 3. Criar usuários e vincular roles
            CategorySeeder::class,
            // 4. Demais seeders de negócio
            CompanySeeder::class,
            CouponSeeder::class,
            // Adicione outros seeders aqui se necessário

            BlogSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
