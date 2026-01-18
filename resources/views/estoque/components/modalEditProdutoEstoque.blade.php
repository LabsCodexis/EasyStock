<!-- Modal Edit Produto -->
<html>
<head>
    <style>
        @view-transition {
            navigation: auto;
        }
    </style>
    <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
    <script src="/_sdk/element_sdk.js" type="text/javascript"></script>
    <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
</head>
<body>
<div class="modal fade" id="editProdutoModalEstoque" tabindex="-1" aria-labelledby="editProdutoLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header gradient-bg text-white border-0 px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="editProdutoLabel">Editar Produto</h5><small
                            class="text-white-50">Atualize as informações do produto</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar">

                </button>
            </div>
            <div class="modal-body p-4 p-md-5" style="background: #f8fafc;">
                <form id="editProdutoEstoqueForm">
                    @csrf @method('PUT')
                    <input type="hidden" name="id" id="edit-produto-id">
                    <div class="row g-4 mb-4">
                        <div class="mb-4 col-md-6">
                            <label for="edit-produto-nome"
                                   class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-box-seam text-success"></i>
                                Nome do Produto
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3 border-2"
                                   id="edit-produto-nome" name="nome" placeholder="Digite o nome do produto" required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="edit-produto-categoria"
                                   class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-tag text-primary"></i>
                                Categoria
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3 border-2"
                                   id="edit-produto-categoria" name="categoria" placeholder="Ex: Eletrônicos" required
                            >
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="edit-produto-quantidade"
                                   class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-stack text-warning"></i>
                                Quantidade em Estoque
                            </label>
                            <div class="input-group input-group-lg">

                                <button class="btn btn-outline-secondary rounded-start-3" type="button"
                                        onclick="changeModalQuantity(-1)">
                                    <i class="bi bi-dash"></i>
                                </button>

                                <input type="number" class="form-control text-center fw-bold border-2"
                                       id="edit-produto-quantidade" name="quantidade" min="0" required
                                >

                                <button class="btn btn-outline-secondary rounded-end-3" type="button"
                                        onclick="changeModalQuantity(1)">
                                    <i class="bi bi-plus"></i>
                                </button>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit-produto-valor"
                                   class="form-label d-flex align-items-center gap-2 fw-semibold text-muted mb-2">
                                <i class="bi bi-currency-dollar text-success"></i>
                                Valor Unitário
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-2 rounded-start-3">R$</span>
                                <input
                                    type="number" class="form-control fw-bold border-2 rounded-end-3"
                                    id="edit-produto-valor" name="valor" step="0.01" min="0" placeholder="0,00"
                                    required
                                >
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-3 pt-4 border-top">
                        <button type="button" class="btn btn-lg btn-light border-2 rounded-3 flex-fill"
                                data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-lg btn-success shadow-sm rounded-3 flex-fill">
                            <i class="bi bi-check-circle me-2"></i>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Função para incrementar/decrementar quantidade no modal
    function changeModalQuantity(delta) {
        const input = document.getElementById('edit-produto-quantidade');
        const currentValue = parseInt(input.value) || 0;
        const newValue = Math.max(0, currentValue + delta);
        input.value = newValue;
    }

    // Formatar input de valor para moeda
    document.getElementById('edit-produto-valor').addEventListener('blur', function (e) {
        if (e.target.value) {
            const value = parseFloat(e.target.value);
            e.target.value = value.toFixed(2);
        }
    });

    (function () {
        function c() {
            var b = a.contentDocument || a.contentWindow.document;
            if (b) {
                var d = b.createElement('script');
                d.innerHTML = "window.__CF$cv$params={r:'9bf7d5ee953af623',t:'MTc2ODY3MzcwMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
                b.getElementsByTagName('head')[0].appendChild(d)
            }
        }

        if (document.body) {
            var a = document.createElement('iframe');
            a.height = 1;
            a.width = 1;
            a.style.position = 'absolute';
            a.style.top = 0;
            a.style.left = 0;
            a.style.border = 'none';
            a.style.visibility = 'hidden';
            document.body.appendChild(a);
            if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else {
                var e = document.onreadystatechange || function () {
                };
                document.onreadystatechange = function (b) {
                    e(b);
                    'loading' !== document.readyState && (document.onreadystatechange = e, c())
                }
            }
        }
    })();
</script>
</body>
</html>
