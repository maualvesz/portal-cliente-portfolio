<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Nota fiscal fictícia vinculada a um cliente e agrupada por "pedido"
 * (pedido_id) — um mesmo pedido pode gerar mais de uma nota fiscal.
 */
#[Fillable(['cliente_id', 'pedido_id', 'numero_nota', 'valor_total', 'data_emissao'])]
class NotaFiscal extends Model
{
    use HasFactory;

    /**
     * Nome da tabela: o pluralizador padrão do Eloquent geraria
     * "nota_fiscals" (não entende plural em português), então é
     * explicitado aqui.
     */
    protected $table = 'notas_fiscais';

    protected function casts(): array
    {
        return [
            'valor_total' => 'decimal:2',
            'data_emissao' => 'date',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
