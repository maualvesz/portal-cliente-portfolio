<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Popula o banco inteiramente com dados fictícios (Faker), simulando
     * uma sincronização já concluída com o ERP externo. Nenhum dado real
     * de cliente é utilizado em nenhum momento.
     */
    public function run(): void
    {
        $this->call([
            ClienteSeeder::class,
            BoletoSeeder::class,
            NotaFiscalSeeder::class,
        ]);
    }
}
