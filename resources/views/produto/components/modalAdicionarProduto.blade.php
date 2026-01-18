<div class="modal fade" id="adcProdutoModal" tabindex="-1" aria-labelledby="adcProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header gradient-bg text-white border-0 px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="adcProdutoLabel">Adicionar Produto</h5>
                        <small class="text-white-50">Preencha as informações para cadastrar um novo produto</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 p-md-5" style="background: #f8fafc;">
                <form id="adcProdutoForm">
                    @csrf <!-- Não precisa do PUT nem do id -->

                    <!-- Nome do Produto -->
                    <div class="mb-4">
                        <label for="adc-produto-nome" class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                            <i class="bi bi-box-seam text-success"></i> Nome do Produto
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3 border-2" id="adc-produto-nome" name="nome" placeholder="Digite o nome do produto" required>
                    </div>

                    <!-- Categoria e Gramatura -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="adc-produto-categoria" class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-tag text-primary"></i> Categoria
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3 border-2" id="adc-produto-categoria" name="categoria" placeholder="Ex: Eletrônicos" required>
                        </div>
                        <div class="col-md-6">
                            <label for="adc-produto-gramatura" class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-speedometer2 text-info"></i> Gramatura
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3 border-2" id="adc-produto-gramatura" name="gramatura" placeholder="Ex: 500g, 1kg">
                        </div>
                    </div>

                    <!-- Quantidade e Valor -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="adc-produto-quantidade" class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-stack text-warning"></i> Quantidade em Estoque
                            </label>
                            <div class="input-group input-group-lg">
                                <button class="btn btn-outline-secondary rounded-start-3" type="button" onclick="changeModalQuantity(-1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="form-control text-center fw-bold border-2" id="adc-produto-quantidade" name="quantidade" min="0" value="0" required>
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="changeModalQuantity(1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="adc-produto-valor" class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-currency-dollar text-success"></i> Valor Unitário
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-2 rounded-start-3">R$</span>
                                <input type="number" class="form-control fw-bold border-2 rounded-end-3" id="adc-produto-valor" name="valor" step="0.01" min="0" placeholder="0,00" required>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex flex-column flex-sm-row gap-3 pt-4 border-top">
                        <button type="button" class="btn btn-lg btn-light border-2 rounded-3 flex-fill" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-lg btn-success shadow-sm rounded-3 flex-fill">
                            <i class="bi bi-check-circle me-2"></i> Adicionar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Incrementar/decrementar quantidade
    function changeModalQuantity(delta) {
        const input = document.getElementById('adc-produto-quantidade');
        const currentValue = parseInt(input.value) || 0;
        const newValue = Math.max(0, currentValue + delta);
        input.value = newValue;
    }

    // Formatar valor
    document.getElementById('adc-produto-valor').addEventListener('blur', function(e) {
        if(e.target.value){
            const value = parseFloat(e.target.value);
            e.target.value = value.toFixed(2);
        }
    });

    // Submit via AJAX para adicionar produto
    document.getElementById('adcProdutoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;

        fetch(`/produtos`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nome: form.nome.value,
                categoria: form.categoria.value,
                quantidade: form.quantidade.value,
                valor: form.valor.value,
                gramatura: form.gramatura.value
            })
        })
            .then(async res => {
                if(!res.ok){
                    const errorData = await res.json();
                    throw errorData;
                }
                return res.json();
            })
            .then(data => {
                // Adiciona nova linha no topo da tabela
                const tbody = document.getElementById('produtos-tbody');
                const newRow = `
            <tr class="table-row-hover bg-white" data-id="${data.id}">
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.1));">
                            <i class="bi bi-box-seam text-success"></i>
                        </div>
                        <span class="fw-semibold text-dark">${data.nome}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-center"><span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2">${data.categoria}</span></td>
                <td class="px-4 py-3 text-center"><span class="badge bg-secondary bg-opacity-25 text-dark fw-semibold px-3 py-2">${data.quantidade}</span></td>
                <td class="px-4 py-3 text-center"><span class="fw-bold text-success">R$ ${Number(data.valor).toLocaleString('pt-BR',{minimumFractionDigits:2,maximumFractionDigits:2})}</span></td>
                <td class="px-4 py-3 text-center">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <a href="javascript:void(0)" onclick="openEditModal(${data.id}, '${data.nome}', '${data.categoria}', ${data.quantidade}, ${data.valor}, '${data.gramatura ?? ''}')" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center btn-action" style="width: 36px; height: 36px;"><i class="bi bi-pencil"></i></a>
                        <form action="/produtos/destroy/${data.id}" method="POST" class="d-inline delete-form">
                            @csrf @method('DELETE')
                <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center btn-action" style="width: 36px; height: 36px;"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    </td>
</tr>
`;
                tbody.insertAdjacentHTML('afterbegin', newRow);

                // Fecha modal
                const modalEl = document.getElementById('adcProdutoModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Produto adicionado com sucesso',
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(err => {
                console.error(err);
                let message = 'Não foi possível adicionar o produto';
                if(err.errors){
                    message = Object.values(err.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    html: message
                });
            });
    });
</script>
