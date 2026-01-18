<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProdutosMaisVendidosSheet implements FromCollection, WithHeadings, WithTitle
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
        return 'Produtos';
    }

    public function headings(): array
    {
        return ['Produto', 'Quantidade Vendida'];
    }

    public function collection()
    {
        return DB::table('venda_itens')
            ->join('produtos', 'produtos.id', '=', 'venda_itens.produto_id')
            ->join('vendas', 'vendas.id', '=', 'venda_itens.venda_id')
            ->whereBetween('vendas.created_at', [$this->inicio, $this->fim])
            ->select(
                'produtos.nome',
                DB::raw('SUM(venda_itens.quantidade) as quantidade')
            )
            ->groupBy('produtos.nome')
            ->orderByDesc('quantidade')
            ->get();
    }
}
