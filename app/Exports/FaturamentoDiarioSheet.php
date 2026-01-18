<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;



class FaturamentoDiarioSheet implements WithMultipleSheets
{
    protected $inicio;
    protected $fim;

    public function __construct($inicio, $fim)
    {
        $this->inicio = $inicio;
        $this->fim = $fim;
    }

    public function sheets(): array
    {
        return [
            new ResumoSheet($this->inicio, $this->fim),
            new ProdutosMaisVendidosSheet($this->inicio, $this->fim),
            new FaturamentoDiarioSheet($this->inicio, $this->fim),
            new FormasPagamentoSheet($this->inicio, $this->fim),
            new MovimentacoesEstoqueSheet($this->inicio, $this->fim),
        ];
    }
}
