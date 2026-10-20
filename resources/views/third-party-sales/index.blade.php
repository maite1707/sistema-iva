<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Ventas a Cuenta de Terceros</flux:heading>
                <flux:subheading>Anexo 4 — {{ session('company_name') }}</flux:subheading>
            </div>
            <flux:button :href="route('third-party-sales.create')" variant="primary" icon="plus">
                Nueva venta a cuenta de terceros
            </flux:button>
        </div>

        @if (session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 text-left dark:bg-neutral-800">
                    <tr>
                        <th class="px-4 py-3 font-medium text-neutral-500">Fecha documento</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Mandante</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Documento</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Monto venta</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Débito fiscal</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Liquidación</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($thirdPartySales as $sale)
                        <tr>
                            <td class="px-4 py-3">{{ $sale->document_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $sale->mandante_name }}</td>
                            <td class="px-4 py-3">{{ $sale->document_type }} {{ $sale->document_number }}</td>
                            <td class="px-4 py-3">${{ number_format($sale->sale_amount, 2) }}</td>
                            <td class="px-4 py-3">${{ number_format($sale->tax_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                {{ $sale->settlement_number ? $sale->settlement_number . ' - ' . $sale->settlement_date?->format('d/m/Y') : 'Pendiente' }}
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('third-party-sales.destroy', $sale) }}" onsubmit="return confirm('¿Anular este registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" size="sm" variant="danger">Anular</flux:button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-neutral-500">
                                Aún no has registrado ventas a cuenta de terceros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>