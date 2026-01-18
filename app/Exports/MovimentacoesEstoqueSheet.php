<?php
namespace App\Exports;

use App\Models\EstoqueMovimentacao;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MovimentacoesEstoqueSheet implements FromCollection, WithHeadings, WithTitle
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
        return 'Estoque';
    }

    public function headings(): array
    {
        return [
            'Data',
            'Produto',
            'Categoria',
            'Tipo',
            'Quantidade',
            'Valor Unitário',
            'Total',
        ];
    }

    public function collection()
    {
        return EstoqueMovimentacao::whereBetween('created_at', [$this->inicio, $this->fim])
            ->orderBy('created_at')
            ->get()
            ->map(function ($mov) {
                return [
                    $mov->created_at->format('d/m/Y'),
                    $mov->nome_produto,
                    $mov->categoria,
                    ucfirst($mov->tipo),
                    $mov->quantidade,
                    $mov->valor_unitario,
                    $mov->total,
                ];
            });
    }
}
