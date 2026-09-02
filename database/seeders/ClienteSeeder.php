<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Cria de 5 a 8 clientes fictícios (empresas inventadas) e, para cada um,
 * um usuário de teste (cliente1@example.com, cliente2@example.com, ...)
 * com senha "password". Nenhum dado aqui corresponde a uma empresa real.
 */
class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('pt_BR');
        $quantidade = random_int(5, 8);
        $sufixos = ['LTDA', 'S.A.', 'ME', 'EIRELI', 'Comércio LTDA', 'Indústria S.A.'];

        for ($i = 1; $i <= $quantidade; $i++) {
            $nomeFantasia = $faker->company();

            $cliente = Cliente::create([
                'nome_fantasia' => $nomeFantasia,
                'razao_social' => $nomeFantasia.' '.$faker->randomElement($sufixos),
                'cnpj' => $faker->unique()->cnpj(),
                'cidade' => $faker->city(),
                'telefone' => $faker->cellphoneNumber(),
            ]);

            User::create([
                'name' => $faker->name(),
                'email' => "cliente{$i}@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'cliente_id' => $cliente->id,
            ]);
        }

        $this->command?->info("Criados {$quantidade} clientes fictícios com 1 usuário de teste cada (senha: password).");
    }
}
