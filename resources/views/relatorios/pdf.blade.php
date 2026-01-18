<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Gerencial</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 20px;
        }

        h1, h2 {
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 16px;
            margin: 20px 0 10px;
            border-bottom: 2px solid #111827;
            padding-bottom: 5px;
        }

        .subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .kpi-grid {
            width: 100%;
            margin-bottom: 20px;
        }

        .kpi {
            width: 32%;
            display: inline-block;
            vertical-align: top;
            margin-bottom: 15px;
        }

        .kpi-box {
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 6px;
        }

        .kpi-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #111827;
            color: #ffffff;
            font-size: 11px;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 7px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 3px 6px;
            font-size: 10px;
            border-radius: 4px;
            color: #fff;
        }

        .entrada {
            background: #16a34a;
        }

        .saida {
            background: #dc2626;
        }

        .total-row td {
            font-weight: bold;
            background: #f3f4f6;
        }

        .page-break {
            page-break-after: always;
        }

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<h1>📊 Relatório Gerencial</h1>
<div class="subtitle">
    Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }}
    até {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
</div>

{{-- KPIs --}}
<div class="kpi-grid">
    <div class="kpi">
        <div class="kpi-box">
            <div class="kpi-label">Faturamento Total</div>
            <div class="kpi-value">R$ {{ number_format($faturamento, 2, ',', '.') }}</div>
        </div>
    </div>

    <div class="kpi">
        <div class="kpi-box">
            <div class="kpi-label">Total de Vendas</div>
            <div class="kpi-value">{{ $qtdVendas }}</div>
        </div>
    </div>

    <div class="kpi">
        <div class="kpi-box">
            <div class="kpi-label">Ticket Médio</div>
            <div class="kpi-value">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</div>
        </div>
    </div>

    <div class="kpi">
        <div class="kpi-box">
            <div class="kpi-label">Itens Vendidos</div>
            <div class="kpi-value">{{ $itensVendidos }}</div>
        </div>
    </div>

    <div class="kpi">
        <div class="kpi-box">
            <div class="kpi-label">Entradas de Estoque</div>
            <div class="kpi-value">R$ {{ number_format($entradas, 2, ',', '.') }}</div>
        </div>
    </div>

    <div class="kpi">
        <div class="kpi-box">
            <div class="kpi-label">Saídas de Estoque</div>
            <div class="kpi-value">R$ {{ number_format($saidas, 2, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- PRODUTOS MAIS VENDIDOS --}}
<h2>🔥 Produtos Mais Vendidos</h2>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Produto</th>
        <th class="text-right">Quantidade</th>
    </tr>
    </thead>
    <tbody>
    @foreach($produtos as $index => $produto)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $produto->nome }}</td>
            <td class="text-right">{{ $produto->quantidade }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="page-break"></div>

{{-- FATURAMENTO DIÁRIO --}}
<h2>📈 Faturamento Diário</h2>
<table>
    <thead>
    <tr>
        <th>Data</th>
        <th class="text-right">Total (R$)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($faturamentoDiario as $dia)
        <tr>
            <td>{{ \Carbon\Carbon::parse($dia->data)->format('d/m/Y') }}</td>
            <td class="text-right">R$ {{ number_format($dia->total, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="page-break"></div>

{{-- FORMAS DE PAGAMENTO --}}
<h2>💳 Formas de Pagamento</h2>
<table>
    <thead>
    <tr>
        <th>Forma</th>
        <th class="text-right">Total (R$)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($formasPagamento as $fp)
        <tr>
            <td>{{ ucfirst($fp->forma_pagamento) }}</td>
            <td class="text-right">R$ {{ number_format($fp->total, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="page-break"></div>

{{-- MOVIMENTAÇÕES DE ESTOQUE --}}
<h2>📦 Movimentações de Estoque</h2>
<table>
    <thead>
    <tr>
        <th>Data</th>
        <th>Produto</th>
        <th>Categoria</th>
        <th>Tipo</th>
        <th class="text-right">Qtd</th>
        <th class="text-right">Valor Unit.</th>
        <th class="text-right">Total</th>
    </tr>
    </thead>
    <tbody>
    @php $totalMov = 0; @endphp
    @foreach($movimentacoesEstoque as $mov)
        @php $totalMov += $mov->total; @endphp
        <tr>
            <td>{{ $mov->created_at->format('d/m/Y') }}</td>
            <td>{{ $mov->nome_produto }}</td>
            <td>{{ $mov->categoria }}</td>
            <td>
                        <span class="badge {{ $mov->tipo }}">
                            {{ ucfirst($mov->tipo) }}
                        </span>
            </td>
            <td class="text-right">{{ $mov->quantidade }}</td>
            <td class="text-right">R$ {{ number_format($mov->valor_unitario, 2, ',', '.') }}</td>
            <td class="text-right">R$ {{ number_format($mov->total, 2, ',', '.') }}</td>
        </tr>
    @endforeach

    <tr class="total-row">
        <td colspan="6">Total Geral Movimentações</td>
        <td class="text-right">R$ {{ number_format($totalMov, 2, ',', '.') }}</td>
    </tr>
    </tbody>
</table>

<div class="footer">
    Relatório gerado em {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
