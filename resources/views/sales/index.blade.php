<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Ventas</flux:heading>
                <flux:subheading>Ventas registradas de {{ session('company_name') }}</flux:subheading>
            </div>

            <flux:button href="{{ route('sales.create') }}" variant="primary">
                Nueva venta
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
                        <th class="px-4 py-3 font-medium text-neutral-500">Fecha</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Cliente</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Documento</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Tipo</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Gravado</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">IVA</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Total</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Estado</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($sales as $sale)
                        <tr>
                            <td class="px-4 py-3">{{ $sale->issue_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $sale->customer->name }}</td>
                            <td class="px-4 py-3">
                                <div>{{ $sale->documentType->name }}</div>
                                <div class="text-xs text-neutral-500">{{ $sale->document_number }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($sale->taxpayer_type_snapshot === 'CONTRIBUYENTE')
                                    <flux:badge color="blue" size="sm">Contribuyente</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Consumidor final</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">${{ number_format($sale->taxable_amount, 2) }}</td>
                            <td class="px-4 py-3">${{ number_format($sale->iva_amount, 2) }}</td>
                            <td class="px-4 py-3 font-medium">${{ number_format($sale->total, 2) }}</td>
                            <td class="px-4 py-3">
                                @if($sale->status)
                                    <flux:badge color="green" size="sm">Activa</flux:badge>
                                @else
                                    <flux:badge color="red" size="sm">Anulada</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <flux:button
                                        href="{{ route('sales.edit', $sale) }}"
                                        size="sm"
                                        variant="ghost"
                                    >
                                        Editar
                                    </flux:button>

                                    @if($sale->status)
                                        <form method="POST" action="{{ route('sales.destroy', $sale) }}">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button
                                                type="submit"
                                                size="sm"
                                                variant="danger"
                                                onclick="return confirm('¿Anular esta venta?')"
                                            >
                                                Anular
                                            </flux:button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-neutral-500">
                                Aún no tienes ventas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $sales->links() }}
        </div>
    </div>
</x-layouts.app>