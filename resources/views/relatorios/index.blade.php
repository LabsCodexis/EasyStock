    @extends('layout')

    @section('conteudo')
        <style>
            body {
                box-sizing: border-box;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            html, body {
                height: 100%;
                width: 100%;
            }

            body {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background: #f8fafc;
                color: #0f172a;
                overflow-x: hidden;
            }

            .dashboard-wrapper {
                width: 100%;
                height: 100%;
                overflow-y: auto;
                overflow-x: hidden;
            }

            .dashboard-container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 2rem;
            }

            .header {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                border-radius: 20px;
                padding: 2.5rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 40px rgba(15, 23, 42, 0.2);
            }

            .header-title {
                font-size: 2rem;
                font-weight: 800;
                color: white;
                margin-bottom: 0.5rem;
            }

            .header-subtitle {
                color: #94a3b8;
                font-size: 0.95rem;
            }

            .filters-section {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
                border: 1px solid #e2e8f0;
            }

            .filters-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #0f172a;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .filters-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }

            .filter-input {
                padding: 0.875rem 1rem;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                background: white;
                color: #0f172a;
            }

            .filter-input:focus {
                outline: none;
                border-color: #1e293b;
                box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
            }

            .btn {
                padding: 0.875rem 1.5rem;
                border: none;
                border-radius: 12px;
                font-weight: 600;
                font-size: 0.95rem;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                text-decoration: none;
            }

            .btn-primary {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                color: white;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(15, 23, 42, 0.3);
            }

            .btn-danger {
                background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                color: white;
            }

            .btn-danger:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
            }

            .btn-success {
                background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
                color: white;
            }

            .btn-success:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
            }

            .kpi-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .kpi-card {
                background: white;
                border-radius: 20px;
                padding: 1.75rem;
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
                border: 1px solid #e2e8f0;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .kpi-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%);
            }

            .kpi-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(15, 23, 42, 0.15);
            }

            .kpi-label {
                font-size: 0.85rem;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .kpi-value {
                font-size: 2rem;
                font-weight: 800;
                color: #0f172a;
                line-height: 1.2;
            }

            .kpi-value.green {
                color: #16a34a;
            }

            .kpi-value.red {
                color: #dc2626;
            }

            .kpi-value.blue {
                color: #2563eb;
            }

            .kpi-value.purple {
                color: #9333ea;
            }

            .kpi-icon {
                font-size: 2.5rem;
                position: absolute;
                right: 1.5rem;
                top: 50%;
                transform: translateY(-50%);
                opacity: 0.1;
            }

            .charts-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
                gap: 2rem;
                margin-bottom: 2rem;
            }

            .chart-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
                border: 1px solid #e2e8f0;
            }

            .chart-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #0f172a;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .table-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
                border: 1px solid #e2e8f0;
                overflow-x: auto;
            }

            .data-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .data-table thead th {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                color: white;
                padding: 1rem;
                text-align: left;
                font-weight: 600;
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .data-table thead th:first-child {
                border-top-left-radius: 12px;
            }

            .data-table thead th:last-child {
                border-top-right-radius: 12px;
            }

            .data-table tbody tr {
                transition: all 0.2s ease;
            }

            .data-table tbody tr:hover {
                background: #f8fafc;
            }

            .data-table tbody td {
                padding: 1rem;
                border-bottom: 1px solid #e2e8f0;
                color: #1e293b;
            }

            .data-table tbody tr:last-child td {
                border-bottom: none;
            }

            .badge {
                display: inline-block;
                padding: 0.375rem 0.875rem;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .badge-success {
                background: #dcfce7;
                color: #166534;
            }

            .badge-warning {
                background: #fef3c7;
                color: #92400e;
            }

            .badge-info {
                background: #dbeafe;
                color: #1e40af;
            }

            .empty-state {
                text-align: center;
                padding: 3rem;
                color: #64748b;
            }

            .empty-state-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            @media (max-width: 768px) {
                .dashboard-container {
                    padding: 1rem;
                }

                .header-title {
                    font-size: 1.5rem;
                }

                .kpi-grid {
                    grid-template-columns: 1fr;
                }

                .charts-grid {
                    grid-template-columns: 1fr;
                }

                .filters-grid {
                    grid-template-columns: 1fr;
                }
        </style>

        <style>
            .rank-badge {
                font-weight: 700;
            }
            .rank-1 { color: #16a34a; }
            .rank-2 { color: #2563eb; }
            .rank-3 { color: #9333ea; }

            .product-cell {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .product-avatar {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background: linear-gradient(135deg, #0f172a, #1e293b);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
            }

            .product-info small {
                display: block;
                font-size: 0.75rem;
                color: #64748b;
            }

            .progress-cell strong {
                font-size: 0.9rem;
            }

            .product-info .category {
                display: block;
                font-size: 0.7rem;
                color: #94a3b8;
                text-transform: uppercase;
                margin-bottom: 2px;
            }


        </style>


        <style>@view-transition { navigation: auto; }</style>
        <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        </head>
            <body style="background: linear-gradient(135deg, #ececec 0%, #dfdfe2 100%);">
        <div class="dashboard-wrapper">
            <div class="dashboard-container">

                <div class="header">
                    <h1 class="header-title">📊 Relatórios Gerenciais</h1>
                    <p class="header-subtitle">Análise completa de desempenho e resultados</p>
                </div>

                <div class="filters-section">
                    <h3 class="filters-title">🔍 Filtros de Período</h3>

                    <form method="GET" action={{ route('relatorios.index') }}>
                        <div class="filters-grid">

                            <input
                                type="date"
                                name="data_inicio"
                                class="filter-input"
                                value="{{ $dataInicioInput ?? '' }}"
                                required
                            >

                            <input
                                type="date"
                                name="data_fim"
                                class="filter-input"
                                value="{{ $dataFimInput ?? ''}}"
                                required
                            >

                            <button type="submit" class="btn btn-primary">
                                <span>🔎</span> Filtrar
                            </button>

                            <a
                                href="/relatorios/pdf?data_inicio={{ $inicio ?? '' }}&data_fim={{ $fim ?? '' }}"
                                class="btn btn-danger"
                                target="_blank"
                            >
                                <span>📄</span> Exportar PDF
                            </a>

                            <a
                                href="/relatorios/excel?data_inicio={{ $inicio?->format('Y-m-d') }}&data_fim={{ $fim?->format('Y-m-d') }}"
                                class="btn btn-success"
                                target="_blank"
                            >
                                <span>📊</span> Exportar Excel
                            </a>

                        </div>
                    </form>
                </div>

                <div class="kpi-grid">

                    <div class="kpi-card">
                        <div class="kpi-icon">💰</div>
                        <div class="kpi-label">Faturamento Total</div>
                        <div class="kpi-value green">
                            R$ {{ number_format($faturamento ?? 0, 2, ',', '.') }}
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon">🛒</div>
                        <div class="kpi-label">Total de Vendas</div>
                        <div class="kpi-value">
                            {{ $qtdVendas ?? 0 }}
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon">📦</div>
                        <div class="kpi-label">Itens Vendidos</div>
                        <div class="kpi-value">
                            {{ $itensVendidos ?? 0 }}
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon">📈</div>
                        <div class="kpi-label">Entradas Estoque</div>
                        <div class="kpi-value blue">
                            R$ {{ number_format($entradas ?? 0, 2, ',', '.') }}
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon">📉</div>
                        <div class="kpi-label">Saídas Estoque</div>
                        <div class="kpi-value red">
                            R$ {{ number_format($saidas ?? 0, 2, ',', '.') }}
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon">🎯</div>
                        <div class="kpi-label">Ticket Médio</div>
                        <div class="kpi-value purple">
                            R$ {{ number_format($ticketMedio ?? 0, 2, ',', '.') }}
                        </div>
                    </div>

                </div>

                {{-- MOVIMENTAÇÕES DE ESTOQUE --}}

                <div id="relatorios-app-mov">
                    <v-card class="mb-6" height="400">
                        <v-card-title>📦 Movimentações de Estoque</v-card-title>
                        <v-data-table
                            :items="movimentacoes"
                            :loading="loadingMov"
                            :server-items-length="totalMovimentacoes"
                            :items-per-page="20"
                            :page.sync="pageMov"
                            fixed-header
                            height="350"
                            @update:page="fetchMovimentacoes"
                        >
                            <!-- Template do tipo -->
                            <template #item.tipo="{ item }">
                                <v-chip :color="item.tipo === 'entrada' ? 'green' : 'red'" dark>
                                    <span v-text="item.tipo === 'entrada' ? 'Entrada' : 'Saída'"></span>
                                </v-chip>
                            </template>

                        </v-data-table>
                    </v-card>
                </div>

                <div class="charts-grid mt-3">
                    <div class="chart-card">
                        <h3 class="chart-title">📈 Faturamento Diário</h3>

                        @if(isset($faturamentoDiario) && count($faturamentoDiario) > 0)
                            <canvas id="graficoFaturamento"></canvas>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">📊</div>
                                <p>Nenhum dado disponível para o período selecionado</p>
                            </div>
                        @endif
                    </div>

                    <div class="chart-card">
                        <h3 class="chart-title">💳 Formas de Pagamento</h3>

                        @if(isset($formasPagamento) && count($formasPagamento) > 0)
                            <canvas id="graficoPagamento"></canvas>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">💳</div>
                                <p>Nenhum dado disponível para o período selecionado</p>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- TABELA PRODUTOS --}}

{{--                <div id="relatorios-app-prod" class="table-card premium-table">--}}
{{--                    <v-card elevation="0">--}}
{{--                        <v-card-title class="table-header">--}}
{{--                            <div>--}}
{{--                                <h3>🔥 Produtos Mais Vendidos</h3>--}}
{{--                                <span class="subtitle">Ranking por volume no período</span>--}}
{{--                            </div>--}}

{{--                            <v-chip color="deep-purple" variant="tonal">--}}
{{--                                TOP @{{ totalProdutos }}--}}
{{--                            </v-chip>--}}
{{--                        </v-card-title>--}}

{{--                        <v-data-table--}}
{{--                            :items="produtos"--}}
{{--                            :loading="loadingProd"--}}
{{--                            :items-per-page="10"--}}
{{--                            fixed-header--}}
{{--                            height="340"--}}
{{--                            class="modern-table"--}}
{{--                        >--}}
{{--                            <!-- Coluna: Rank -->--}}
{{--                            <template #item.rank="{ index }">--}}
{{--                                <div class="rank-badge" :class="'rank-' + (index + 1)">--}}
{{--                                    #@{{ index + 1 }}--}}
{{--                                </div>--}}
{{--                            </template>--}}

{{--                            <!-- Coluna: Categoria + Produto -->--}}
{{--                            <template #item.nome="{ item }">--}}
{{--                                <div class="product-cell">--}}
{{--                                    <div class="product-avatar">--}}
{{--                                        📦--}}
{{--                                    </div>--}}

{{--                                    <div class="product-info">--}}
{{--                                        <small class="category">@{{ item.categoria }}</small>--}}
{{--                                        <strong>@{{ item.nome }}</strong>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </template>--}}

{{--                            <!-- Coluna: Quantidade com barra de progresso -->--}}
{{--                            <template #item.quantidade="{ item }">--}}
{{--                                <div class="progress-cell">--}}
{{--                                    <strong>@{{ item.quantidade }}</strong>--}}
{{--                                    <v-progress-linear--}}
{{--                                        :model-value="item.percentual || 0"--}}
{{--                                        height="6"--}}
{{--                                        rounded--}}
{{--                                        color="green"--}}
{{--                                    />--}}
{{--                                </div>--}}
{{--                            </template>--}}

{{--                            <!-- Coluna: Status (badge) -->--}}
{{--                            <template #item.status="{ index }">--}}
{{--                                <v-chip--}}
{{--                                    size="small"--}}
{{--                                    variant="elevated"--}}
{{--                                    :color="index < 3 ? 'green' : index < 7 ? 'blue' : 'orange'"--}}
{{--                                >--}}
{{--                                    <span v-if="index < 3">🔥 Em Alta</span>--}}
{{--                                    <span v-else-if="index < 7">📊 Estável</span>--}}
{{--                                    <span v-else>⚠️ Atenção</span>--}}
{{--                                </v-chip>--}}
{{--                            </template>--}}
{{--                        </v-data-table>--}}
{{--                    </v-card>--}}
{{--                </div>--}}


            </div>
        <script>
            const chartColors = {
                primary: '#0f172a',
                secondary: '#1e293b',
                success: '#16a34a',
                danger: '#dc2626',
                warning: '#eab308',
                info: '#2563eb'
            };

            @if(isset($faturamentoDiario) && count($faturamentoDiario) > 0)
            const ctxFaturamento = document.getElementById('graficoFaturamento');
            if (ctxFaturamento) {
                new Chart(ctxFaturamento.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($faturamentoDiario->pluck('data')->map(function($d) {
                            return \Carbon\Carbon::parse($d)->format('d/m');
                        })) !!},
                        datasets: [{
                            label: 'Faturamento (R$)',
                            data: {!! json_encode($faturamentoDiario->pluck('total')) !!},
                            borderColor: chartColors.primary,
                            backgroundColor: 'rgba(15, 23, 42, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'R$ ' + value.toLocaleString('pt-BR');
                                    }
                                }
                            }
                        }
                    }
                });
            }
            @endif

            @if(isset($formasPagamento) && count($formasPagamento) > 0)
            const ctxPagamento = document.getElementById('graficoPagamento');
            if (ctxPagamento) {
                new Chart(ctxPagamento.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($formasPagamento->pluck('forma_pagamento')) !!},
                        datasets: [{
                            data: {!! json_encode($formasPagamento->pluck('total')) !!},
                            backgroundColor: [
                                chartColors.primary,
                                chartColors.success,
                                chartColors.info,
                                chartColors.warning,
                                chartColors.danger,
                                chartColors.secondary
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
            @endif

            // Animação de entrada dos KPIs
            window.addEventListener('load', () => {
                const kpiCards = document.querySelectorAll('.kpi-card');
                kpiCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });

            function setupInfiniteScroll(tbodyId, routeName, page = 1) {
                let loading = false;
                const tbody = document.getElementById(tbodyId);

                window.addEventListener('scroll', () => {
                    if (loading) return;

                    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
                        loading = true;
                        page++;

                        fetch(`${routeName}?data_inicio={{ $inicio }}&data_fim={{ $fim }}&page=${page}`)
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const novasLinhas = doc.querySelectorAll(`#${tbodyId} tr`);

                                if (novasLinhas.length > 0) {
                                    novasLinhas.forEach(tr => tbody.appendChild(tr));
                                    loading = false;
                                }
                            })
                            .catch(() => loading = false);
                    }
                });
            }

        </script>


        <script>
            const { createApp, ref, onMounted } = Vue;
            const { createVuetify } = Vuetify;

            const appMov = createApp({
                setup() {
                    const movimentacoes = ref([]);
                    const totalMovimentacoes = ref(0);
                    const pageMov = ref(1);
                    const loadingMov = ref(false);

                    const fetchMovimentacoes = async () => {
                        loadingMov.value = true;
                        const res = await fetch(`/movimentacoes?page=${pageMov.value}&data_inicio={{ $inicio }}&data_fim={{ $fim }}`);
                        const data = await res.json();
                        movimentacoes.value = data.data;
                        totalMovimentacoes.value = data.total;

                        loadingMov.value = false;
                    };

                    onMounted(() => fetchMovimentacoes());

                    return { movimentacoes, totalMovimentacoes, pageMov, loadingMov, fetchMovimentacoes };
                }
            });
            appMov.use(createVuetify());
            appMov.mount('#relatorios-app-mov');

            // App produtos
            const appProd = createApp({
                setup() {
                    const produtos = ref([]);
                    const totalProdutos = ref(0);
                    const pageProd = ref(1);
                    const loadingProd = ref(false);

                    const fetchProdutos = async () => {
                        loadingProd.value = true;
                        const res = await fetch(`/produtos?page=${pageProd.value}&data_inicio={{ $inicio }}&data_fim={{ $fim }}`);
                        const data = await res.json();
                        produtos.value = data.data;       // array de produtos
                        totalProdutos.value = data.total; // total de produtos
                        loadingProd.value = false;
                    };

                    onMounted(() => fetchProdutos());

                    return { produtos, totalProdutos, pageProd, loadingProd, fetchProdutos };
                }
            });
            appProd.use(createVuetify());
            appProd.mount('#relatorios-app-prod');

        </script>

    @endsection
