    @extends('layout')

    @section('conteudo')
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <div class="min-h-full w-full p-4">
            {{-- Cabeçalho --}}
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
                </div>
            </div>

            @if($caixaAberto)
                {{-- Abas --}}
                <div class="flex gap-2 mb-6">
                    <div class="tab-button active" data-tab="nova-entrada">Nova Entrada</div>
                    <a href="{{ route('produtos.index') }}" class="tab-button" data-tab="produtos-cadastrados">Produtos Cadastrados</a>
                </div>

                {{-- Conteúdos da Abas --}}
                <div class="tab-content active" id="nova-entrada">
                    <div class="glass-card rounded-3xl shadow-xl overflow-hidden p-6 sm:p-8">
                        <form id="form-add-produto" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">Produto</label>
                                    <select id="select-produto" class="custom-select w-full px-3 py-2 border border-slate-200 rounded-xl">
                                        <option value="">Selecione um produto</option>
                                        @php $produtosAgrupados = $produtos->groupBy('categoria'); @endphp
                                        @foreach($produtosAgrupados as $categoria => $produtosDaCategoria)
                                            <optgroup label="{{ $categoria }}">
                                                @foreach($produtosDaCategoria as $produto)
                                                    <option value="{{ $produto->id }}"
                                                            data-nome="{{ $produto->nome }}"
                                                            data-valor="{{ $produto->valor }}"
                                                            data-categoria="{{ $categoria }}">
                                                        {{ $produto->nome }} - <b>Qnt.</b> <span
                                                            style="background-color: #fef3c7; padding: 2px 6px; border-radius: 4px; font-weight: bold;">{{ $produto->quantidade }}</span>
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-end gap-2">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-slate-600 mb-1">Quantidade</label>
                                        <input type="number" id="quantidade-produto" min="1" value="1" class="w-full px-3 py-2 border border-slate-200 rounded-xl">
                                    </div>
                                </div>
                            </div>

                            <button type="button" onclick="adicionarProduto()" class="px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all">
                                Adicionar Produto à Tabela
                            </button>

                            <div class="mt-6 overflow-x-auto">
                                <table class="w-full text-sm text-left text-slate-700 responsive-table">
                                    <thead class="bg-slate-100">
                                    <tr>
                                        <th class="px-4 py-2">Produto</th>
                                        <th class="px-4 py-2">Quantidade</th>
                                        <th class="px-4 py-2">Preço Unitário</th>
                                        <th class="px-4 py-2">Total</th>
                                        <th class="px-4 py-2">Remover</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tabela-produtos">
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-slate-400" data-label="Produtos">Nenhum produto selecionado</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" onclick="salvarEntrada()" class="mt-4 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-all">
                                Salvar Entrada
                            </button>
                        </form>
                    </div>
                </div>

            @else
                {{--CAIXA FECHADO--}}
                <div style="
                margin:40px auto;
                max-width:600px;
                background:linear-gradient(135deg,#1e293b,#0f172a);
                border-radius:24px;
                padding:48px;
                text-align:center;
                color:white;
                box-shadow:0 30px 80px rgba(0,0,0,.5);
            ">
                    <div style="font-size:64px; margin-bottom:20px;">🔒</div>

                    <h1 style="font-family:Outfit; font-size:32px; font-weight:900;">
                        Caixa Fechado
                    </h1>

                    <p style="margin-top:12px; font-size:16px; opacity:.9;">
                        Para começar a vender, abra o caixa na aba (CAIXA).
                    </p>

                    <div style="margin-top:32px; display:flex; justify-content:center; gap:12px;">
                    <span style="
                        background:rgba(255,255,255,.1);
                        padding:10px 18px;
                        border-radius:999px;
                        font-family:Outfit;
                        font-weight:700;
                        font-size:13px;
                    ">
                        Sistema bloqueado
                    </span>
                        <span style="
                            background:#ff0000;
                            padding:10px 18px;
                            border-radius:999px;
                            font-family:Outfit;
                            font-weight:800;
                            font-size:13px;
                        ">
                            OFF-LINE
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <script>
            // Funções já existentes
            const tabela = document.getElementById('tabela-produtos');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function mostrarSucesso(msg) { Swal.fire({ icon:'success', title:msg, showConfirmButton:true, position:'center' }); }
            function mostrarErro(msg) { Swal.fire({ icon:'error', title:msg, showConfirmButton:true, position:'center' }); }

            function adicionarProduto() {
                const select = document.getElementById('select-produto');
                const quantidadeInput = document.getElementById('quantidade-produto');
                const selectedOption = select.selectedOptions[0];
                const id = select.value;
                const categoria = selectedOption?.dataset.categoria;
                const nome = selectedOption?.dataset.nome;
                const valor = parseFloat(selectedOption?.dataset.valor || 0);
                const quantidade = parseInt(quantidadeInput.value || 1);

                if(!id) return mostrarErro("Selecione um produto!");
                if(tabela.querySelector('td[colspan="5"]')) tabela.innerHTML = '';

                const total = (valor * quantidade).toFixed(2).replace('.', ',');
                const linha = document.createElement('tr');
                linha.classList.add('border-b','hover:bg-slate-50','product-row');
                linha.innerHTML = `
                <td class="px-4 py-2" data-label="Produto">
                    ${nome}
                    <input type="hidden" name="produtos[]" value="${id}">
                    <input type="hidden" name="categorias[]" value="${categoria}">
                </td>
                <td class="px-4 py-2" data-label="Quantidade"><input type="hidden" name="quantidades[]" value="${quantidade}">${quantidade}</td>
                <td class="px-4 py-2" data-label="Preço Unitário"><input type="hidden" name="valores_unitarios[]" value="${valor}">R$ ${valor.toFixed(2).replace('.',',')}</td>
                <td class="px-4 py-2" data-label="Total">R$ ${total}</td>
                <td class="px-4 py-2" data-label="Remover"><button type="button" onclick="removerLinha(this)" class="text-red-500 font-semibold">Remove</button></td>
            `;
                tabela.appendChild(linha);
                select.value=''; quantidadeInput.value=1;
            }

            function removerLinha(btn) {
                btn.closest('tr').remove();
                if(!tabela.querySelector('tr')) {
                    tabela.innerHTML=`<tr><td colspan="5" class="px-4 py-2 text-center text-slate-400">Nenhum produto selecionado</td></tr>`;
                }
            }

            async function salvarEntrada() {
                const produtos = Array.from(document.getElementsByName('produtos[]')).map(i => i.value);
                const nomes = Array.from(document.querySelectorAll('#tabela-produtos td input[name="produtos[]"]'))
                    .map(td => td.closest('tr').querySelector('td:first-child').textContent.trim());
                const quantidades = Array.from(document.getElementsByName('quantidades[]')).map(i => parseInt(i.value));
                const valores_unitarios = Array.from(document.getElementsByName('valores_unitarios[]')).map(i => parseFloat(i.value));

                if(produtos.length === 0) return mostrarErro('Adicione pelo menos um produto!');

                // Monta array 'itens' para o Laravel
                const itens = produtos.map((id, index) => ({
                    id: id,
                    nome: nomes[index],
                    qtd: quantidades[index],
                    valor: valores_unitarios[index]
                }));

                try {
                    const response = await axios.post("{{ route('entradas.store') }}",
                        { itens, observacao: '' },
                        { headers: { 'X-CSRF-TOKEN': token } }
                    );
                    mostrarSucesso(response.data.message);

                    // Limpa tabela
                    tabela.innerHTML = `<tr><td colspan="5" class="px-4 py-2 text-center text-slate-400">Nenhum produto selecionado</td></tr>`;
                } catch(err) {
                    console.error(err);
                    mostrarErro(err.response?.data?.message || 'Erro ao salvar entrada');
                }
            }

        </script>
    @endsection
