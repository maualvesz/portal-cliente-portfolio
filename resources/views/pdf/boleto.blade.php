<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1f2937; font-size: 12px; }
        .aviso {
            background: #fef3c7; border: 1px solid #f59e0b; color: #92400e;
            padding: 8px 12px; border-radius: 6px; margin-bottom: 20px; font-size: 11px;
        }
        h1 { font-size: 16px; margin-bottom: 2px; }
        .subtitulo { color: #6b7280; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-size: 11px; text-transform: uppercase; color: #6b7280; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: bold; }
        .status-pago { background: #d1fae5; color: #065f46; }
        .status-aberto { background: #fef3c7; color: #92400e; }
        .status-vencido { background: #fee2e2; color: #991b1b; }
        .barras { margin-top: 30px; text-align: center; font-family: 'Courier New', monospace; font-size: 30px; letter-spacing: 2px; }
        .rodape { margin-top: 40px; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="aviso">
        DOCUMENTO FICTÍCIO — gerado para fins de demonstração/portfólio. Não possui valor fiscal ou bancário.
    </div>

    <h1>Portal do Cliente — Boleto {{ $boleto->numero_documento }}</h1>
    <p class="subtitulo">{{ $boleto->cliente->nome_fantasia }} &middot; CNPJ {{ $boleto->cliente->cnpj }}</p>

    <table>
        <tr>
            <th>Número do documento</th>
            <td>{{ $boleto->numero_documento }}</td>
        </tr>
        <tr>
            <th>Valor do documento</th>
            <td>R$ {{ number_format($boleto->valor_documento, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Saldo devedor</th>
            <td>R$ {{ number_format($boleto->saldo_devedor, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Data de vencimento</th>
            <td>{{ $boleto->data_vencimento->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Data de baixa</th>
            <td>{{ $boleto->data_baixa?->format('d/m/Y') ?? '—' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="status status-{{ $boleto->status }}">{{ strtoupper($boleto->status) }}</span>
            </td>
        </tr>
    </table>

    <div class="barras">||||| |||| ||||| || ||||| |||| |||</div>

    <p class="rodape">
        Gerado automaticamente pelo Portal do Cliente (protótipo de portfólio) em {{ now()->format('d/m/Y H:i') }}.
        Todos os dados são fictícios.
    </p>
</body>
</html>
