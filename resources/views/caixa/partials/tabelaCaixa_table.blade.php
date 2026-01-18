<div class="table-responsive" id="tabelaCaixa-table">
    <tbody id="tabelaCaixa-tbody">
        @foreach($produtosEstoque as $produto)

        <tr class="hover:bg-blue-50 transition cursor-pointer"
            onclick="addFromTable(
                {{ $produto->produto_id }},
                '{{ addslashes($nomesProdutos[$produto->id]) }}',
                {{ $produto['valor_unitario'] }},
                '{{ $produto->categoria }}'
            )"
        >

            <td class="px-6 py-4 font-medium text-gray-800">{{ $produto->categoria }}</td>
            <td class="px-6 py-4 font-medium text-gray-800">{{ $nomesProdutos[$produto->id] }}</td>
            <td class="px-6 py-4 text-center font-semibold text-green-600">
                R$ {{ number_format($produto['valor_unitario'],2,',','.') }}
            </td>
            <td class="px-6 py-4 text-center">
                @if($produto['quantidade'] > 10)
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold">
                                                    {{ $produto['quantidade'] }}
                                                </span>
                @elseif($produto['quantidade'] > 0)
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-bold">
                                                    {{ $produto['quantidade'] }}
                                                </span>
                @else
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold">0</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    <div class="mt-4">
        {{ $produtosEstoque->links() }}
    </div>

</div>
