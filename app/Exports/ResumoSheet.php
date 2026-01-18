<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class ResumoSheet implements FromArray, WithTitle
{
    protected $inicio;
    protected $fim;

    public function __construct($inicio, $fim)
    {
        $this->inicio = $inicio;
        $this->fim = $fim;
    }

    public function title(): string
    {
        return 'Resumo';
    }

    public function array(): array
    {
        $faturamento = DB::table('vendas')
            ->whereBetween('created_at', [$this->inicio, $this->fim])
            ->sum('total');

        $qtdVendas = DB::table('vendas')
            ->whereBetween('created_at', [$this->inicio, $this->fim])
            ->count();

        $ticketMedio = $qtdVendas > 0 ? $faturamento / $qtdVendas : 0;

        return [
            ['Relatório Gerencial'],
            ['Período', $this->inicio->format('d/m/Y').' até '.$this->fim->format('d/m/Y')],
            [],
            ['Faturamento Total', $faturamento],
            ['Total de Vendas', $qtdVendas],
            ['Ticket Médio', $ticketMedio],
        ];
    }
}
