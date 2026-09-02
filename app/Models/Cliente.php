<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa uma empresa cliente que acessa o Portal do Cliente.
 * Todos os dados abaixo são fictícios (gerados via Faker) — nenhuma
 * relação com clientes reais.
 */
#[Fillable(['nome_fantasia', 'razao_social', 'cnpj', 'cidade', 'telefone'])]
class Cliente extends Model
{
    use HasFactory;

    /**
     * Usuários vinculados a este cliente.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Boletos emitidos para este cliente.
     */
    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    /**
     * Notas fiscais emitidas para este cliente.
     */
    public function notasFiscais(): HasMany
    {
        return $this->hasMany(NotaFiscal::class);
    }
}
