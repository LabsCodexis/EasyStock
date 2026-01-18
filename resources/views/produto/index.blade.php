@extends('layout')
@section('conteudo')
    <style>
        body {
            box-sizing: border-box;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #1e293b, #0f172a);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-row-hover:hover {
            background: rgba(16, 185, 129, 0.05);
            transition: all 0.2s ease;
        }

        .btn-action {
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-button {
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab-button.active {
            border-color: #10b981;
            color: #10b981;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .animate-slide {
            animation: slideIn 0.3s ease-out;
        }

        .status-badge {
            animation: pulse-glow 2s infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
            }
            50% {
                box-shadow: 0 0 30px rgba(34, 197, 94, 0.5);
            }
        }
    </style>
    <style>@view-transition {
            navigation: auto;
        }</style>
    <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>

    @include('produto.components.modalEditProduto')
    @include('produto.components.modalAdicionarProduto')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <div class="container-fluid py-4" style="font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc;">
        <!-- Background Pattern -->
        <div class="position-fixed top-0 start-0 w-100 h-100 pointer-events-none" style="opacity: 0.3; z-index: -1;">
            <div class="position-absolute w-100 h-100"
                 style="background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        <!-- Header Card -->
        <div class="gradient-bg rounded-4 p-4 p-md-5 mb-4 shadow-lg text-white position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 rounded-circle"
                 style="width: 16rem; height: 16rem; background: rgba(255,255,255,0.05); transform: translate(8rem, -8rem);"></div>
            <div class="position-absolute bottom-0 start-0 rounded-circle"
                 style="width: 12rem; height: 12rem; background: rgba(255,255,255,0.05); transform: translate(-6rem, 6rem);"></div>
            <div
                class="position-relative d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div
                        class="d-flex align-items-center justify-content-center rounded-3 border border-white border-opacity-25 shadow"
                        style="width: 64px; height: 64px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                        <i class="bi bi-box-seam fs-1 text-white"></i></div>
                    <div>
                        <h1 class="h3 fw-bold mb-1">Easy Stock</h1>
                        <p class="mb-0 text-white-50 small">Desenvolvido por Labs Codexis</p>
                    </div>
                </div>
                <div
                    class="status-badge d-flex align-items-center gap-2 rounded-pill px-4 py-2 border border-success border-opacity-50"
                    style="background: rgba(34, 197, 94, 0.2); backdrop-filter: blur(10px);">
                    <span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                    <span class="text-success fw-semibold small">Sistema ativo</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 mb-6">
            <a href="{{ route('entradas.index') }}" class="tab-button active" data-tab="nova-entrada">Nova Entrada</a>
            <div class="tab-button" data-tab="produtos-cadastrados">Produtos Cadastrados</div>
        </div>

        <!-- Main Content Card -->
        <div class="glass-card rounded-4 shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div
                class="bg-light bg-gradient px-4 px-md-5 py-4 border-bottom d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.1);"><i
                            class="bi bi-list-ul text-success fs-5"></i></div>
                    <div>
                        <h2 class="h5 mb-0 fw-bold">Produtos Cadastrados</h2>
                        <p class="text-muted small mb-0">Gerencie seu estoque</p>
                    </div>
                </div>
                <a href="javascript:void(0)" onclick="openAdcModal()"
                   class="btn btn-success shadow-sm d-flex align-items-center gap-2 btn-action">
                    <i class="bi bi-plus-circle"></i> Novo Produto
                </a>

            </div>

            <div class="p-4 p-md-5">
                @if($produtos->isEmpty())
                    <div class="text-center py-5">
                        <div
                            class="d-flex align-items-center justify-content-center rounded-3 bg-secondary bg-opacity-10 mx-auto mb-4"
                            style="width: 80px; height: 80px;"><i class="bi bi-inbox text-secondary text-opacity-50"
                                                                  style="font-size: 2.5rem;"></i></div>
                        <h4 class="text-muted fw-semibold mb-2">Nenhum produto cadastrado</h4>
                        <p class="text-muted mb-4">Comece adicionando seu primeiro produto ao estoque</p>
                        <a href="{{ route('produtos.create') }}"
                           class="btn btn-success shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle"></i> Cadastrar Primeiro Produto
                        </a>
                    </div>
                @else
                    <div class="bg-light bg-opacity-50 rounded-3 border overflow-hidden">
                        <div class="table-responsive" id="produtos-table">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-uppercase fw-semibold small text-muted border-0"><i
                                            class="bi bi-box me-2"></i>Produto
                                    </th>
                                    <th class="px-4 py-3 text-uppercase fw-semibold small text-muted text-center border-0">
                                        <i class="bi bi-tag me-2"></i>Categoria
                                    </th>
{{--                                    <th class="px-4 py-3 text-uppercase fw-semibold small text-muted text-center border-0">--}}
{{--                                        <i class="bi bi-stack me-2"></i>Quantidade--}}
{{--                                    </th>--}}
                                    <th class="px-4 py-3 text-uppercase fw-semibold small text-muted text-center border-0">
                                        <i class="bi bi-cash me-2"></i>Valor Unit.
                                    </th>
                                    <th class="px-4 py-3 text-uppercase fw-semibold small text-muted text-center border-0"
                                        style="width: 150px;"><i class="bi bi-gear me-2"></i>Ações
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="produtos-tbody">
                                @include('produto.partials.produtos_table')
                                </tbody>
                            </table>

                        </div>


                        <div class="border-top bg-light bg-opacity-50 px-4 py-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-box-seam text-primary"></i>
                                        <span class="text-muted small">Total de produtos:</span>
                                        <span class="fw-bold text-dark">{{ $produtos->count() }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-2"><i class="bi bi-stack text-info"></i>
                                        <span class="text-muted small">Total em estoque:</span> <span
                                            class="fw-bold text-dark">{{ $produtos->sum('quantidade') }}</span></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-2"><i class="bi bi-cash text-success"></i>
                                        <span class="text-muted small">Valor total:</span> <span
                                            class="fw-bold text-success">R$ {{ number_format($produtos->sum(function($p) { return $p->valor * $p->quantidade; }), 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4 text-center text-muted small">
            <p class="mb-0">Easy Stock © {{ date('Y') }} • Sistema de Gestão de Estoque</p>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let page = 1;
            let loading = false;

            window.addEventListener('scroll', function () {
                if (loading) return;

                const scrollTop = window.scrollY;
                const windowHeight = window.innerHeight;
                const fullHeight = document.body.offsetHeight;

                if (scrollTop + windowHeight >= fullHeight - 200) { // quando chegar perto do final
                    loading = true;
                    page++;

                    fetch(`{{ route('produtos.index') }}?page=${page}`, {
                        headers: {'X-Requested-With': 'XMLHttpRequest'}
                    })
                        .then(res => res.text())
                        .then(data => {
                            if (data.trim().length == 0) return;
                            const parser = new DOMParser();
                            const htmlDoc = parser.parseFromString(data, 'text/html');
                            const newRows = htmlDoc.querySelector('tbody').innerHTML;
                            document.querySelector('tbody').insertAdjacentHTML('beforeend', newRows);
                            loading = false;
                        })
                        .catch(err => {
                            console.error(err);
                            loading = false;
                        });
                }
            });
        });

        function openEditModal(id, nome, categoria, valor, gramatura) {
            document.getElementById('edit-produto-id').value = id;
            document.getElementById('edit-produto-nome').value = nome;
            document.getElementById('edit-produto-categoria').value = categoria;
            document.getElementById('edit-produto-valor').value = valor;
            document.getElementById('edit-produto-gramatura').value = gramatura;

            const modal = new bootstrap.Modal(document.getElementById('editProdutoModal'));
            modal.show();
        }

        document.getElementById('editProdutoForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = e.target;
            const id = document.getElementById('edit-produto-id').value;

            fetch(`/produtos/${id}`, {
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
                    gramatura: form.gramatura.value
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
                    const row = document.querySelector(`#produtos-tbody tr[data-id="${data.id}"]`);
                    if (row) {
                        row.children[0].querySelector('span').textContent = data.nome;
                        row.children[1].querySelector('span').textContent = data.categoria;
                        row.children[2].querySelector('span').textContent = data.quantidade;
                        row.children[3].querySelector('span').textContent = 'R$ ' + Number(data.valor).toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }

                    // Fecha modal
                    const modalEl = document.getElementById('editProdutoModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
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

        function openAdcModal() {
            // Limpar os campos do formulário
            document.getElementById('editProdutoForm').reset();

            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('adcProdutoModal'));
            modal.show();
        }
    </script>

    <script>
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
