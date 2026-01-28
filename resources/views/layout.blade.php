<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Controle de Estoque e Caixa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Vuetify CSS e JS -->
    <!-- Vue 3 -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

    <!-- Vuetify 3 -->
    <link href="https://cdn.jsdelivr.net/npm/vuetify@3.5.14/dist/vuetify.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/vuetify@3.5.14/dist/vuetify.min.js"></script>

    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <style>
        @view-transition { navigation: auto; }

        @media (max-width: 768px) {
            .responsive-table {
                width: 100% !important;
                min-width: 0 !important;
            }

            .responsive-table thead {
                display: none;
            }

            .responsive-table,
            .responsive-table tbody,
            .responsive-table tr,
            .responsive-table td {
                display: block;
                width: 100%;
            }

            .responsive-table tr {
                margin-bottom: 12px;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                padding: 8px;
                background: #ffffff;
            }

            .responsive-table td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
                padding: 8px 12px;
                text-align: left;
                border: 0;
                word-break: break-word;
            }

            .responsive-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                text-align: left;
                padding-right: 12px;
                flex: 0 0 42%;
            }

            .responsive-table td > * {
                max-width: 100%;
            }

            .responsive-table td[data-label="Ações"] {
                justify-content: space-between;
            }

            .responsive-table td[data-label="Ações"]::before {
                margin-right: 0;
            }
        }
    </style>


</head>
<body class="bg-gray-100 text-gray-900 overflow-x-hidden">

<div class="min-h-screen flex">

    <!-- Menu lateral -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white flex flex-col p-4 transform -translate-x-full transition-transform duration-200 ease-out lg:static lg:translate-x-0">
        <!-- Logo e Nome do Sistema -->
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold flex items-center justify-center gap-2">
                <i class="bi bi-box-seam"></i> Easy Stock
            </h1>
            <p class="text-sm text-gray-400 mt-1">Desenvolvido por LabsCodexis</p>
        </div>

        <!-- Menu de Navegação -->
        <nav class="flex-1 space-y-2">

            <a href="{{ route('entradas.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 transition">
                <i class="bi bi-box-arrow-in-down text-lg"></i>
                Entrada de Produto
            </a>

            <a href="{{ route('estoque.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 transition">
                <i class="bi bi-stack text-lg"></i>
                Estoque
            </a>

            <a href="{{ route('caixa.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 transition">
                <i class="bi bi-cash-stack text-lg"></i>
                Caixa
            </a>

            <a href="{{ route('relatorios.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 transition">
                <i class="bi bi-file-earmark-text text-lg"></i>
                Relatório
            </a>
        </nav>

        <!-- Rodapé opcional -->
        <div class="mt-auto text-gray-400 text-sm text-center pt-4 border-t border-gray-700">
            &copy; 2026 LabsCodexis
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-200 lg:hidden"></div>

    <div class="flex-1 min-w-0">
        <!-- Top bar mobile -->
        <header class="lg:hidden sticky top-0 z-30 bg-gray-900 text-white px-4 py-3 flex items-center gap-3 shadow">
            <button id="sidebar-toggle" class="p-2 rounded-md bg-white/10 hover:bg-white/20 transition" type="button" aria-label="Abrir menu">
                <i class="bi bi-list text-xl"></i>
            </button>
            <div class="font-semibold">Easy Stock</div>
        </header>

        <!-- Conteúdo principal -->
        <main class="p-4 sm:p-6 lg:p-6">
            @yield('conteudo')
        </main>
    </div>

</div>

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('sidebar-toggle');

        if (!sidebar || !overlay || !toggle) return;

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0', 'pointer-events-none');
        };

        toggle.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);
        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeSidebar();
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    })();
</script>

</body>
</html>
