<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        try {
            /******* Usuário principal - Super Admin *******/
            $superAdmin = User::firstOrCreate(
                ['email' => 'fabiano@achadosmulhersabia.com.br'],
                [
                    'name' => 'Fabiano',
                    'password' => Hash::make('123456A#'),
                    'email_verified_at' => now(),
                ]
            );
            $superAdmin->syncRoles(['Super Admin']); // garante que sempre terá o papel Super Admin

            /******* Usuários de teste - apenas fora de produção *******/
            if (App::environment() !== 'production') {
                
                $coordenador = User::firstOrCreate(
                    ['email' => 'coordenador@achadosmulhersabia.com.br'],
                    [
                        'name' => 'Coordenador Líder',
                        'password' => Hash::make('123456A#'),
                        'email_verified_at' => now(),
                    ]
                );
                $coordenador->syncRoles(['Coordenador']);

                $supervisor = User::firstOrCreate(
                    ['email' => 'supervisor@achadosmulhersabia.com.br'],
                    [
                        'name' => 'Supervisor Operacional',
                        'password' => Hash::make('123456A#'),
                        'email_verified_at' => now(),
                    ]
                );
                $supervisor->syncRoles(['Supervisor']);

                $usuario = User::firstOrCreate(
                    ['email' => 'usuario@achadosmulhersabia.com.br'],
                    [
                        'name' => 'Usuário Comum',
                        'password' => Hash::make('123456A#'),
                        'email_verified_at' => now(),
                    ]
                );
                $usuario->syncRoles(['Usuario']);
            }

        } catch (Exception $e) {
            Log::notice('Erro ao cadastrar usuários.', ['error' => $e->getMessage()]);
        }
    }
}