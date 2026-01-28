@extends('layout')

@section('conteudo')
    <style>
        grid-layout {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 24px;
            height: 100%;
        }

        @media (max-width: 1024px) {
            .grid-layout {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }
    </style>

    <style>
        body { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #1e293b, #0f172a); }
        .glass-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2); }
        .product-row { animation: slideIn 0.3s ease-out; }
        @keyframes slideIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }
        .tab-button { padding: 0.5rem 1rem; font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tab-button.active { border-color: #10b981; color: #10b981; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>

    @include('estoque.components.modalEditProdutoEstoque')

    <!-- Cabeçalho -->
    <div class="gradient-bg rounded-4 p-4 p-md-5 mb-4 shadow-lg text-white position-relative overflow-hidden">
        <div class="position-absolute top-0 end-0 rounded-circle" style="width: 16rem; height: 16rem; background: rgba(255,255,255,0.05); transform: translate(8rem, -8rem);"></div>
        <div class="position-absolute bottom-0 start-0 rounded-circle" style="width: 12rem; height: 12rem; background: rgba(255,255,255,0.05); transform: translate(-6rem, 6rem);"></div>
        <div class="position-relative d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3 border border-white border-opacity-25 shadow" style="width: 64px; height: 64px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);"><i class="bi bi-box-seam fs-1 text-white"></i></div>
                <div>
                    <h1 class="h3 fw-bold mb-1">Easy Stock</h1>
                    <p class="mb-0 text-white-50 small">Desenvolvido por Labs Codexis</p>
                </div>
            </div>
            <div class="status-badge d-flex align-items-center gap-2 rounded-pill px-4 py-2 border border-success border-opacity-50" style="background: rgba(34, 197, 94, 0.2); backdrop-filter: blur(10px);">
                <span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                <span class="text-success fw-semibold small">Sistema ativo</span>
            </div>
        </div>
    </div>

        <div class="container mx-auto p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white shadow rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 font-medium">Total de Produtos</p>
                        <p class="text-2xl font-bold">{{ $totalProdutos }} itens em estoque</p>
                    </div>
                    <div class="text-green-950-950">
                        <i style="font-size: 38px" class="bi bi-box-seam"></i>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 font-medium">Valor em Estoque</p>
                        <p class="text-2xl font-bold text-green-600">R$ {{ number_format($valorTotalEstoque, 2, ',', '.') }}</p>
                    </div>
                    <div class="text-green-600">
                        <i style="font-size: 38px" class="bi bi-currency-dollar"></i>

                    </div>
                </div>
            </div>
            <div class="grid-layout ">
                <div>
                    <form method="GET" action="{{ route('estoque.index') }}" class="flex flex-col md:flex-row md:items-center md:justify-between w-full gap-4">

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div class="p-2 ">
                                <div class="flex items-center gap-2 w-full border rounded-lg p-2">
                                    <i class="bi bi-search"></i>
                                    <input style="background: transparent" type="text" name="search" value="{{ request('search') }}" placeholder="Buscar produto..." class="flex-1 outline-none border-none">
                                </div>
                            </div>

                            <div class="p-2">
                                <div class="flex items-center gap-2 border rounded-lg p-2">
                                    <i class="bi bi-list-ul text-gray-400"></i>
                                    <select name="categoria" class="outline-none border-none bg-transparent">
                                        <option value="">Todas categorias</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                                                {{ $categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="p-2">
                                <div class="flex items-center gap-2 border rounded-lg p-2">
                                    <i class="bi bi-exclamation-triangle text-gray-400"></i>
                                    <select name="status" class="outline-none border-none bg-transparent">
                                        <option value="">Todos status</option>
                                        <option value="pouco" {{ request('status') == 'pouco' ? 'selected' : '' }}>Pouco Estoque</option>
                                        <option value="esgotado" {{ request('status') == 'esgotado' ? 'selected' : '' }}>Esgotado</option>
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="flex gap-2">
                            <button type="submit" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>

                            <a href="{{ route('estoque.index') }}" class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                                <i class="bi bi-x-circle"></i> Limpar filtros
                            </a>
                        </div>
                    </form>
                </div>

                <div class="table-scroll bg-white shadow rounded-lg">
                    <div class="px-6 py-4 bg-gray-50 border-b flex items-center justify-between">

                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                <i class="bi bi-box-seam text-blue-600"></i>
                                Produtos disponíveis para venda
                            </h2>
                        </div>


                        <div class="flex items-center gap-3">
                            <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold">
                                {{ $totalEsgotados }} Esgotado
                            </span>

                            <span class="text-xs bg-orange-100 text-orange-600 px-3 py-1 rounded-full font-bold">
                                {{ $totalPouco }} Mínimo
                            </span>
                        </div>
                    </div>

                    <div id="app">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Produto</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Categoria</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Quantidade</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Preço</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Total</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Ajustes</th>
                            </tr>
                            </thead>
                            <tbody id="produtosEstoque-tbody">
                             @include('estoque.partials.produtosEstoque_table')
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    <script>

        const openEditModal = (id, nome, categoria, quantidade, valor) => {
            console.log(id, nome, categoria, quantidade, valor)
            document.getElementById('edit-produto-id').value = id;
            document.getElementById('edit-produto-nome').value = nome;
            document.getElementById('edit-produto-categoria').value = categoria;
            document.getElementById('edit-produto-quantidade').value = quantidade;
            document.getElementById('edit-produto-valor').value = valor;

            const modalEl = document.getElementById('editProdutoModalEstoque')
            const modal = new bootstrap.Modal(modalEl)
            modal.show();
        }

        document.getElementById('editProdutoEstoqueForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = e.target;
            const id = document.getElementById('edit-produto-id').value;

            fetch(`/estoque/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    nome: form.nome.value,
                    categoria: form.categoria.value,
                    quantidade: form.quantidade.value,
                    valor: form.valor.value,
                })
            })
                .then(async res => {
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw errorData;
                    }
                    return res.json();
                })
                .then(data => {

                    const modalEl = document.getElementById('editProdutoModalEstoque');
                    const modal = bootstrap.Modal.getInstance(modalEl);

                    modalEl.addEventListener('hidden.bs.modal', function () {
                        window.location.reload();
                    }, {once: true});

                    modal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Produto atualizado com sucesso',
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(err => {
                    console.error(err);
                    let message = 'Não foi possível atualizar o produto';
                    if (err.errors) {
                        message = Object.values(err.errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        html: message
                    });
                });
        });


        document.addEventListener('DOMContentLoaded', function () {
            @if(session('sucesso'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: '{{ session('sucesso') }}',
                confirmButtonColor: '#22c55e',
                timer: 3000,
                timerProgressBar: true
            });
            @endif

            @if(session('erro'))
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '{{ session('erro') }}',
                confirmButtonColor: '#ef4444',
                timer: 3000,
                timerProgressBar: true
            });
            @endif

            document.querySelectorAll('.delete-form button').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: "Você não poderá reverter isso!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sim, deletar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

    </script>
@endsection
