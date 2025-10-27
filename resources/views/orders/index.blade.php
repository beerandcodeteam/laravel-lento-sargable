<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-semibold mb-2">Orders</h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">Lista de todos os pedidos do sistema</p>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg overflow-hidden">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Usuário</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Total</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                        @forelse($orders as $order)
                            <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                <td class="px-6 py-4 text-sm font-mono text-[#706f6c] dark:text-[#A1A09A]">
                                    #{{ $order->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#f53003] dark:bg-[#FF4433] flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $order->user->name }}</div>
                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'new' => 'bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-900',
                                            'paid' => 'bg-green-100 dark:bg-green-950 text-green-800 dark:text-green-300 border-green-200 dark:border-green-900',
                                            'shipped' => 'bg-orange-100 dark:bg-orange-950 text-orange-800 dark:text-orange-300 border-orange-200 dark:border-orange-900',
                                            'cancelled' => 'bg-red-100 dark:bg-red-950 text-red-800 dark:text-red-300 border-red-200 dark:border-red-900',
                                        ];
                                        $statusLabels = [
                                            'new' => 'Novo',
                                            'paid' => 'Pago',
                                            'shipped' => 'Enviado',
                                            'cancelled' => 'Cancelado',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border {{ $statusColors[$order->status] }}">
                                        {{ $statusLabels[$order->status] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    R$ {{ number_format($order->total_cents / 100, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#706f6c] dark:text-[#A1A09A]">
                                    Nenhum pedido encontrado
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Mostrando {{ $orders->firstItem() }} a {{ $orders->lastItem() }} de {{ number_format($orders->total()) }} pedidos
                        </div>
                        <div class="flex gap-2">
                            @if ($orders->onFirstPage())
                                <span class="px-3 py-2 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#706f6c] dark:text-[#62605b] cursor-not-allowed">
                                    Anterior
                                </span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-2 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-md transition-colors">
                                    Anterior
                                </a>
                            @endif

                            @if ($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-2 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-md transition-colors">
                                    Próximo
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#706f6c] dark:text-[#62605b] cursor-not-allowed">
                                    Próximo
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
            <p>Laravel {{ app()->version() }} | PHP {{ phpversion() }}</p>
        </div>
    </div>
</body>
</html>
