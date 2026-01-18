<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
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

    <style>@view-transition { navigation: auto; }</style>


</head>
<body class="bg-gray-100 text-gray-900">

<div class="min-h-screen flex">

        <!-- Menu lateral -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col p-4">
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


    <!-- Conteúdo principal -->
    <main class="flex-1 p-6">
        @yield('conteudo')
    </main>

</div>

</body>
</html>
