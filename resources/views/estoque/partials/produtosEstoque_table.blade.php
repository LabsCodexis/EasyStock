<div class="table-responsive" id="produtosEstoque-table">
    <tbody id="produtosEstoque-tbody">
    @foreach($ProdutoPages as $produto)
        @php
            $total = $produto->quantidade * $produto->valor_unitario;
        @endphp
        <tr>
            <td class="px-6 py-4 flex items-center gap-2">
                {{ $produto->nome ?? 'Produto Não Definido' }}
            </td>
            <td class="px-6 py-4">{{ $produto->categoria }}</td>
            <td class="px-6 py-4 font-bold
                @if($produto->quantidade == 0) text-red-600
                @elseif($produto->quantidade <= 5) text-yellow-500
                @else text-green-600 @endif">
                {{ $produto->quantidade }}
            </td>
            <td class="px-6 py-4">R$ {{ number_format($produto->valor_unitario, 2, ',', '.') }}</td>
            <td class="px-6 py-4 font-bold">R$ {{ number_format($total, 2, ',', '.') }}</td>
            <td class="px-6 py-4 ">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-warning btn-action btn-editar"
                            style="width:36px; height:36px;"
                            title="Editar produto"
                            onclick="openEditModal({{ $produto->id }}, '{{ $produto->nome }}', '{{ $produto->categoria }}', {{ $produto->quantidade }}, {{ $produto->valor_unitario }})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('estoque.destroy', $produto->id) }}" method="POST"
                          class="d-inline delete-form">
                        @csrf @method('DELETE')
                        <button type="button"
                                class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center btn-action"
                                style="width: 36px; height: 36px;" title="Excluir produto">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>

    <div class="mt-4">
        {{ $ProdutoPages->links() }}
    </div>

</div>
