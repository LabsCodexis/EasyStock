<div class="table-responsive" id="produtos-table">
    <tbody id="produtos-tbody">
    @foreach($produtosPag as $produto)
        <tr class="table-row-hover bg-white" data-id="{{ $produto->id }}">
            <td class="px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.1));"><i class="bi bi-box-seam text-success"></i></div>
                    <span class="fw-semibold text-dark">{{ $produto->nome }}</span>
                </div>
            </td>
            <td class="px-4 py-3 text-center"><span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2"> {{ $produto->categoria }} </span></td>
{{--            <td class="px-4 py-3 text-center"><span class="badge bg-secondary bg-opacity-25 text-dark fw-semibold px-3 py-2"> {{ $produto->quantidade }} </span></td>--}}
            <td class="px-4 py-3 text-center"><span class="fw-bold text-success"> R$ {{ number_format($produto->valor, 2, ',', '.') }} </span></td>
            <td class="px-4 py-3 text-center">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-warning btn-action"
                            style="width:36px; height:36px;"
                            title="Editar produto"
                            onclick="openEditModal({{ $produto->id }}, '{{ $produto->nome }}', '{{ $produto->categoria }}', {{ $produto->quantidade }}, {{ $produto->valor }}, '{{ $produto->gramatura ?? '' }}')">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="d-inline delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center btn-action" style="width: 36px; height: 36px;" title="Excluir produto">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>

    <div id="produtos-pagination" class="mt-3">
        {!! $produtosPag->links() !!}
    </div>

</div>
