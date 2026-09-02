<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Boleto bancário fictício vinculado a um cliente.
 *
 * O status não é armazenado em coluna própria: é sempre calculado a partir
 * de data_baixa / data_vencimento, para nunca ficar desatualizado.
 */
#[Fillable([
    'cliente_id',
    'numero_documento',
    'valor_documento',
    'saldo_devedor',
    'data_vencimento',
    'data_baixa',
])]
class Boleto extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'valor_documento' => 'decimal:2',
            'saldo_devedor' => 'decimal:2',
            'data_vencimento' => 'date',
            'data_baixa' => 'date',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Status calculado do boleto: pago | vencido | aberto.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! is_null($this->data_baixa)) {
                    return 'pago';
                }

                if ($this->data_vencimento instanceof Carbon && $this->data_vencimento->lt(Carbon::today())) {
                    return 'vencido';
                }

                return 'aberto';
            },
        );
    }

    /**
     * Escopo: apenas boletos com determinado status calculado.
     */
    public function scopeStatus($query, ?string $status)
    {
        return match ($status) {
            'pago' => $query->whereNotNull('data_baixa'),
            'vencido' => $query->whereNull('data_baixa')->whereDate('data_vencimento', '<', now()),
            'aberto' => $query->whereNull('data_baixa')->whereDate('data_vencimento', '>=', now()),
            default => $query,
        };
    }
}
