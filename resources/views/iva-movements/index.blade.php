<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Movimientos de IVA</flux:heading>
                <flux:subheading>Retenciones, percepciones y anticipos — {{ session('company_name') }}</flux:subheading>
            </div>

            <flux:button href="{{ route('iva-movements.create') }}" variant="primary">
                Nuevo movimiento
            </flux:button>
        </div>

        @if (session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 text-left dark:bg-neutral-800">
                    <tr>
                        <th class="px-4 py-3 font-medium text-neutral-500">Anexo</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Fecha</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Contraparte</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Documento</th>
                        <th class="px-4 py-3 text-right font-medium text-neutral-500">Monto sujeto</th>
                        <th class="px-4 py-3 text-right font-medium text-neutral-500">%</th>
                        <th class="px-4 py-3 text-right font-medium text-neutral-500">Monto IVA</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($movements as $movement)
                        <tr>
                            <td class="px-4 py-3">
                                <flux:badge size="sm">{{ $movement->annexCode() }}</flux:badge>
                                <div class="mt-1 text-xs text-neutral-500">
                                    {{ $movement->movement_type }} · {{ $movement->direction }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $movement->issue_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                {{ $movement->counterparty_name ?? '—' }}
                                <div class="text-xs text-neutral-500">{{ $movement->counterparty_nit ?? $movement->counterparty_dui ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $movement->document_number ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($movement->taxable_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($movement->percentage, 2) }}%</td>
                            <td class="px-4 py-3 text-right font-medium">${{ number_format($movement->tax_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('iva-movements.destroy', $movement) }}">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button
                                        type="submit"
                                        size="sm"
                                        variant="danger"
                                        onclick="return confirm('¿Anular este movimiento?')"
                                    >
                                        Anular
                                    </flux:button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-neutral-500">
                                Aún no tienes movimientos de IVA registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $movements->links() }}
        </div>
    </div>
</x-layouts.app>