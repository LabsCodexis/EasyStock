@extends('layout')

@section('conteudo')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap');

        body {
            box-sizing: border-box;
        }



        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body, #app-wrapper {
            height: 100%;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }

        .input-modern {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid #e2e8f0;
        }

        .input-modern:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: #64748b;
            outline: none;
        }

        .btn-primary {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:active::before {
            width: 300px;
            height: 300px;
        }

        .product-item {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .product-item:hover {
            transform: translateX(4px);
            background: rgba(0, 0, 0, 0.02);
            border-left-color: #1e293b;
        }

        .cart-empty {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .status-badge {
            animation: pulse-subtle 2s ease-in-out infinite;
        }

        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .number-display {
            font-family: 'JetBrains Mono', monospace;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.05em;
        }

        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 20px 32px;
            border-radius: 16px;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.25);
            transform: translateX(500px);
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 16px;
            max-width: 400px;
            backdrop-filter: blur(20px);
        }

        .toast.show {
            transform: translateX(0);
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9000;
            padding: 24px;
            overflow-y: auto;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            max-width: 600px;
            width: 100%;
            max-height: 90%;
            overflow-y: auto;
            border-radius: 24px;
            animation: modalSlide 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.3);
        }

        @keyframes modalSlide {
            from {
                opacity: 0;
                transform: scale(0.8) translateY(-50px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .grid-layout {
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

        .scrollbar-custom::-webkit-scrollbar {
            width: 8px;
        }

        .scrollbar-custom::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .scrollbar-custom::-webkit-scrollbar-thumb {
            background: rgba(30, 41, 59, 0.3);
            border-radius: 10px;
        }

        .scrollbar-custom::-webkit-scrollbar-thumb:hover {
            background: rgba(30, 41, 59, 0.5);
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border-radius: 12px;
            padding: 4px;
            border: 2px solid #e2e8f0;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            background: #f1f5f9;
            color: #475569;
        }

        .quantity-btn:hover {
            background: #1e293b;
            color: white;
            transform: scale(1.1);
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }

        .loading-spinner {
            border: 3px solid rgba(30, 41, 59, 0.1);
            border-top: 3px solid #1e293b;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .product-catalog {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .product-card-catalog {
            background: white;
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
            text-align: center;
        }

        .product-card-catalog:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            border-color: #1e293b;
        }

        .product-card-catalog:active {
            transform: translateY(-2px);
        }
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap');
    </style>

    {{--Cabeçalho--}}
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

    <div id="app-wrapper" class="w-full h-full overflow-auto" style="background-color: #f8fafc;">
        <div class="gradient-bg rounded-4 p-4 p-md-5 mb-4 shadow-lg text-white position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 rounded-circle" style="width: 16rem; height: 16rem; background: rgba(255,255,255,0.05); transform: translate(8rem, -8rem);"></div>
            <div class="position-absolute bottom-0 start-0 rounded-circle" style="width: 12rem; height: 12rem; background: rgba(255,255,255,0.05); transform: translate(-6rem, 6rem);"></div>
            <div class="position-relative d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 border border-white border-opacity-25 shadow" style="width: 64px; height: 64px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                        <i class="bi bi-box-seam fs-1 text-white"></i>
                    </div>
                    <div>
                        <h1 class="h3 fw-bold mb-1">Easy Stock</h1>
                        <p class="mb-0 text-white-50 small">Desenvolvido por Labs Codexis</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($caixaStatus && $caixaStatus->status === 'aberto')
                        <form action="{{ route('caixa.fechar') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="status-badge"
                                    style="background: #22c55e; color: white; padding: 8px 20px; border-radius: 50px;
                                       font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700;
                                       display: flex; align-items: center; gap: 8px;
                                       box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);">
                                <span style="width: 8px; height: 8px; background: white; border-radius: 50%;"></span> CAIXA ABERTO
                            </button>
                        </form>
                    @else
                        <form action="{{ route('caixa.abrir') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="saldo_inicial" value="0">
                            <button type="submit" class="status-badge"
                                style="background: #ff0000; color: white; padding: 8px 20px; border-radius: 50px;
                                   font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700;
                                   display: flex; align-items: center; gap: 8px;
                                   box-shadow: 0 4px 12px rgb(210,34,34);">
                                <span style="width: 8px; height: 8px; background: white; border-radius: 50%;"></span> CAIXA FECHADO
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div style="padding: 0 16px 16px;">
            @if($caixaAberto)
            <div class="grid-layout">
                <div class="glass-card scrollbar-custom" style=" width: 1000px; padding: 24px; border-radius: 20px; overflow-y: auto;">
                    <div style="margin-bottom: 24px;">
                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; margin: 0 0 8px 0; color: #0f172a;">Produtos Disponíveis</h2>
                        <p style="font-family: 'Outfit', sans-serif; font-size: 14px; color: #64748b; margin: 0;">Clique no produto para adicionar à venda</p>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
                        <form method="GET" action="{{ route('caixa.index') }}" class="flex flex-col md:flex-row md:items-center md:justify-between w-full gap-4">
                            <div class="d-flex mb-3 flex-wrap gap-3">
                                <div class="p-2 flex-1 min-w-[250px]">
                                    <div class="flex items-center gap-2 w-full border rounded-lg p-2">
                                        <i class="bi bi-search text-gray-400"></i>
                                        <input
                                            type="text"
                                            name="search"
                                            value="{{ request('search') }}"
                                            placeholder="Buscar produto..."
                                            class="flex-1 outline-none border-none bg-transparent"
                                        >
                                    </div>
                                </div>

                                <div class="p-2 min-w-[200px]">
                                    <div class="flex items-center gap-2 border rounded-lg p-2">
                                        <i class="bi bi-list-ul text-gray-400"></i>
                                        <select name="categoria" class="flex-1 outline-none border-none bg-transparent">
                                            <option value="">Todas categorias</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                                                    {{ $categoria }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    <i class="bi bi-funnel"></i> Aplicar
                                </button>

                                <a href="{{ route('caixa.index') }}" class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                                    <i class="bi bi-x-circle"></i> Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden w-full max-w-5xl mx-auto">
                        <div class="px-6 py-4 bg-gray-50 border-b flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                <i class="bi bi-box-seam text-blue-600"></i>
                                Produtos disponíveis para venda
                            </h2>
                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold">
                                {{ ($produtosEstoque?->count() ?? 0) }} itens
                            </span>
                        </div>

                        <div class="overflow-x-auto px-4 py-2">
                            <table class="w-full text-sm min-w-[600px]">
                                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3 text-left">Categoria</th>
                                    <th class="px-6 py-3 text-left">Produto</th>
                                    <th class="px-6 py-3 text-center">Valor Unitário</th>
                                    <th class="px-6 py-3 text-center">Estoque</th>
                                </tr>
                                </thead>
                                <tbody id="tabelaCaixa-tbody">
                                @include('caixa.partials.tabelaCaixa_table')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="glass-card" style="margin-left: -140px; padding: 24px; border-radius: 20px; display: flex; flex-direction: column; max-height: calc(100% - 32px);">
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                            <h3 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; margin: 0; color: #0f172a;">Venda Atual</h3>
                            <div style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 6px 16px; border-radius: 50px; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);">
                                #<span id="sale-number">0001</span>
                            </div>
                        </div>
                        <div>
                            <label for="customer-input" style="font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 600; color: #64748b; display: block; margin-bottom: 8px;">
                                Cliente
                            </label>
                            <input type="text" id="customer-input" placeholder="Nome do cliente (opcional)" class="input-modern" style="width: 100%; padding: 12px 16px; border-radius: 12px; font-family: 'Outfit', sans-serif; font-size: 15px; background: white;">
                        </div>
                    </div>

                    <div id="cart-container" class="scrollbar-custom" style="flex: 1; overflow-y: auto; margin-bottom: 20px; min-height: 200px;">
                        <div id="empty-cart" class="cart-empty" style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                            <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.5;">
                                🛒
                            </div>
                            <p style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 600; margin: 0;">
                                Nenhum produto adicionado
                            </p>
                            <p style="font-family: 'Outfit', sans-serif; font-size: 14px; margin: 8px 0 0 0;">
                                Clique em um produto para iniciar
                            </p>
                        </div>
                        <div id="cart-items" style="display: none;"></div>
                    </div>

                    <div style="background: white; padding: 20px; border-radius: 16px; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">

                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="font-family: 'Outfit', sans-serif; font-size: 15px; color: #64748b; font-weight: 600;">Subtotal</span>
                            <span id="subtotal-value" class="number-display" style="font-family: 'Outfit', sans-serif; font-size: 15px; color: #0f172a; font-weight: 700;">
                                R$ 0,00
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                            <label for="discount-input" style="font-family: 'Outfit', sans-serif; font-size: 15px; color: #64748b; font-weight: 600;">Desconto (%)</label>
                            <input type="number" id="discount-input" min="0" max="100" value="0" step="0.1" style="width: 100px; padding: 8px 12px; border-radius: 8px; border: 2px solid #e2e8f0; font-family: 'JetBrains Mono', monospace; font-size: 15px; font-weight: 600; text-align: right; background: white;">
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 12px;">
                            <span style="font-family: 'Outfit', sans-serif; font-size: 18px; color: white; font-weight: 800;">Total</span>
                            <span id="total-value" class="number-display" style="font-family: 'JetBrains Mono', monospace; font-size: 28px; color: white; font-weight: 900;">
                                R$ 0,00
                            </span>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="payment-method" style="font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 600; color: #64748b; display: block; margin-bottom: 8px;">Forma de Pagamento</label>
                            <select id="payment-method" class="input-modern" style="width: 100%; padding: 12px 16px; border-radius: 12px; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 600; background: white; cursor: pointer;">
                                <option value="money">💵 Dinheiro</option>
                                <option value="debit">💳 Débito</option>
                                <option value="credit">💳 Crédito</option>
                                <option value="pix">📱 PIX</option>
                            </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <a href="{{ route('caixa.index') }}" id="new-sale-btn" class="btn btn-primary" style="background: white; border: 2px solid #e2e8f0; color: #475569; padding: 16px; border-radius: 12px; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; cursor: pointer;"> 🔄 Nova Venda </a>
                        <button id="finalize-btn" class="btn-primary" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 16px; border-radius: 12px; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; cursor: pointer; border: none; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);">
                            ✓ Finalizar Venda
                        </button>
                    </div>
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
                        Para começar a vender, abra o caixa no botão acima.
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
        <div id="history-modal" class="modal">
            <div class="modal-content glass-card" style="max-width: 900px;">
                <div style="padding: 32px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; margin: 0; color: #0f172a;">Histórico de Vendas</h3><button id="close-history-modal" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #64748b; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 12px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='transparent'">×</button>
                    </div>
                    <div id="history-content" class="scrollbar-custom" style="max-height: 500px; overflow-y: auto;"></div>
                </div>
            </div>
        </div>
        <div id="toast" class="toast" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white;">
            <div style="font-size: 28px;" id="toast-icon">
                ✓
            </div>
            <div>
                <div id="toast-message" style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; margin: 0;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        /* ============================
           CONFIG AXIOS + CSRF
        ============================ */
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        axios.defaults.headers.common['Accept'] = 'application/json';

        /* ============================
           ESTADO DA VENDA
        ============================ */
        let saleCounter = 1;
        let allSales = [];

        let currentSale = {
            items: [],
            subtotal: 0,
            discount: 0,
            total: 0,
            customer: '',
            payment: 'money'
        };

        /* ============================
           ALERTAS SWEETALERT
        ============================ */
        function alertSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: message,
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        }

        function alertError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        }

        /* ============================
           ADICIONAR PRODUTO
        ============================ */
        function addFromTable(id, nome, valor, categoria) {
            const existing = currentSale.items.find(item => item.id === id);

            if (existing) {
                existing.quantity++;
            } else {
                currentSale.items.push({
                    id: id,
                    name: nome,
                    price: parseFloat(valor),
                    quantity: 1,
                    category: categoria
                });
            }

            console.log('🛒 Produto adicionado:', currentSale.items);
            updateCart();
        }

        /* ============================
           UTILIDADES
        ============================ */
        function showToast(message, icon = '✓') {
            const toast = document.getElementById('toast');
            toast.querySelector('#toast-message').textContent = message;
            toast.querySelector('#toast-icon').textContent = icon;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function formatCurrency(value) {
            return `R$ ${value.toFixed(2).replace('.', ',')}`;
        }

        /* ============================
           CARRINHO
        ============================ */
        function updateCart() {
            const cartItems = document.getElementById('cart-items');
            const emptyCart = document.getElementById('empty-cart');

            if (currentSale.items.length === 0) {
                emptyCart.style.display = 'block';
                cartItems.style.display = 'none';
                currentSale.subtotal = 0;
            } else {
                emptyCart.style.display = 'none';
                cartItems.style.display = 'block';

                cartItems.innerHTML = currentSale.items.map(item => `
                    <div class="product-item" style="background:linear-gradient(145deg,#ffffff,#f8fafc);padding:16px;border-radius:16px;margin-bottom:12px;display:flex;gap:12px;align-items:center;border:2px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);transition:all 0.3s ease">
                        <div style="width:48px;height:48px;background:linear-gradient(135deg,#1e293b,#0f172a);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:20px;font-weight:800;flex-shrink:0">
                            ${item.category.charAt(0).toUpperCase()}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="font-family:'Outfit',sans-serif;font-size:15px;font-weight:700;color:#0f172a;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                ${item.category} ${item.name}
                            </div>
                            <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#64748b;font-weight:600">
                                ${formatCurrency(item.price)} × ${item.quantity} = ${formatCurrency(item.price * item.quantity)}
                            </div>
                        </div>
                        <div class="quantity-control" style="display:flex;align-items:center;gap:8px;background:white;border-radius:12px;padding:6px;border:2px solid #e2e8f0;flex-shrink:0">
                            <button onclick="decreaseQuantity(${item.id})" class="quantity-btn" style="width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;font-weight:700;font-size:18px;display:flex;align-items:center;justify-content:center;transition:all 0.2s ease;background:#f1f5f9;color:#475569">−</button>
                            <span style="font-family:'JetBrains Mono',monospace;font-size:15px;font-weight:700;color:#0f172a;min-width:32px;text-align:center">${item.quantity}</span>
                            <button onclick="increaseQuantity(${item.id})" class="quantity-btn" style="width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;font-weight:700;font-size:18px;display:flex;align-items:center;justify-content:center;transition:all 0.2s ease;background:#f1f5f9;color:#475569">+</button>
                        </div>
                        <button onclick="removeItem(${item.id})" style="width:40px;height:40px;border-radius:10px;border:none;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;transition:all 0.2s ease;background:#fee2e2;color:#dc2626;flex-shrink:0" onmouseover="this.style.background='#dc2626';this.style.color='white'" onmouseout="this.style.background='#fee2e2';this.style.color='#dc2626'">🗑️</button>
                    </div>
            `).join('');

                currentSale.subtotal = currentSale.items.reduce(
                    (sum, i) => sum + (i.price * i.quantity), 0
                );
            }

            calculateTotal();
        }

        function increaseQuantity(id) {
            const item = currentSale.items.find(i => i.id === id);
            if (item) item.quantity++;
            updateCart();
        }

        function decreaseQuantity(id) {
            const item = currentSale.items.find(i => i.id === id);
            if (item && item.quantity > 1) item.quantity--;
            updateCart();
        }

        function removeItem(id) {
            currentSale.items = currentSale.items.filter(i => i.id !== id);
            updateCart();
        }

        /* ============================
           TOTAIS
        ============================ */
        function calculateTotal() {
            const discountPercent =
                parseFloat(document.getElementById('discount-input').value) || 0;

            currentSale.discount = currentSale.subtotal * (discountPercent / 100);
            currentSale.total = currentSale.subtotal - currentSale.discount;

            document.getElementById('subtotal-value').textContent =
                formatCurrency(currentSale.subtotal);

            document.getElementById('total-value').textContent =
                formatCurrency(currentSale.total);
        }

        /* ============================
           FINALIZAR VENDA (AXIOS)
        ============================ */
        async function finalizeSale() {
            if (currentSale.items.length === 0) {
                alertError('Carrinho vazio');
                console.warn('⚠️ Carrinho vazio');
                return;
            }

            const sale = {
                sale_id: `SALE${String(saleCounter).padStart(4, '0')}`,
                items: currentSale.items,
                subtotal: currentSale.subtotal,
                discount: currentSale.discount,
                total: currentSale.total,
                payment_method: document.getElementById('payment-method').value,
                customer_name:
                    document.getElementById('customer-input').value || 'Cliente Anônimo'
            };

            console.log('📤 Enviando venda:', sale);

            try {
                const response = await axios.post('/caixa/store', sale);

                console.log('✅ Sucesso:', response.data);

                if (response.data.success) {
                    alertSuccess('Venda finalizada 🎉');
                    showToast('Venda finalizada 🎉');

                    saleCounter++;
                    resetSale();
                    loadSalesFromAPI();
                } else {
                    alertError(response.data.message || 'Erro ao salvar venda');
                    console.error('❌ Erro lógico:', response.data);
                }

            } catch (error) {
                console.error('🔥 Erro Axios:', error);

                if (error.response) {
                    console.error('📄 Response:', error.response.data);
                    alertError(error.response.data.message || 'Erro no servidor');
                } else {
                    alertError('Erro de conexão com o servidor');
                }
            }
        }

        /* ============================
           RESET VENDA
        ============================ */
        function resetSale() {
            currentSale = {
                items: [],
                subtotal: 0,
                discount: 0,
                total: 0
            };

            document.getElementById('customer-input').value = '';
            document.getElementById('discount-input').value = '0';

            updateCart();
        }

        /* ============================
           CARREGAR VENDAS
        ============================ */
        async function loadSalesFromAPI() {
            try {
                const res = await axios.get('/venda');

                console.log('📥 Vendas:', res.data);

                if (res.data.success && res.data.data.length) {
                    const max = Math.max(
                        ...res.data.data.map(v =>
                            parseInt(v.sale_id.replace('SALE', ''))
                        )
                    );

                    saleCounter = max + 1;
                    document.getElementById('sale-number').textContent =
                        String(saleCounter).padStart(4, '0');
                }
            } catch (error) {
                console.error('Erro ao carregar vendas:', error);
                alertError('Erro ao carregar vendas');
            }
        }

        /* ============================
           EVENTOS
        ============================ */
        document.getElementById('discount-input')
            .addEventListener('input', calculateTotal);

        document.getElementById('finalize-btn')
            .addEventListener('click', finalizeSale);

        document.getElementById('new-sale-btn')
            .addEventListener('click', resetSale);

        loadSalesFromAPI();
    </script>


@endsection

