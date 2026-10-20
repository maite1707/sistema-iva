<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Libro de Compras</flux:heading>
            <flux:subheading>{{ session('company_name') }}</flux:subheading>
        </div>

        <form method="GET" action="{{ route('purchases.book') }}" class="flex items-end gap-4">
            <flux:select name="month" label="Mes" class="w-40">
                @foreach ([1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'] as $num => $label)
                    <flux:select.option value="{{ $num }}" :selected="$month == $num">
                        {{ $label }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:select name="year" label="Año" class="w-32">
                @for ($y = now()->year; $y >= now()->year - 3; $y--)
                    <flux:select.option value="{{ $y }}" :selected="$year == $y">
                        {{ $y }}
                    </flux:select.option>
                @endfor
            </flux:select>

            <flux:button type="submit" variant="primary">
                Consultar
            </flux:button>
        </form>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 text-left dark:bg-neutral-800">
                    <tr>
                        <th class="px-4 py-3 font-medium text-neutral-500">Fecha</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Proveedor</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">NRC/NIT</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">Documento</th>
                        <th class="px-4 py-3 font-medium text-neutral-500">N°</th>
                        <th class="px-4 py-3 text-right font-medium text-neutral-500">Gravado</th>
                        <th class="px-4 py-3 text-right font-medium text-neutral-500">Exento</th>
                        <th class="px-4 py-3 text-right font-medium text-neutral-500">IVA</th>
                        <th class="px-4 py-3 text-right font-medium text-neutral-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $purchase->issue_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $purchase->supplier->name }}</td>
                            <td class="px-4 py-3">{{ $purchase->supplier->nrc ?? $purchase->supplier->document_number ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $purchase->documentType->name }}</td>
                            <td class="px-4 py-3">{{ $purchase->document_number }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($purchase->taxable_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($purchase->exempt_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($purchase->iva_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right font-medium">${{ number_format($purchase->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-neutral-500">
                                No hay compras registradas en este período.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($purchases->isNotEmpty())
                    <tfoot class="border-t-2 border-neutral-300 bg-neutral-50 font-medium dark:border-neutral-600 dark:bg-neutral-800">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right">TOTALES</td>
                            <td class="px-4 py-3 text-right">${{ number_format($totals['taxable_amount'], 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($totals['exempt_amount'], 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($totals['iva_amount'], 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($totals['total'], 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-layouts.app>