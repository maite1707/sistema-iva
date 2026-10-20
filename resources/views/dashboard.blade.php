@php
    $monthNames = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];

    $cards = [
        ['label' => 'Ventas del mes (sin IVA)', 'value' => $salesTotal, 'hint' => $salesCount . ' documentos emitidos', 'icon' => 'banknotes'],
        ['label' => 'Compras del mes (sin IVA)', 'value' => $purchasesTotal, 'hint' => $purchasesCount . ' documentos recibidos', 'icon' => 'shopping-cart'],
        ['label' => 'IVA débito', 'value' => $ivaDebit, 'hint' => 'IVA cobrado en ventas', 'icon' => 'arrow-trending-up'],
        ['label' => 'IVA crédito', 'value' => $ivaCredit, 'hint' => 'IVA pagado en compras', 'icon' => 'arrow-trending-down'],
    ];
@endphp

<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <flux:heading size="xl">Dashboard</flux:heading>
                <flux:subheading>{{ $company->name }} · {{ $monthNames[$month] }} {{ $year }}</flux:subheading>
            </div>

            <form method="GET" action="{{ route('dashboard') }}" class="flex items-end gap-3">
                <flux:select name="month" label="Mes" class="w-40">
                    @foreach ($monthNames as $num => $label)
                        <flux:select.option value="{{ $num }}" :selected="$num == $month">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select name="year" label="Año" class="w-28">
                    @for ($y = now()->year; $y >= now()->year - 3; $y--)
                        <flux:select.option value="{{ $y }}" :selected="$y == $year">{{ $y }}</flux:select.option>
                    @endfor
                </flux:select>

                <flux:button type="submit" variant="primary">Ver</flux:button>
            </form>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($cards as $card)
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-center justify-between">
                        <flux:text size="sm" class="text-zinc-500">{{ $card['label'] }}</flux:text>
                        <span class="flex size-8 items-center justify-center rounded-lg bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                            <flux:icon name="{{ $card['icon'] }}" class="size-4" />
                        </span>
                    </div>
                    <div class="mt-3 text-2xl font-semibold text-zinc-900 dark:text-white">
                        ${{ number_format($card['value'], 2) }}
                    </div>
                    <flux:text size="xs" class="mt-1 text-zinc-500">{{ $card['hint'] }}</flux:text>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-900 dark:bg-blue-950/40 lg:col-span-2">
                <flux:text size="sm" class="font-medium text-blue-800 dark:text-blue-300">
                    {{ $ivaToPay >= 0 ? 'IVA a pagar del mes' : 'Remanente de crédito fiscal a favor' }}
                </flux:text>
                <div class="mt-1 text-4xl font-semibold text-blue-900 dark:text-blue-100">
                    ${{ number_format(abs($ivaToPay), 2) }}
                </div>
                <flux:text size="sm" class="mt-3 text-blue-800/80 dark:text-blue-300/80">
                    Se calcula como IVA débito menos IVA crédito. No incluye retenciones, percepciones ni anticipos de IVA recibidos.
                </flux:text>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Accesos rápidos</flux:heading>
                <div class="mt-4 flex flex-col gap-2">
                    <flux:button :href="route('sales.create')" icon="plus" variant="primary" class="w-full justify-start">Nueva venta</flux:button>
                    <flux:button :href="route('purchases.create')" icon="plus" class="w-full justify-start">Nueva compra</flux:button>
                    <flux:button :href="route('tax-annexes.index')" icon="document-text" class="w-full justify-start">Generar anexos F-07</flux:button>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">
                {{ $company->commercial_name ?? $company->name }}
            </flux:heading>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <flux:text size="sm" class="text-zinc-500">NRC</flux:text>
                    <flux:text>{{ $company->nrc ?? 'No registrado' }}</flux:text>
                </div>
                <div>
                    <flux:text size="sm" class="text-zinc-500">NIT</flux:text>
                    <flux:text>{{ $company->nit ?? 'No registrado' }}</flux:text>
                </div>
                <div>
                    <flux:text size="sm" class="text-zinc-500">Actividad económica</flux:text>
                    <flux:text>{{ $company->economic_activity ?? 'No registrada' }}</flux:text>
                </div>
                <div>
                    <flux:text size="sm" class="text-zinc-500">Estado</flux:text>
                    @if($company->status)
                        <flux:badge color="green" size="sm">Activa</flux:badge>
                    @else
                        <flux:badge color="red" size="sm">Inactiva</flux:badge>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>