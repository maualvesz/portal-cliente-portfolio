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
        th { background: #f9fafb; font-size: 11px; text-transform: uppercase; color: #6b7280; width: 220px; }
        .rodape { margin-top: 40px; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="aviso">
        DOCUMENTO FICTÍCIO — gerado para fins de demonstração/portfólio. Não possui valor fiscal.
    </div>

    <h1>Portal do Cliente — Nota Fiscal {{ $nota->numero_nota }}</h1>
    <p class="subtitulo">{{ $nota->cliente->nome_fantasia }} &middot; CNPJ {{ $nota->cliente->cnpj }}</p>

    <table>
        <tr>
            <th>Número da nota</th>
            <td>{{ $nota->numero_nota }}</td>
        </tr>
        <tr>
            <th>Pedido</th>
            <td>{{ $nota->pedido_id }}</td>
        </tr>
        <tr>
            <th>Valor total</th>
            <td>R$ {{ number_format($nota->valor_total, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Data de emissão</th>
            <td>{{ $nota->data_emissao->format('d/m/Y') }}</td>
        </tr>
    </table>

    <p class="rodape">
        Gerado automaticamente pelo Portal do Cliente (protótipo de portfólio) em {{ now()->format('d/m/Y H:i') }}.
        Todos os dados são fictícios.
    </p>
</body>
</html>
