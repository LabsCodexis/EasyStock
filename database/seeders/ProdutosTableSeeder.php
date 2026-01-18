<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produtos = [
            // Cookies
            ['categoria' => 'Cookies', 'nome' => 'Classic', 'valor' => 18.00, 'gramatura' => '80g', 'quantidade' => 40],
            ['categoria' => 'Cookies', 'nome' => 'Nutella', 'valor' => 18.00, 'gramatura' => '80g', 'quantidade' => 35],
            ['categoria' => 'Cookies', 'nome' => 'Kinder-Bueno', 'valor' => 18.00, 'gramatura' => '80g', 'quantidade' => 30],
            ['categoria' => 'Cookies', 'nome' => 'Red-Velvet', 'valor' => 18.00, 'gramatura' => '80g', 'quantidade' => 25],
            ['categoria' => 'Cookies', 'nome' => 'Brigadeiro-Belga', 'valor' => 18.00, 'gramatura' => '80g', 'quantidade' => 25],
            ['categoria' => 'Cookies', 'nome' => 'Laka-Red', 'valor' => 18.00, 'gramatura' => '80g', 'quantidade' => 20],
            ['categoria' => 'Cookies', 'nome' => 'M&M', 'valor' => 18.00, 'gramatura' => '80g', 'quantidade' => 30],

            // Mini-Cakes
            ['categoria' => 'Mini-Cake', 'nome' => 'Classic', 'valor' => 35.00, 'gramatura' => '180g', 'quantidade' => 15],
            ['categoria' => 'Mini-Cake', 'nome' => 'Nutella', 'valor' => 35.00, 'gramatura' => '180g', 'quantidade' => 12],
            ['categoria' => 'Mini-Cake', 'nome' => 'Kinder-Bueno', 'valor' => 35.00, 'gramatura' => '180g', 'quantidade' => 10],
            ['categoria' => 'Mini-Cake', 'nome' => 'Red-Velvet', 'valor' => 35.00, 'gramatura' => '180g', 'quantidade' => 10],
            ['categoria' => 'Mini-Cake', 'nome' => 'Brigadeiro-Belga', 'valor' => 35.00, 'gramatura' => '180g', 'quantidade' => 10],
            ['categoria' => 'Mini-Cake', 'nome' => 'Laka-Red', 'valor' => 35.00, 'gramatura' => '180g', 'quantidade' => 8],
            ['categoria' => 'Mini-Cake', 'nome' => 'M&M', 'valor' => 35.00, 'gramatura' => '180g', 'quantidade' => 12],

            // Mini-Cookies
            ['categoria' => 'Mini-Cookie', 'nome' => 'Mix 24', 'valor' => 40.00, 'gramatura' => '240g', 'quantidade' => 20],
            ['categoria' => 'Mini-Cookie', 'nome' => 'Mix Red 24', 'valor' => 40.00, 'gramatura' => '240g', 'quantidade' => 15],
            ['categoria' => 'Mini-Cookie', 'nome' => 'Copo-Bolha Sortidos', 'valor' => 22.00, 'gramatura' => '90g', 'quantidade' => 30],
            ['categoria' => 'Mini-Cookie', 'nome' => 'Copo-Bolha Red-Velvet', 'valor' => 22.00, 'gramatura' => '90g', 'quantidade' => 25],

            // Bebidas
            ['categoria' => 'Bebidas', 'nome' => 'Coca-Cola Original', 'valor' => 8.00, 'gramatura' => '350ml', 'quantidade' => 60],
            ['categoria' => 'Bebidas', 'nome' => 'Coca-Cola Zero', 'valor' => 8.00, 'gramatura' => '350ml', 'quantidade' => 50],
            ['categoria' => 'Bebidas', 'nome' => 'Tchual', 'valor' => 8.00, 'gramatura' => '350ml', 'quantidade' => 40],
            ['categoria' => 'Bebidas', 'nome' => 'Fanta Uva', 'valor' => 8.00, 'gramatura' => '350ml', 'quantidade' => 45],
            ['categoria' => 'Bebidas', 'nome' => 'Sprite', 'valor' => 8.00, 'gramatura' => '350ml', 'quantidade' => 40],
            ['categoria' => 'Bebidas', 'nome' => 'Del Valle Uva', 'valor' => 6.00, 'gramatura' => '290ml', 'quantidade' => 35],
        ];

        foreach ($produtos as $produto) {
            Produto::create([
                'categoria'     => $produto['categoria'],
                'nome'          => $produto['nome'],
                'valor'         => $produto['valor'],
                'gramatura'     => $produto['gramatura'],
                'quantidade'    => $produto['quantidade'] ?? 0,
            ]);
        }
    }
}
